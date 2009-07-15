<?php
/*
Plugin Name: HTML Special Characters Helper
Version: 1.0
Plugin URI: http://coffee2code.com/wp-plugins/html-special-characters-helper
Author: Scott Reilly
Author URI: http://coffee2code.com
Description: Adds a tool to the Write Post page for inserting HTML encodings of special characters into the post.

The helper box is labeled "HTML Special Characters" and is present in the admin Write Post and Write Page 
pages. Clicking on any special character in the box causes its character encoding to be inserted into the
post body text field at the current cursor location (or at the end of the post if the cursor isn't located
in the post body field).  Hovering over any of the special characters causes a hover text box to appear
that shows the HTML entity encoding for the character as well as the name of the character.

The helper is available for both the non-visual editor and the visual editor modes.

In the visual editor mode, an additional interface for the HTML special characters is accessible via the
editor's toolbar: a new button with an ampersand, &, on it.  Pressing that button displays a popup that
behaves just like the sidebar box.  Note that in the visual editor mode that the special character itself
is added to the post body. Also note that the visual editor has its own special characters popup helper
accessible via the advanced toolbar, which depending on your usage, may make this plugin unnecessary for
you.  In truth, the plugin is intended more for the non-visual mode as that is the mode I (the plugin
author) use.

Compatible with WordPress 2.0+, 2.1+, 2.2+, 2.3+, and 2.5.

=>> Read the accompanying readme.txt file for more information.  Also, visit the plugin's homepage
=>> for more information and the latest updates

INSTALLATION:

1. Download the file http://www.coffee2code.com/wp-plugins/html-special-characters-helper.zip and unzip it into your 
/wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. A helper box entitled "HTML Special Characters" will now be present in your write post and write page forms.
Simply click on any character that you would like inserted into your post.  In versions of WordPress older than 
2.5 this box appears in the sidebar and can be dragged to a different position; in WordPress 2.5 it appears below
the post entry box and cannot be moved.

TODO:

	* Add support for accented characters
	
REFERENCES:

	* http://www.w3schools.com/tags/ref_entities.asp
	* http://tlt.psu.edu/suggestions/international/web/codehtml.html
	* http://wdvl.internet.com/Authoring/HTML/Entities/
*/

