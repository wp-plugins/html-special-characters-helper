<?php
/**
 * @package HTML_Special_Characters_Helper
 * @author Scott Reilly
 * @version 1.7.1
 */
/*
Plugin Name: HTML Special Characters Helper
Version: 1.7.1
Plugin URI: http://coffee2code.com/wp-plugins/html-special-characters-helper/
Author: Scott Reilly
Author URI: http://coffee2code.com
Description: Admin widget on the Write Post page for inserting HTML encodings of special characters into the post.

Compatible with WordPress 2.6+, 2.7+, 2.8+, 2.9+, 3.0+, 3.1+, 3.2+.

=>> Read the accompanying readme.txt file for instructions and documentation.
=>> Also, visit the plugin's homepage for additional information and updates.
=>> Or visit: http://wordpress.org/extend/plugins/html-special-characters-helper/

TODO:
	* Localize
	* Front-end widget to facilitate use in comments
	* Enqueue scripts and styles?
	* Make it possible to attach HTML character insertion into any input field
*/

/*
Copyright (c) 2007-2011 by Scott Reilly (aka coffee2code)

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

if ( is_admin() && ! class_exists( 'c2c_HTMLSpecialCharactersHelper' ) ) :

class c2c_HTMLSpecialCharactersHelper {
	public static $title = 'HTML Special Characters';

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'do_init' ) );
	}

	/**
	 * Hook actions and register adding the plugins admin meta box
	 *
	 * @return void
	 */
	public static function do_init() {
		global $pagenow;
		if ( in_array( $pagenow, array( 'page.php', 'page-new.php', 'post.php', 'post-new.php' ) ) ) {
			add_action( 'admin_head', array( __CLASS__, 'insert_admin_css' ) );
			add_action( 'admin_print_footer_scripts', array( __CLASS__, 'insert_admin_js' ) );
		}
		add_meta_box( 'htmlspecialchars', self::$title, array( __CLASS__, 'add_meta_box' ), 'page', 'side' );
		add_meta_box( 'htmlspecialchars', self::$title, array( __CLASS__, 'add_meta_box' ), 'post', 'side' );
	}

	/**
	 * Returns an associative array of all the categories of HTML special characters, their entities/codes, and their descriptions.
	 *
	 * @param string|null $category (optional) The name of the sub-category of codes to return. Default of null returns all
	 * @return array Array of HTML special characters
	 */
	public static function html_special_characters( $category = null ) {
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
				'&szlig;' => 'sharp s / ess-zed',
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
				'&fnof;' => 'Dutch Florin symbol',
				'&curren;' => 'generic currency symbol'
			),
			'math' => array(
				'&fnof;' => 'function',
				'&gt;' => 'greater than',
				'&lt;' => 'less than',
				'&ge;' => 'greater than or equal to',
				'&le;' => 'less than or equal to',
				'&ne;' => 'not equal to',
				'&asymp;' => 'approximately',
				'&equiv;' => 'identical to',
				'&minus;' => 'minus sign',
				'&divide;' => 'division sign',
				'&times;' => 'multiplication sign',
				'&deg;' => 'degree symbol',
				'&not;' => 'not symbol',
				'&plusmn;' => 'plus/minus symbol',
				'&micro;' => 'Micro',
				'&forall;' => 'for all',
				'&exist;' => 'there exists',
				'&there4;' => 'therefore triangle',
				'&radic;' => 'square root radical',
				'&infin;' => 'infinity',
				'&int;' => 'integral sign',
				'&part;' => 'partial differential',
				'&sdot;' => 'dot operator',
				'&prime;' => 'single prime',
				'&Prime;' => 'double prime',
				'&sum;' => 'n-ary summation',
				'&prod;' => 'n-ary product',
				'&permil;' => 'per mil (1/1000th)',
				'&perp;' => 'orthogonal to / perpendicular',
				'&ang;' => 'angle',
				'&and;' => 'logical and',
				'&or;' => 'logical or',
				'&cap;' => 'intersection',
				'&cup;' => 'union',
				'&empty;' => 'empty set',
				'&nabla;' => 'nabla, backward difference',
				'&frasl;' => 'fraction slash',
				'&sup1;' => 'superscript one',
				'&sup2;' => 'superscript two - squared',
				'&sup3;' => 'superscript three - cubed',
				'&frac14;' => 'fraction one quarter (1/4)',
				'&frac12;' => 'fraction one half (1/2)',
				'&frac34;' => 'fraction three quarters (3/4)',
				'&ordf;' => 'feminine ordinal indicator',
				'&ordm;' => 'masculine ordinal indicator'
			),
			'symbols' => array(
				'&cedil;' => 'cedilla',
				'&dagger;' => 'dagger',
				'&Dagger;' => 'double dagger',
				'&larr;' => 'left arrow',
				'&uarr;' => 'up arrow',
				'&rarr;' => 'right arrow',
				'&darr;' => 'down arrow',
				'&harr;' => 'left-right arrow',
				'&crarr;' => 'carriage return',
				'&lArr;' => 'left double arrow',
				'&uArr;' => 'up double arrow',
				'&rArr;' => 'right double arrow',
				'&dArr;' => 'down double arrow',
				'&hArr;' => 'left-right double arrow',
				'&loz;' => 'lozenge',
				'&clubs;' => 'clubs',
				'&hearts;' => 'hearts',
				'&diams;' => 'diamonds',
				'&spades;' => 'spades'
			),
			'greek' => array(
				'&Alpha;' => 'Greek capital letter alpha',
				'&Beta;' => 'Greek capital letter beta',
				'&Gamma;' => 'Greek capital letter gamma',
				'&Delta;' => 'Greek capital letter delta',
				'&Epsilon;' => 'Greek capital letter epsilon',
				'&Zeta;' => 'Greek capital letter zeta',
				'&Eta;' => 'Greek capital letter eta',
				'&Theta;' => 'Greek capital letter theta',
				'&Iota;' => 'Greek capital letter iota',
				'&Kappa;' => 'Greek capital letter kappa',
				'&Lambda;' => 'Greek capital letter lambda',
				'&Mu;' => 'Greek capital letter mu',
				'&Nu;' => 'Greek capital letter nu',
				'&Xi;' => 'Greek capital letter xi',
				'&Omicron;' => 'Greek capital letter omicron',
				'&Pi;' => 'Greek capital letter pi',
				'&Rho;' => 'Greek capital letter rho',
				'&Sigma;' => 'Greek capital letter sigma',
				'&Tau;' => 'Greek capital letter tau',
				'&Upsilon;' => 'Greek capital letter upsilon',
				'&Phi;' => 'Greek capital letter phi',
				'&Chi;' => 'Greek capital letter chi',
				'&Psi;' => 'Greek capital letter psi',
				'&Omega;' => 'Greek capital letter omega',
				'&alpha;' => 'Greek small letter alpha',
				'&beta;' => 'Greek small letter beta',
				'&gamma;' => 'Greek small letter gamma',
				'&delta;' => 'Greek small letter delta',
				'&epsilon;' => 'Greek small letter epsilon',
				'&zeta;' => 'Greek small letter zeta',
				'&eta;' => 'Greek small letter eta',
				'&theta;' => 'Greek small letter theta',
				'&iota;' => 'Greek small letter iota',
				'&kappa;' => 'Greek small letter kappa',
				'&lambda;' => 'Greek small letter lambda',
				'&mu;' => 'Greek small letter mu',
				'&nu;' => 'Greek small letter nu',
				'&xi;' => 'Greek small letter xi',
				'&omicron;' => 'Greek small letter omicron',
				'&pi;' => 'Greek small letter pi',
				'&rho;' => 'Greek small letter rho',
				'&sigmaf;' => 'Greek small letter final sigma',
				'&sigma;' => 'Greek small letter sigma',
				'&tau;' => 'Greek small letter tau',
				'&upsilon;' => 'Greek small letter upsilon',
				'&phi;' => 'Greek small letter phi',
				'&chi;' => 'Greek small letter chi',
				'&psi;' => 'Greek small letter psi',
				'&omega;' => 'Greek small letter omega'
			)
		);
		if ( $category )
			$codes = $codes[$category];
		return apply_filters( 'c2c_html_special_characters', $codes );
	}

	/**
	 * Adds the meta box
	 *
	 * Need this function instead of having the action directly call show_html_special_characters_content() because
	 * the action sends over multiple arguments that we don't want.  Since show_html_special_characters() also calls
	 * show_html_special_characters_content() we can't just have it ignore arguments
	 */
	public static function add_meta_box() {
		self::show_html_special_characters_content();
	}

	/**
	 * Outputs the HTML special characters listing
	 *
	 * @param bool $echo (optional) Echo the output?  Default is true.
	 * @return string The listing
	 */
	protected static function show_html_special_characters_content( $echo = true ) {
		$codes = self::html_special_characters();
		$innards = '';
		$moreinnards = "<dl id='morehtmlspecialcharacters'>";
		$i = 0;
		foreach ( array_keys( $codes ) as $cat ) {
			if ( 'common' != $cat )
				$moreinnards .= "<dt>$cat:</dt><dd>";
			foreach ( $codes[$cat] as $code => $description ) {
					$ecode = htmlspecialchars( $code );
					$item = "<acronym onclick=\"send_to_editor('$ecode');\" title='$ecode $description'> $code</acronym>";
					if ( 'common' == $cat ) $innards .= $item;
					else $moreinnards .= $item;
			}
			if ( 'common' != $cat )
				$moreinnards .= '</dd>';
		}
		$moreinnards .= '</dl>';
		$innards = <<<HTML
		<div class="htmlspecialcharacter">
		<span id='commoncodes'>$innards</span>
		<a href='#' class="htmlspecialcharacter_helplink" title="Click to toggle display of help">Help?</a>
		<a href='#' class="htmlspecialcharacter_morelink" title="Click to toggle the display of more special characters">
			See <span id="htmlhelper_more">more</span><span id="htmlhelper_less">less</span>
		</a>
		$moreinnards
		<p id="htmlhelperhelp">Click to insert character into post.  Mouse-over character for more info. Some characters may not display in older browsers.</p>
		</div>

HTML;
		if ( $echo )
			echo $innards;
		return $innards;
	}

	/**
	 * Outputs a wrapper around the HTML special characters listing
	 *
	 * @return void (Text is echoed)
	 */
	public static function show_html_special_characters() {
		$innards = self::show_html_special_characters_content( false );
		$title = self::$title;
		echo <<<HTML
		<fieldset id="htmlspecialcharacterhelper" class="dbx-box">
			<h3 class="dbx-handle">{$title}</h3>
			<div class="dbx-content">
				$innards
			</div>
		</fieldset>

HTML;
	}

	/**
	 * Outputs the CSS needed by the HTML special characters admin widget
	 *
	 * @return void (Text is echoed)
	 */
	public static function insert_admin_css() {
		echo <<<HTML
		<style type="text/css">
			.htmlspecialcharacter acronym { margin:0.1em 0.5em 0.1em 0; cursor:pointer; }
			.htmlspecialcharacter a, #morehtmlspecialcharacters dt { font-size:xx-small; }
			#morehtmlspecialcharacters dd { margin-left:6px; }
			#htmlhelperhelp { font-size:x-small; display:none; }
			#morehtmlspecialcharacters, #htmlhelper_less { display:none; }
		</style>

HTML;
	}

	/**
	 * Outputs the JS needed by the HTML special characters admin widget
	 *
	 * @return void (Text is echoed)
	 */
	public static function insert_admin_js() {
		echo <<<JS
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('.htmlspecialcharacter_helplink').click(function() {
				$('#htmlhelperhelp').toggle(); return false;
			});
			$('.htmlspecialcharacter_morelink').click(function() {
				$('#commoncodes, #morehtmlspecialcharacters, #htmlhelper_more, #htmlhelper_less').toggle(); return false;
			});
		});
		</script>

JS;
	}

} // end c2c_HTMLSpecialCharactersHelper

c2c_HTMLSpecialCharactersHelper::init();

endif; // end if !class_exists()

?>