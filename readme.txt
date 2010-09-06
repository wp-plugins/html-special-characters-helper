=== HTML Special Characters Helper ===
Contributors: coffee2code
Donate link: http://coffee2code.com/donate
Tags: post, admin widget, html special characters, write post, dbx, entity codes, coffee2code
Requires at least: 2.6
Tested up to: 3.0.1
Stable tag: 1.6
Version: 1.6

Admin widget on the Write Post page for inserting HTML encodings of special characters into the post.


== Description ==

Admin widget on the Write Post page for inserting HTML encodings of special characters into the post.

The admin widget is labeled "HTML Special Characters" and is present in the admin Add/Edit Post and Add/Edit Page pages. Clicking on any special character in the widget causes its character encoding to be inserted into the post body text field at the current cursor location (or at the end of the post if the cursor isn't located in the post body field).  Hovering over any of the special characters causes a hover text box to appear that shows the HTML entity encoding for the character as well as the name of the character.

Note that when used in the visual editor mode the special character itself is added to the post body. Also note that the visual editor has its own special characters popup helper accessible via the advanced toolbar, which depending on your usage, may make this plugin unnecessary for you.  In truth, the plugin is intended more for the non-visual (aka HTML) mode as that is the mode I (the plugin author) use.


== Installation ==

1. Unzip `html-special-characters-helper.zip` inside the `/wp-content/plugins/` directory (or install via the built-in WordPress plugin installer)
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. An admin widget entitled "HTML Special Characters" will now be present in your write post and write page forms.  Simply click on any character that you would like inserted into your post.

== Frequently Asked Questions ==

= How do I use the "HTML Special Characters" admin widget to insert special characters into other post fields (such as the post title)? =

You can't.  The plugin only inserts the HTML character encodings into the post body.  However, you can certainly hover over the character you want to use to see the HTML encoding for it (it'll start with an ampersand, `&`, and end with a semi-color, `;`) and type that into the field. 

= I've activated the plugin and don't see the "HTML Special Characters" admin widget when I go to write a post; where is it? =

It is in the sidebar, most likely at the sidebar's bottom.

= Have any references? =

Try:
* http://www.w3schools.com/tags/ref_entities.asp
* http://tlt.psu.edu/suggestions/international/web/codehtml.html
* http://wdvl.internet.com/Authoring/HTML/Entities/


== Screenshots ==

1. A screenshot of the HTML Special Characters admin widget in its default state
2. A screenshot of the HTML Special Characters admin widget when "Toggle More" is clicked to display more special characters.  Note all characters are categorized into labeled sections
3. A screenshot of the HTML Special Characters admin widget after "Help?" is clicked
4. A screenshot of the HTML Special Characters admin widget when the mouse is hovering over one of the special characters.  The hover text that appears shows the HTML entity encoding for the character as well as the name of the character


== Changelog ==

= 1.6 =
* Extract all inline JavaScript into add_admin_js() and output via admin_print_footer_scripts action
* Extract all inline CSS into add_admin_css()
* Only output CSS on the add/edit post/page pages
* Remove all references to $for (which was context variable that lingered from former rte popup)
* Remove JavaScript related to inserting text into editor and just use send_to_editor()
* Change the 'Toggle more?' link to 'See more'/'See less' (JavaScript toggles between the two as appropriate)
* Move hooking of actions out of constructor and into class's admin_init()
* Rename add_css() to add_admin_css()
* Assign object instance to global variable, $c2c_html_special_characters_helper, to allow for external manipulation
* Rename class from 'HTMLSpecialCharactersHelper' to 'c2c_HTMLSpecialCharactersHelper'
* Don't define class unless within admin section
* Note compatibility with WP 3.0+
* Minor code reformatting (spacing)
* Remove documentation and instructions from top of plugin file (all of that and more are contained in readme.txt)
* Add PHPDoc documentation
* Add package info to top of file
* Update copyright date
* Add Upgrade Notice section to readme.txt

= 1.5 =
* Added 78 new characters to extended characters listing: left-right arrow, carriage return arrow, lozenge, clubs, hearts, diamonds, spades, for all, there exists, empty set, intersection, union, backward difference, angle, logical and, logical or, 49 Greek characters, 5 double arrows, plus, minus, dot operator, orthogonal to, feminine ordinal indicator, masculine ordinal indicator, fraction slash, cedilla
* Tweaked description of a few existing special characters
* Reordered some of the existing special characters
* Removed rich text editor toolbar button and all related code and files (including html-special-characters.php, and tinymce/*)
* Added title attribute to links for Help and Toggle More
* Removed create_dbx_box variable from class, since it controlled what is now the sole behavior of the plugin
* Minor reformatting (spacing)
* Updated screenshots
* Updated copyright date
* Noted compatibility through 2.8+
* Dropped compatibility with versions of WP older than 2.6

= 1.0 =
* Initial release


== Upgrade Notice ==

= 1.6 =
Recommended major compatibility update. Highlights: JS/CSS handling tweaks; misc non-functionality tweaks; noted compatibility with WP 3.0+.