=== WP Link Pages Extended ===

Contributors: HoosierDragon (Terry O'Brien, dragonmage@terryobrien.me)
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=s-xclick&hosted_button_id=THLBLFT4BV7E2
Tags: nextpage, pagination, wp_link_pages, wp_link_pages_args
Requires at least: 2.7
Tested up to: 4.3.1
Stable tag: 1.0
Author: Terry O'Brien
Author link: http://www.terryobrien.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Replaces the boring nextpage pagination navigation system with something more informative that includes the page number and page count and links to the First and Previous and Next and Last pages. It also provides a link to display all pages as a single page and back. 

== Installation ==

1. Unzip 'wp-link-pages-extended.zip' inside the '/wp-content/plugins/' directory or install via the built-in WordPress plugin installer.
1. Activate the plugin through the WordPress 'Plugins' admin page.

== Screenshots ==

1. Full pagination system
1. Pagination links when viewing page or post as a single page

== Frequently Asked Questions ==

= When viewing the "View All" extended link list, why is the first page number listed not a link to the first page of the page or post?

The `wp_link_pages()` function that generates the internal link list is seeing the existing page as the first page and therefore is not producing a link to itself.

== Changelog ==

= 1.0 =

Initial Release

== Upgrade Notice ==

= 1.0 =

Initial Release
