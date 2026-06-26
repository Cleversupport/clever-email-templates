=== Email Templates Customizer and Designer for WordPress and WooCommerce ===
Contributors: wpexpertsio
Tags: Email templates, email designer, email customizer, email, woocommerce email
Requires at least: 7.4
Tested up to: 7.0
Stable tag: 1.5.14
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Design and send custom emails with Email Templates plugin for WordPress and WooCommerce

== Description ==

Email Templates is a WordPress plugin that allows you to customize your WooCommerce and WordPress website’s default transactional email designs and templates. 

The easy-to-use editor lets you choose a template style, and you can customize it by adding a logo or text, changing colors, and editing the footer.

= Why use Email templates for your WordPress/WooCommerce website? =
* Live preview your WordPress and WooCommerce emails
* Customize emails to match your brand’s color theme
* Customize your email’s heading, subtitle, and body text (including header and footer text)
* Choose from a variety of template styles
* Configure settings like the sender’s name and email address
* Send test emails to the administrator’s email address
* View your WooCommerce order emails or choose to view a mockup template.
* Select email type from a dropdown list - New order, canceled order, customer processing order, customer completed order, customer refunded order, customer on-hold order, customer invoice, failed order, new account, customer note, reset password.
* Each email type has a default email template and template settings
* Import/export custom style settings
* Send preview email after importing custom style settings