/*
Copyright (c) 2007-2008 by Scott Reilly (aka coffee2code)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation 
files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, 
modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the 
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

if (!class_exists('HTMLSpecialCharactersHelper')) :

class HTMLSpecialCharactersHelper {
	var $title = 'HTML Special Characters';
	var $folder = 'wp-content/plugins/html-special-characters-helper/';
    var $fullfolderurl;
	var $create_dbx_box = true;		// This is for the collapsible admin sidebar box
	var $create_editor_button = true;	// This is for the button above the post content input textbox

	function HTMLSpecialCharactersHelper() {
		$this->fullfolderurl = get_bloginfo('wpurl') . '/' . $this->folder;
		add_action('edit_page_form', array(&$this, 'insert_js'));
		add_action('edit_form_advanced', array(&$this, 'insert_js'));
		add_action('html_special_characters_head', array(&$this, 'insert_js'));
		if ($this->create_editor_button)
			add_action('init', array(&$this, 'addbuttons'));
		if ($this->create_dbx_box) {
			add_action('admin_menu', array(&$this,'admin_init')); // Use admin_init action when dropping pre-2.5 support
		}
	}

	function admin_init() {
		if (function_exists('add_meta_box')) {
			add_meta_box('htmlspecialchars', $this->title, array(&$this, 'add_meta_box'), 'page');
			add_meta_box('htmlspecialchars', $this->title, array(&$this, 'add_meta_box'), 'post');
		} else {
			add_action('dbx_page_sidebar', array(&$this, 'show_html_special_characters'));
			add_action('dbx_post_sidebar', array(&$this, 'show_html_special_characters'));
		}
	}

	function html_special_characters($category = null) {
		$codes = array(
			'common' => array(
				'&copy;' => 'copyright sign',
				'&reg;' => 'registered trade mark sign',
				'&#8482;' => 'trade mark sign',
				'&laquo;' => 'left double angle quotes',
				'&raquo;' => 'right double angle quotes',
				'&cent;' => 'cent sign',
				'&pound;' => 'pound sign',
				'&euro;' => 'euro sign',
				'&yen;' => 'yen sign', 
				'&sup1;' => 'superscript one',
				'&sup2;' => 'superscript two - squared',
				'&sup3;' => 'superscript three - cubed',
				'&deg;' => 'degree sign',
				'&frac14;' => 'fraction one quarter (1/4)',
				'&frac12;' => 'fraction one half (1/2)',
				'&frac34;' => 'fraction three quarters (3/4)',
				'&iquest;' => 'inverted question mark',
				'&iexcl;' => 'inverted exclamation mark',
				'&quot;' => 'double quotes',
				'&amp;' => 'ampersand',
				'&lt;' => 'less than sign',
				'&gt;' => 'greater than sign',
				'&apos;' => 'apostrophe',
				'&sect;' => 'subsection sign',
				'&micro;' => 'micro sign',
				'&times;' => 'multiplication sign',
				'&divide;' => 'division sign',
				'&plusmn;' => 'plus/minus symbol',
				'&middot;' => 'middle dot',
				'&para;' => 'paragraph symbol',
				'&#8211;' => 'en dash',
				'&#8212;' => 'em dash',
				'&#8230;' => 'horizontal ellipsis',
				'&bull;' => 'bullet',
				'&dagger;' => 'dagger',
				'&Dagger;' => 'double dagger',
				'&larr;' => 'left arrow',
				'&uarr;' => 'up arrow',
				'&rarr;' => 'right arrow',
				'&darr;' => 'down arrow'
			),
			'punctuation' => array(
				'&amp;'	=> 'ampersand',
				'&apos;' => 'apostrophe',
				'&quot;' => 'double quotes',
				'&laquo;' => 'left double angle quotes',
				'&raquo;' => 'right double angle quotes',
				'&ldquo;' => 'opening double quotes',
				'&rdquo;' => 'closing double quotes',
				'&lsquo;' => 'opening Single quote mark',
				'&rsquo;' => 'closing single quote mark',
				'&reg;' => 'registered symbol',
				'&copy;' => 'copyright symbol',
				'&#8482;' => 'trademark symbol',
				'&para;' =>	'paragraph symbol',
				'&bull;' => 'bullet/big dot',
				'&middot;' => 'middle dot',
				'&sect;' =>	'subsection symbol',
				'&#8211;' => 'en dash',
				'&#8212;' => 'em dash',
				'&#8230;' => 'horizontal ellipsis',
				'&iquest;' => 'inverted question mark',
				'&iexcl;' => 'inverted exclamation mark'
			),
			'currency' => array(
				'&cent;' => 'cent sign',
				'&pound;' => 'British Pound',
				'&yen;' => 'Japanese Yen',
				'&euro;' => 'Euro symbol',
				'&curren;' => 'generic currency symbol',
				'&fnof;' => 'Dutch Florin symbol'
			),
			'math' => array(
				'&gt;' => 'greater than',
				'&lt;' => 'less than',
				'&divide;' => 'division symbol',
				'&times;' => 'multiplication symbol',
				'&deg;' => 'degree symbol',
				'&not;' => 'not symbol',
				'&plusmn;' => 'plus/minus symbol',
				'&micro;' => 'Micro',
				'&there4;' => 'therefore triangle',
				'&ne;' => 'not equals',
				'&ge;' => 'greater than or equal to',
				'&le;' => 'less than or equal to',
				'&asymp;' => 'approximately',
				'&radic;' => 'square root radical',
				'&infin;' => 'infinity',
				'&int;' => 'integral sign',
				'&part;' => 'partial derivative',
				'&prime;' => 'single prime',
				'&Prime;' => 'double prime',
				'&sum;' => 'Sigma sum sign',
				'&prod;' => 'Pi product sign',
				'&permil;' => 'per mil (1/1000th)',
				'&equiv;' => 'equivalent to (three lines)',
				'&sup1;' => 'superscript one',
				'&sup2;' => 'superscript two - squared',
				'&sup3;' => 'superscript three - cubed',
				'&frac14;' => 'fraction one quarter (1/4)',
				'&frac12;' => 'fraction one half (1/2)',
				'&frac34;' => 'fraction three quarters (3/4)'
			),
			'symbols' => array(
				'&dagger;' => 'dagger',
				'&Dagger;' => 'double dagger',
				'&larr;' => 'left arrow',
				'&uarr;' => 'up arrow',
				'&rarr;' => 'right arrow',
				'&darr;' => 'down arrow'
			)
		);
		if ($category)
			$codes = $codes[$category];
		return apply_filters('c2c_html_special_characters', $codes);
	}

	// Need this function instead of having the action directly call show_html_special_characters_content() because
	//	the action sends over multiple arguments that we don't want.  Since show_html_special_characters() also calls
	//	show_html_special_characters_content() we can't just have it ignore arguments
	function add_meta_box() {
		$this->show_html_special_characters_content();
	}

	function show_html_special_characters_content($for = 'dbx', $echo = true) {
		if ($for == '') $for = 'dbx';
		$codes = $this->html_special_characters();
		$innards = '';
		$moreinnards = "<dl id='morehtmlspecialcharacters_$for' style='display:none;'>";
		$i = 0;
		foreach (array_keys($codes) as $cat) {
			if ($cat != 'common') $moreinnards .= "<dt style='font-size:xx-small;'>$cat:</dt><dd style='margin-left:6px;'>";
			foreach ($codes[$cat] as $code => $description) {
					$ecode = htmlspecialchars($code);
					$item = "<acronym onclick=\"insert_htmlspecialcharacter('$ecode');\" title='$ecode $description'> $code</acronym>";
					if ('common' == $cat) $innards .= $item;
					else $moreinnards .= $item;
			}
			if ($cat != 'common') $moreinnards .= '</dd>';
		}
		$moreinnards .= '</dl>';
		$innards = <<<HTML
		<div class="htmlspecialcharacter">
		<span id='commoncodes_$for'>$innards</span>
		<a href='#' class="htmlspecialcharacter_helplink" onclick="jQuery('#htmlhelperhelp_$for').toggle(); return false;">Help?</a>
		<a href='#' class="htmlspecialcharacter_morelink" onclick="jQuery('#commoncodes_$for, #morehtmlspecialcharacters_$for').toggle(); return false;">Toggle more</a>
		$moreinnards
		<p id="htmlhelperhelp_$for" style='font-size:x-small; display:none;'>Click to insert character into post.  Mouse-over character for more info. Some characters may not display in older browsers.</p>
		</div>
HTML;
		if ($echo) echo $innards;
		return $innards;
	}

	function show_html_special_characters($for = 'dbx') {
		$innards = $this->show_html_special_characters_content($for, false);
		echo <<<HTML
		<fieldset id="htmlspecialcharacterhelper_$for" class="dbx-box">
			<h3 class="dbx-handle">{$this->title}</h3>
			<div class="dbx-content">
				$innards
			</div>
		</fieldset>
HTML;
	}

    function addbuttons() {
    	global $wp_db_version;
        if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) return;

       	if ( 3664 <= $wp_db_version && 'true' == get_user_option('rich_editing') ) {
			// Load and append TinyMCE external plugins
			add_filter('mce_plugins', array(&$this, 'mce_plugins'));
			add_filter('mce_buttons', array(&$this, 'mce_buttons'));
			add_action('tinymce_before_init', array(&$this, 'tinymce_before_init'));
		}
	}
	
	function mce_plugins($plugins) {
            array_push($plugins, '-htmlspecialcharactershelper');
            return $plugins;
    }
    function mce_buttons($buttons) {
            array_push($buttons, 'separator', 'htmlspecialcharactershelper');
            return $buttons;
    }
    function tinymce_before_init() {
            echo 'tinyMCE.loadPlugin("htmlspecialcharactershelper", "' . $this->fullfolderurl . "tinymce/\");\n";
    }

	function insert_js() {
		echo <<<HTML
		<style type="text/css">
			.htmlspecialcharacter acronym {
				margin:0.1em 0.5em 0.1em 0;
				cursor:pointer;
			}
			.htmlspecialcharacter a {
				font-size:xx-small;
			}
			#htmlspecialcharacterhelper_rte {
				border:0;
			}
			#htmlspecialcharacterhelper_rte acronym {
				font-size:1.2em;
			}
			#htmlspecialcharacterhelper_rte h3.dbx-handle, #htmlspecialcharacterhelper_rte a.htmlspecialcharacter_helplink {
				display:none;
			}
		</style>
		<script type="text/javascript">
			function insert_htmlspecialcharacter(character) {
				var inst;
				if ((typeof tinyMCE != "undefined") && (inst = tinyMCE.selectedInstance)) {
					try {
						if (tinyMCEPopup)
							tinyMCEPopup.restoreSelection();
					} catch(e) {
						inst.getWin().focus();
						if (inst.selectionBookmark)
							inst.selection.moveToBookmark(inst.selectionBookmark);
					}
					tinyMCE.execCommand('mceInsertContent', false, character);
				} else {
					edInsertContent(edCanvas, character);
				}
				return false;
			}
			function htmlspecialcharactershelper_buttonscript() {
				alert("I am here!");
			}
		</script>
HTML;
	}
} // end HTMLSpecialCharactersHelper

endif; // end if !class_exists()
if (class_exists('HTMLSpecialCharactersHelper')) :

	$HTMLSpecialCharactersHelper = new HTMLSpecialCharactersHelper();

endif; // end if class_exists()

?>