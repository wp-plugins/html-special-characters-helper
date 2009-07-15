=== HTML Special Characters Helper ===
Contributors: coffee2code
Donate link: http://coffee2code.com/donate
Tags: html special characters, write post, dbx, entity codes, coffee2code
Requires at least: 2.0
Tested up to: 2.5
Stable tag: 1.0
Version: 1.0

Adds a tool to the Write Post page for inserting HTML encodings of special characters into the post.

== Description ==

Adds a tool to the Write Post page for inserting HTML encodings of special characters into the post.

The helper box is labeled "HTML Special Characters" and is present in the admin Write Post and Write Page pages. Clicking on any special character in the box causes its character encoding to be inserted into the post body text field at the current cursor location (or at the end of the post if the cursor isn't located in the post body field).  Hovering over any of the special characters causes a hover text box to appear that shows the HTML entity encoding for the character as well as the name of the character.

The helper is available for both the non-visual editor and the visual editor modes.

In the visual editor mode, an additional interface for the HTML special characters is accessible via the editor's toolbar: a new button with an ampersand, &, on it.  Pressing that button displays a popup that behaves just like the sidebar box.  Note that in the visual editor mode that the special character itself is added to the post body. Also note that the visual editor has its own special characters popup helper accessible via the advanced toolbar, which depending on your usage, may make this plugin unnecessary for you.  In truth, the plugin is intended more for the non-visual mode as that is the mode I (the plugin author) use.

== Installation ==

1. Unzip `html-special-characters-helper.zip` inside the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. A helper box entitled "HTML Special Characters" will now be present in your write post and write page forms.  Simply click on any character that you would like inserted into your post.  In versions of WordPress older than 2.5 this box appears in the sidebar and can be dragged to a different position; in WordPress 2.5 it appears below the post entry box and cannot be moved.

== Frequently Asked Questions ==

= How do I use the "HTML Special Characters" helper box to insert special characters into other post fields (such as the post title)? =

You can't.  The plugin only inserts the HTML character encodings into the post body.  However, you can certainly hover over the character you want to use to see the HTML encoding for it (it'll start with an ampersand, `&`, and end with a semi-color, `;`) and type that into the field. 

= I've activated the plugin and don't see the "HTML Special Characters" helper box when I go to write a post; where is it? =

In WP 2.3 the helper box is initially added to the bottom of your admin sidebar.  You may want to drag it up the sidebar into a higher position closer to the post entry field.  In WP 2.5 the box is located below the post entry field and, unfortunately, is not currently drag-n-droppable.

== Screenshots ==

1. A screenshot of the HTML Special Characters helper box in its default state
2. A screenshot of the HTML Special Characters helper box when "Toggle More" is clicked to display more special characters.  Note all characters are categorized into labeled sections
3. A screenshot of the HTML Special Characters helper box after "Help?" is clicked
4. A screenshot of the HTML Special Characters helper box when the mouse is hovering over one of the special characters.  The hover text that appears shows the HTML entity encoding for the character as well as the name of the character
5. A screenshot of the HTML Special Characters popup helper box used with the visual editor.  Special characters appear as the special character in the visual editor.  The popup box is accessible via the `&` editor toolbar button and behaves just like the sidebar box.