= Compatible with Post SMTP Mailer/Email Log – Best Mail SMTP For WP =
[PostSMTP](https://wordpress.org/plugins/post-smtp/) is a next-generation WP Mail SMTP plugin that assists and improves the email deliverability process of your WordPress website.

**Easy-to-use and reliable** – 300,000+ customers trust Post SMTP Mailer to send their daily WordPress emails to millions of users worldwide.

== Email Templates Features ==

= WordPress Email Template - General Settings =
* Choose the size of your email template (boxed or full-width)
* Add custom CSS to your email template
* Choose the background color for your email template
* Resize the body of the email box using a slider

= WordPress Email Template - Header Settings =
* Add an image to your email template’s header
* Add text and color to your email header.
* Choose an alignment for the email’s header (Left, Center, Right)
* Increase or decrease the size of the text in the header.

= WordPress Email Template - Email Body Settings =
* Set a background color for the email body
* Set a text color for links in the email body

= WordPress Email Template - Footer Settings =
* Edit the text on the email footer
* Choose an alignment for the email footer (Left, Center, Right)
* Set a background color for the email footer
* Resize the footer text using a slider
* Set the color of the text on the footer
* Enable/disable the link to the plugin page (Powered by)


= Minimum Requirements = 
WordPress 4.0.0

= Help with translations =

Send your translations to [Transifex](https://www.transifex.com/projects/p/wp-email-templates/)

= Currently Available in: =
*   English
*   Spanish
*   French
*   Chinese
*   Portuguese
*   Dutch
*   Persian
*   Russian
*   German

= Collaborate in Github = - [https://github.com/wpexpertsio/wordpress-email-templates](https://github.com/wpexpertsio/wordpress-email-templates)

= Latest Update =

**Email Templates v1.5**

**Text box implementation**
Added a text box above the slider so the Administrator can manually enter the number. In the color option, the admin can select the color from the color grid and can also enter the color code manually.

**Template Settings**
Gave the option a particular name called “Template Setting”

**Relocated the **Border Color** option in Template Settings**
Now, the Border Color option would appear after the **Border Right Width** option and above the **Border Radius** option to define the border widths properly. 

**Rename and Capitalization of Options For Template Settings**
Some option names, such as ‘bottom padding’, ‘Custom css’, and ‘box shadow’, have been capitalized for better readability. 

**Rename and Capitalization of Options for Email Header**
Spelling Corrections, Capitalization, and the Renaming of options in the Email Header tab. 

**Rename and Capitalization of Options in Email Body**
Spelling Corrections, Capitalization, and the Renaming of options in the Email Body tab. 

**Slider Issue In Footer Text Padding Top And Footer Text Padding Bottom Options**
Added a textbox to manually adjust the slider in the ‘Footer Text Padding Top’ and ‘Footer Text Padding Bottom’ options in the Footer tab. 

**Rename and Capitalization of Options in Footer**
Spelling corrections, capitalization, and the renaming of options in the **Footer** tab. 

**Rename and Capitalization of Options in Subtitle Styles (WooCommerce)**
Spelling Corrections, capitalization, and the renaming of options in the **Subtitle Styles (WooCommerce)** tab. 

**Dropdown Menu was not appearing correctly**
The dropdown menu was appearing incorrectly, and the first option should be selected by default since it works like that in the previous options. Now it has been fixed. 

**Order Table Styles option | No reflection in real-time**
Previously, this option was not reflecting any change when any sub-option was selected from the dropdown menu. It has been fixed now. 

**Product Image & Product Image Size options | No reflection in real-time**
Both options (Product Image & Product Image Size) were not reflecting any change when any sub-option was selected from the dropdown menu. It has been fixed now. 

**Order Table Border Color option | No reflection in real-time**
Previously, this option was not reflecting any change in real time. Now it has been fixed. 

**Order Table Heading Style option | No reflection on real-time**
Previously, this option was not reflecting any change in real time. Now it has been fixed.  

**Slider issue in Footer Text Padding Top/Bottom, Left/Right, and Border Width options**
The numbers were not appearing on the slider in the **Padding Top/Bottom** and **Padding Left/Right** options in the **Footer** tab. Now it has been fixed by implementing a text box so the admin can manually enter the numbers. 

**Enable order notes to be moved bellow option | Spelling mistake and No reflection on real-time | Order Items Styles (WooCommerce)**
Spelling correction was made, and real-time reflection was fixed. 

**Rename the "Clear" button to "Default" in color grid options - Order Items Styles (WooCommerce)**
The color grid option had the option name “Clear” which has been replaced by “Default” for better user understanding. 

**Rename and Capitalization of Options in Order Items Styles (WooCommerce)**
Spelling mistakes and capitalization of words were corrected in various places.

**Button Font Size option Slider issue in Button Style (WooCommerce)**
The numbers were not appearing on the slider in the **Button Font Size** option in the Footer tab. Now it has been fixed by implementing a text box to enable the admin to enter numbers manually. 

== Installation ==

1. Upload the plugin in /wp-admin/plugin-install.php
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Click on Email Templates -> "WordPress Email" to start editing
4. Click on Email Templates -> "WooCommerce Email" to start editing

== Frequently Asked Questions ==

= How to add a custom template? =

Copy the templates folder into your theme, then in functions.php add the following:

`add_filter('mailtpl_customizer_template', function($default_template_path){
    return get_stylesheet_directory() . '/email/templates/default.php';
});`

== Screenshots ==

1. Email Templates - Settings
2. Boxed layout
3. Full-width layout
4. Email Notification
5. Background Color
6. Send Preview Email
7. Font Family

== Changelog ==

= 1.5.14 =
Fixed -  Logo header image size.

= 1.5.13 =
Tweak – Code Improvement.
Fixed -  Resolved conflicts with some plugins.

= 1.5.12 =
Tweak – Compatible with WordPress Version 7.0

= 1.5.11 =
Fixed - Minor bug fixes.

= 1.5.10 =
Fixed: Default template override.
Fixed - Minor bug fixes.

= 1.5.9 =
Fixed: Double HTML Wrapper When Using Elementor Forms.

= 1.5.8 =
Fixed: Updated WooCommerce Refunded order email template strings to match latest core changes.

= 1.5.7 =
Fixed: Updated WooCommerce email template strings to match latest core changes.

= 1.5.6 =
* Removed Admin Notice

= 1.5.5 =
* Added Recommendation

= 1.5.4 =
* Added - Body text field in woocommerce templates.

= 1.5.3 =
* Fix - Minor bug fixes

= 1.5.2 =
* Tweak - Update plugin header

= 1.5.1 =
* Tweak - Tested Upto WordPress Latest Version 6.8

= 1.5 =
* New - Added a text box above the slider.
* Tweak - Minor improvements & code optimization.

= 1.4.3 =
* Improvement - Code Optimization.
* Compatible with WordPress v6.3.2

= 1.4.2 =
* Added toggle switch for users who don't want to use the default WooCommerce template.

= 1.4.1 =
* Fixed WooCommerce required error.

= 1.4 =
* NEW - Design/Customize WooCommerce emails.
* NEW - Added Live preview your WooCommerce emails.

= 1.3.2.1 =
* Emails not being send when multiple emails where being sent at the same time

= 1.3.2 =
* Support for multiple templates PR #29
* Fix Increase priority for the preview template

= 1.3.1.2 =
* Fixed css width
* new filter for default message

= 1.3.1.1 =
* Only filter non html messages
* Fixed bug introduced on 1.3.1

= 1.3.1 =
* Security fix to prevent html injection
* Filter attributes for images

= 1.3 =
* Instead of multiple filters we now just modify wp_mail to make plugin more compatible
with transactional mail plugins

= 1.2.2.3 =
* Fixed issue with maxwith not working on certain installs.

= 1.2.2.2 =
* Fixed issue with boxed layout

= 1.2.2.1 =
* Text domain update

= 1.2.2 =
* Added image support in header text
* Fixed issue with spaces on gravity forms ( gravity plugin needs to be >= 2.2.1.5 )

= 1.2.1 =
* Added shortcode support in header/footer
* Header text now it's used for alt image when using images
* Fixed bug where image was not responsive on mobile devices

= 1.2 =
* Added custom css support on template section
* Added link color in body section
* Updated templates with changes above
* Mailgun / sengrid integration

= 1.1.4 =

* Added body size to template section
* Leaving emtpty from name & from email will let you use other plugins settings now
* Logo alt text is now site description by default
* Removed other panels showing on email templates customizer
* Removed email templates panel from normal customizer

= 1.1.3.1 =
* Fixed woocommerce preview link

= 1.1.3 =
* Fixed bug with some links missing or not clickable
* Added more languages and updated some
* Added more action hooks for devs

= 1.1.2.1 =
* Remove "powered by" by default
* Updated languages

= 1.1.2 =
* Fixed bug with powered by still showing on some mail clients
* Added new languages

= 1.1.1 =
* Added Postman SMTP compatibility
* Added WP SMTP compatibility
* Added Easy WP SMTP compatibility
* Added Easy SMTP Mail compatibility

= 1.1 =
* Fixed bug with wpmandrill
* Added chinese, spanish and portuguese languages
* Added new font size control
* WooCommerce Integration
* Easy Digital Downloads Integration
* Added Email body settings

= 1.0.2 =

* Fixed email link on retrieve password emails from WP

= 1.0.1 =

* Bug - Template is cached to avoid issues when sending multiple emails
* Added fallback text email for non html email clients and to improve inbox hits
* Added site url to the logo/text logo in header
* Fixed some typos in descriptions
* Added Emails templates menu using add_submenu_page


= 1.0 =
* First release