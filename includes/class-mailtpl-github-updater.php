<?php
/**
 * GitHub updater for Clever Email Templates.
 *
 * Allows WordPress to detect and install updates from GitHub releases.
 *
 * @package Mailtpl
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Mailtpl_GitHub_Updater' ) ) {
	/**
	 * GitHub release updater.
	 */
	class Mailtpl_GitHub_Updater {

		/**
		 * Repository owner/name.
		 *
		 * @var string
		 */
		private $repo = 'Cleversupport/clever-email-templates';

		/**
		 * GitHub repository URL.
		 *
		 * @var string
		 */
		private $repo_url = 'https://github.com/Cleversupport/clever-email-templates';

		/**
		 * GitHub latest release API endpoint.
		 *
		 * @var string
		 */
		private $api_url = 'https://api.github.com/repos/Cleversupport/clever-email-templates/releases/latest';

		/**
		 * Main plugin file path.
		 *
		 * @var string
		 */
		private $plugin_file;

		/**
		 * Plugin basename, e.g. clever-email-templates/email-templates.php.
		 *
		 * @var string
		 */
		private $plugin_basename;

		/**
		 * Current plugin version.
		 *
		 * @var string
		 */
		private $version;

		/**
		 * Plugin slug, based on installed directory name.
		 *
		 * @var string
		 */
		private $slug;

		/**
		 * Transient cache key.
		 *
		 * @var string
		 */
		private $cache_key = 'mailtpl_github_latest_release';

		/**
		 * Constructor.
		 *
		 * @param string $plugin_file Main plugin file.
		 * @param string $version Current plugin version.
		 */
		public function __construct( $plugin_file, $version ) {
			$this->plugin_file     = $plugin_file;
			$this->plugin_basename = plugin_basename( $plugin_file );
			$this->version         = $version;
			$this->slug            = dirname( $this->plugin_basename );

			// Native WordPress custom plugin update hook. The hostname comes from the Update URI header.
			add_filter( 'update_plugins_github.com', array( $this, 'check_update_uri_update' ), 10, 4 );

			// Fallback for older WordPress versions and manual update checks.
			add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update_transient' ) );

			// Plugin details modal in wp-admin.
			add_filter( 'plugins_api', array( $this, 'plugin_information' ), 20, 3 );

			// Clear cached GitHub release data after an update.
			add_action( 'upgrader_process_complete', array( $this, 'clear_release_cache' ), 10, 2 );
		}

		/**
		 * Check updates through the Update URI hook.
		 *
		 * @param array|false $update Current update data.
		 * @param array       $plugin_data Plugin headers.
		 * @param string      $plugin_file Plugin basename.
		 * @param string[]    $locales Locales.
		 * @return array|false
		 */
		public function check_update_uri_update( $update, $plugin_data, $plugin_file, $locales ) {
			if ( $plugin_file !== $this->plugin_basename ) {
				return $update;
			}

			return $this->get_update_payload( $plugin_data );
		}

		/**
		 * Check updates through the standard plugin update transient.
		 *
		 * @param object $transient Update transient.
		 * @return object
		 */
		public function check_update_transient( $transient ) {
			if ( ! is_object( $transient ) ) {
				return $transient;
			}

			if ( empty( $transient->checked ) || empty( $transient->checked[ $this->plugin_basename ] ) ) {
				return $transient;
			}

			$payload = $this->get_update_payload();

			if ( $payload ) {
				$transient->response[ $this->plugin_basename ] = (object) $payload;
			} elseif ( isset( $transient->response[ $this->plugin_basename ] ) ) {
				unset( $transient->response[ $this->plugin_basename ] );
			}

			return $transient;
		}

		/**
		 * Provide plugin details in the WordPress update modal.
		 *
		 * @param false|object|array $result Existing result.
		 * @param string             $action API action.
		 * @param object             $args Arguments.
		 * @return false|object|array
		 */
		public function plugin_information( $result, $action, $args ) {
			if ( 'plugin_information' !== $action ) {
				return $result;
			}

			if ( empty( $args->slug ) || $args->slug !== $this->slug ) {
				return $result;
			}

			$release = $this->get_latest_release();
			if ( empty( $release['tag_name'] ) ) {
				return $result;
			}

			$latest_version = $this->normalize_version( $release['tag_name'] );
			$download_url   = $this->get_release_package_url( $release );
			$body           = ! empty( $release['body'] ) ? $release['body'] : 'GitHub release for Clever Email Templates.';

			return (object) array(
				'name'          => 'Clever Email Templates',
				'slug'          => $this->slug,
				'plugin'        => $this->plugin_basename,
				'version'       => $latest_version,
				'author'        => '<a href="https://github.com/Cleversupport">Clever</a>',
				'homepage'      => $this->repo_url,
				'requires'      => '4.8',
				'tested'        => '7.0',
				'requires_php'  => '7.1',
				'download_link' => $download_url,
				'sections'      => array(
					'description' => 'Site Mailer compatible HTML email templates for WordPress.',
					'changelog'   => nl2br( esc_html( $body ) ),
				),
			);
		}

		/**
		 * Build WordPress update payload.
		 *
		 * @param array $plugin_data Optional plugin headers.
		 * @return array|false
		 */
		private function get_update_payload( $plugin_data = array() ) {
			$release = $this->get_latest_release();

			if ( empty( $release['tag_name'] ) ) {
				return false;
			}

			$latest_version = $this->normalize_version( $release['tag_name'] );

			if ( ! version_compare( $latest_version, $this->version, '>' ) ) {
				return false;
			}

			$package_url = $this->get_release_package_url( $release );

			if ( empty( $package_url ) ) {
				return false;
			}

			return array(
				'id'             => $this->repo_url,
				'slug'           => $this->slug,
				'plugin'         => $this->plugin_basename,
				'new_version'    => $latest_version,
				'version'        => $latest_version,
				'url'            => ! empty( $release['html_url'] ) ? $release['html_url'] : $this->repo_url,
				'package'        => $package_url,
				'requires'       => ! empty( $plugin_data['RequiresWP'] ) ? $plugin_data['RequiresWP'] : '4.8',
				'tested'         => ! empty( $plugin_data['Tested'] ) ? $plugin_data['Tested'] : '7.0',
				'requires_php'   => ! empty( $plugin_data['RequiresPHP'] ) ? $plugin_data['RequiresPHP'] : '7.1',
				'upgrade_notice' => ! empty( $release['body'] ) ? wp_strip_all_tags( $release['body'] ) : '',
			);
		}

		/**
		 * Retrieve latest GitHub release.
		 *
		 * @return array|false
		 */
		private function get_latest_release() {
			$cached = get_site_transient( $this->cache_key );

			if ( false !== $cached ) {
				return ! empty( $cached ) ? $cached : false;
			}

			$response = wp_remote_get(
				$this->api_url,
				array(
					'timeout' => 10,
					'headers' => array(
						'Accept'               => 'application/vnd.github+json',
						'X-GitHub-Api-Version' => '2022-11-28',
					),
				)
			);

			if ( is_wp_error( $response ) ) {
				set_site_transient( $this->cache_key, array(), HOUR_IN_SECONDS );
				return false;
			}

			$status_code = wp_remote_retrieve_response_code( $response );

			if ( 200 !== (int) $status_code ) {
				set_site_transient( $this->cache_key, array(), HOUR_IN_SECONDS );
				return false;
			}

			$release = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( ! is_array( $release ) || empty( $release['tag_name'] ) ) {
				set_site_transient( $this->cache_key, array(), HOUR_IN_SECONDS );
				return false;
			}

			set_site_transient( $this->cache_key, $release, 6 * HOUR_IN_SECONDS );

			return $release;
		}

		/**
		 * Get ZIP package URL from release assets, with fallback to GitHub source ZIP.
		 *
		 * Prefer release assets because those can be packaged with the correct plugin folder.
		 *
		 * @param array $release GitHub release.
		 * @return string
		 */
		private function get_release_package_url( $release ) {
			if ( ! empty( $release['assets'] ) && is_array( $release['assets'] ) ) {
				foreach ( $release['assets'] as $asset ) {
					if ( empty( $asset['browser_download_url'] ) || empty( $asset['name'] ) ) {
						continue;
					}

					if ( preg_match( '/\.zip$/i', $asset['name'] ) ) {
						return $asset['browser_download_url'];
					}
				}
			}

			return ! empty( $release['zipball_url'] ) ? $release['zipball_url'] : '';
		}

		/**
		 * Normalize GitHub tag to a semantic version.
		 *
		 * @param string $tag GitHub tag, e.g. v1.5.16.
		 * @return string
		 */
		private function normalize_version( $tag ) {
			return ltrim( (string) $tag, 'vV' );
		}

		/**
		 * Clear release cache after plugin updates.
		 *
		 * @param WP_Upgrader $upgrader Upgrader instance.
		 * @param array       $hook_extra Extra hook data.
		 * @return void
		 */
		public function clear_release_cache( $upgrader, $hook_extra ) {
			if ( empty( $hook_extra['type'] ) || 'plugin' !== $hook_extra['type'] ) {
				return;
			}

			delete_site_transient( $this->cache_key );
		}
	}
}
