<?php
/**
 * @package HTML_Special_Characters_Helper
 * @author Scott Reilly
 * @version 1.9.2
 */
/*
Plugin Name: HTML Special Characters Helper
Version: 1.9.2
Plugin URI: http://coffee2code.com/wp-plugins/html-special-characters-helper/
Author: Scott Reilly
Author URI: http://coffee2code.com/
Text Domain: html-special-characters-helper
Domain Path: /lang/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Description: Admin widget on the Write Post page for inserting HTML encodings of special characters into the post.

Compatible with WordPress 2.8 through 3.8+.

=>> Read the accompanying readme.txt file for instructions and documentation.
=>> Also, visit the plugin's homepage for additional information and updates.
=>> Or visit: http://wordpress.org/plugins/html-special-characters-helper/

TODO:
	* Front-end widget to facilitate use in comments
	* Make it possible to attach HTML character insertion into any input field
*/

/*
	Copyright (c) 2007-2014 by Scott Reilly (aka coffee2code)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) or die();

if ( is_admin() && ! class_exists( 'c2c_HTMLSpecialCharactersHelper' ) ) :

class c2c_HTMLSpecialCharactersHelper {
	public static $title = '';

	/**
	 * Returns version of the plugin.
	 *
	 * @since 1.9
	 */
	public static function version() {
		return '1.9.2';
	}

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
		load_plugin_textdomain( 'c2c_hsch', false, basename( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'lang' );
		self::$title = __( 'HTML Special Characters', 'c2c_hsch' );
		add_action( 'load-page.php',     array( __CLASS__, 'enqueue_scripts_and_styles' ) );
		add_action( 'load-page-new.php', array( __CLASS__, 'enqueue_scripts_and_styles' ) );
		add_action( 'load-post.php',     array( __CLASS__, 'enqueue_scripts_and_styles' ) );
		add_action( 'load-post-new.php', array( __CLASS__, 'enqueue_scripts_and_styles' ) );

		$post_types = apply_filters( 'c2c_html_special_characters_helper_post_types', array( 'page', 'post' ) );
		foreach ( $post_types as $post_type )
			add_meta_box( 'htmlspecialchars', self::$title, array( __CLASS__, 'add_meta_box' ), $post_type, 'side' );
	}

	/**
	 * Enqueues scripts and styles.
	 *
	 * @since 1.9
	 */
	public static function enqueue_scripts_and_styles() {
		// Enqueues JS for admin page
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_js' ) );
		// Register and enqueue styles for admin page
		self::register_styles();
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_css' ) );
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
				'&copy;'   => __( 'copyright sign', 'c2c_hsch' ),
				'&reg;'    => __( 'registered trade mark sign', 'c2c_hsch' ),
				'&#8482;'  => __( 'trade mark sign', 'c2c_hsch' ),
				'&laquo;'  => __( 'left double angle quotes', 'c2c_hsch' ),
				'&raquo;'  => __( 'right double angle quotes', 'c2c_hsch' ),
				'&cent;'   => __( 'cent sign', 'c2c_hsch' ),
				'&pound;'  => __( 'pound sign', 'c2c_hsch' ),
				'&euro;'   => __( 'euro sign', 'c2c_hsch' ),
				'&yen;'    => __( 'yen sign', 'c2c_hsch' ),
				'&sup1;'   => __( 'superscript one', 'c2c_hsch' ),
				'&sup2;'   => __( 'superscript two - squared', 'c2c_hsch' ),
				'&sup3;'   => __( 'superscript three - cubed', 'c2c_hsch' ),
				'&deg;'    => __( 'degree sign', 'c2c_hsch' ),
				'&frac14;' => __( 'fraction one quarter (1/4)', 'c2c_hsch' ),
				'&frac12;' => __( 'fraction one half (1/2)', 'c2c_hsch' ),
				'&frac34;' => __( 'fraction three quarters (3/4)', 'c2c_hsch' ),
				'&iquest;' => __( 'inverted question mark', 'c2c_hsch' ),
				'&iexcl;'  => __( 'inverted exclamation mark', 'c2c_hsch' ),
				'&quot;'   => __( 'double quotes', 'c2c_hsch' ),
				'&amp;'    => __( 'ampersand', 'c2c_hsch' ),
				'&lt;'     => __( 'less than sign', 'c2c_hsch' ),
				'&gt;'     => __( 'greater than sign', 'c2c_hsch' ),
				'&apos;'   => __( 'apostrophe', 'c2c_hsch' ),
				'&sect;'   => __( 'subsection sign', 'c2c_hsch' ),
				'&micro;'  => __( 'micro sign', 'c2c_hsch' ),
				'&times;'  => __( 'multiplication sign', 'c2c_hsch' ),
				'&divide;' => __( 'division sign', 'c2c_hsch' ),
				'&plusmn;' => __( 'plus/minus symbol', 'c2c_hsch' ),
				'&middot;' => __( 'middle dot', 'c2c_hsch' ),
				'&para;'   => __( 'paragraph symbol', 'c2c_hsch' ),
				'&#8211;'  => __( 'en dash', 'c2c_hsch' ),
				'&#8212;'  => __( 'em dash', 'c2c_hsch' ),
				'&#8230;'  => __( 'horizontal ellipsis', 'c2c_hsch' ),
				'&bull;'   => __( 'bullet', 'c2c_hsch' ),
				'&dagger;' => __( 'dagger', 'c2c_hsch' ),
				'&Dagger;' => __( 'double dagger', 'c2c_hsch' ),
				'&larr;'   => __( 'left arrow', 'c2c_hsch' ),
				'&uarr;'   => __( 'up arrow', 'c2c_hsch' ),
				'&rarr;'   => __( 'right arrow', 'c2c_hsch' ),
				'&darr;'   => __( 'down arrow', 'c2c_hsch' )
			),
			'punctuation' => array(
				'&amp;'	   => __( 'ampersand', 'c2c_hsch' ),
				'&apos;'   => __( 'apostrophe', 'c2c_hsch' ),
				'&quot;'   => __( 'double quotes', 'c2c_hsch' ),
				'&laquo;'  => __( 'left double angle quotes', 'c2c_hsch' ),
				'&raquo;'  => __( 'right double angle quotes', 'c2c_hsch' ),
				'&ldquo;'  => __( 'opening double quotes', 'c2c_hsch' ),
				'&rdquo;'  => __( 'closing double quotes', 'c2c_hsch' ),
				'&lsquo;'  => __( 'opening Single quote mark', 'c2c_hsch' ),
				'&rsquo;'  => __( 'closing single quote mark', 'c2c_hsch' ),
				'&reg;'    => __( 'registered symbol', 'c2c_hsch' ),
				'&copy;'   => __( 'copyright symbol', 'c2c_hsch' ),
				'&#8482;'  => __( 'trademark symbol', 'c2c_hsch' ),
				'&para;'   => __( 'paragraph symbol', 'c2c_hsch' ),
				'&szlig;'  => __( 'sharp s / ess-zed', 'c2c_hsch' ),
				'&bull;'   => __( 'bullet/big dot', 'c2c_hsch' ),
				'&middot;' => __( 'middle dot', 'c2c_hsch' ),
				'&sect;'   => __( 'subsection symbol', 'c2c_hsch' ),
				'&#8211;'  => __( 'en dash', 'c2c_hsch' ),
				'&#8212;'  => __( 'em dash', 'c2c_hsch' ),
				'&#8230;'  => __( 'horizontal ellipsis', 'c2c_hsch' ),
				'&iquest;' => __( 'inverted question mark', 'c2c_hsch' ),
				'&iexcl;'  => __( 'inverted exclamation mark', 'c2c_hsch' )
			),
			'currency' => array(
				'&cent;'   => __( 'cent sign', 'c2c_hsch' ),
				'&pound;'  => __( 'British Pound', 'c2c_hsch' ),
				'&yen;'    => __( 'Japanese Yen', 'c2c_hsch' ),
				'&euro;'   => __( 'Euro symbol', 'c2c_hsch' ),
				'&fnof;'   => __( 'Dutch Florin symbol', 'c2c_hsch' ),
				'&curren;' => __( 'generic currency symbol', 'c2c_hsch' )
			),
			'math' => array(
				'&fnof;'   => __( 'function', 'c2c_hsch' ),
				'&gt;'     => __( 'greater than', 'c2c_hsch' ),
				'&lt;'     => __( 'less than', 'c2c_hsch' ),
				'&ge;'     => __( 'greater than or equal to', 'c2c_hsch' ),
				'&le;'     => __( 'less than or equal to', 'c2c_hsch' ),
				'&ne;'     => __( 'not equal to', 'c2c_hsch' ),
				'&asymp;'  => __( 'approximately', 'c2c_hsch' ),
				'&equiv;'  => __( 'identical to', 'c2c_hsch' ),
				'&minus;'  => __( 'minus sign', 'c2c_hsch' ),
				'&divide;' => __( 'division sign', 'c2c_hsch' ),
				'&times;'  => __( 'multiplication sign', 'c2c_hsch' ),
				'&deg;'    => __( 'degree symbol', 'c2c_hsch' ),
				'&not;'    => __( 'not symbol', 'c2c_hsch' ),
				'&plusmn;' => __( 'plus/minus symbol', 'c2c_hsch' ),
				'&micro;'  => __( 'Micro', 'c2c_hsch' ),
				'&forall;' => __( 'for all', 'c2c_hsch' ),
				'&exist;'  => __( 'there exists', 'c2c_hsch' ),
				'&there4;' => __( 'therefore triangle', 'c2c_hsch' ),
				'&radic;'  => __( 'square root radical', 'c2c_hsch' ),
				'&infin;'  => __( 'infinity', 'c2c_hsch' ),
				'&int;'    => __( 'integral sign', 'c2c_hsch' ),
				'&part;'   => __( 'partial differential', 'c2c_hsch' ),
				'&sdot;'   => __( 'dot operator', 'c2c_hsch' ),
				'&prime;'  => __( 'single prime', 'c2c_hsch' ),
				'&Prime;'  => __( 'double prime', 'c2c_hsch' ),
				'&sum;'    => __( 'n-ary summation', 'c2c_hsch' ),
				'&prod;'   => __( 'n-ary product', 'c2c_hsch' ),
				'&permil;' => __( 'per mil (1/1000th)', 'c2c_hsch' ),
				'&perp;'   => __( 'orthogonal to / perpendicular', 'c2c_hsch' ),
				'&ang;'    => __( 'angle', 'c2c_hsch' ),
				'&and;'    => __( 'logical and', 'c2c_hsch' ),
				'&or;'     => __( 'logical or', 'c2c_hsch' ),
				'&cap;'    => __( 'intersection', 'c2c_hsch' ),
				'&cup;'    => __( 'union', 'c2c_hsch' ),
				'&empty;'  => __( 'empty set', 'c2c_hsch' ),
				'&nabla;'  => __( 'nabla, backward difference', 'c2c_hsch' ),
				'&frasl;'  => __( 'fraction slash', 'c2c_hsch' ),
				'&sup1;'   => __( 'superscript one', 'c2c_hsch' ),
				'&sup2;'   => __( 'superscript two - squared', 'c2c_hsch' ),
				'&sup3;'   => __( 'superscript three - cubed', 'c2c_hsch' ),
				'&frac14;' => __( 'fraction one quarter (1/4)', 'c2c_hsch' ),
				'&frac12;' => __( 'fraction one half (1/2)', 'c2c_hsch' ),
				'&frac34;' => __( 'fraction three quarters (3/4)', 'c2c_hsch' ),
				'&ordf;'   => __( 'feminine ordinal indicator', 'c2c_hsch' ),
				'&ordm;'   => __( 'masculine ordinal indicator', 'c2c_hsch' )
			),
			'symbols' => array(
				'&cedil;'  => __( 'cedilla', 'c2c_hsch' ),
				'&dagger;' => __( 'dagger', 'c2c_hsch' ),
				'&Dagger;' => __( 'double dagger', 'c2c_hsch' ),
				'&larr;'   => __( 'left arrow', 'c2c_hsch' ),
				'&uarr;'   => __( 'up arrow', 'c2c_hsch' ),
				'&rarr;'   => __( 'right arrow', 'c2c_hsch' ),
				'&darr;'   => __( 'down arrow', 'c2c_hsch' ),
				'&harr;'   => __( 'left-right arrow', 'c2c_hsch' ),
				'&crarr;'  => __( 'carriage return', 'c2c_hsch' ),
				'&lArr;'   => __( 'left double arrow', 'c2c_hsch' ),
				'&uArr;'   => __( 'up double arrow', 'c2c_hsch' ),
				'&rArr;'   => __( 'right double arrow', 'c2c_hsch' ),
				'&dArr;'   => __( 'down double arrow', 'c2c_hsch' ),
				'&hArr;'   => __( 'left-right double arrow', 'c2c_hsch' ),
				'&loz;'    => __( 'lozenge', 'c2c_hsch' ),
				'&clubs;'  => __( 'clubs', 'c2c_hsch' ),
				'&hearts;' => __( 'hearts', 'c2c_hsch' ),
				'&diams;'  => __( 'diamonds', 'c2c_hsch' ),
				'&spades;' => __( 'spades', 'c2c_hsch' )
			),
			'greek' => array(
				'&Alpha;'   => __( 'Greek capital letter alpha', 'c2c_hsch' ),
				'&Beta;'    => __( 'Greek capital letter beta', 'c2c_hsch' ),
				'&Gamma;'   => __( 'Greek capital letter gamma', 'c2c_hsch' ),
				'&Delta;'   => __( 'Greek capital letter delta', 'c2c_hsch' ),
				'&Epsilon;' => __( 'Greek capital letter epsilon', 'c2c_hsch' ),
				'&Zeta;'    => __( 'Greek capital letter zeta', 'c2c_hsch' ),
				'&Eta;'     => __( 'Greek capital letter eta', 'c2c_hsch' ),
				'&Theta;'   => __( 'Greek capital letter theta', 'c2c_hsch' ),
				'&Iota;'    => __( 'Greek capital letter iota', 'c2c_hsch' ),
				'&Kappa;'   => __( 'Greek capital letter kappa', 'c2c_hsch' ),
				'&Lambda;'  => __( 'Greek capital letter lambda', 'c2c_hsch' ),
				'&Mu;'      => __( 'Greek capital letter mu', 'c2c_hsch' ),
				'&Nu;'      => __( 'Greek capital letter nu', 'c2c_hsch' ),
				'&Xi;'      => __( 'Greek capital letter xi', 'c2c_hsch' ),
				'&Omicron;' => __( 'Greek capital letter omicron', 'c2c_hsch' ),
				'&Pi;'      => __( 'Greek capital letter pi', 'c2c_hsch' ),
				'&Rho;'     => __( 'Greek capital letter rho', 'c2c_hsch' ),
				'&Sigma;'   => __( 'Greek capital letter sigma', 'c2c_hsch' ),
				'&Tau;'     => __( 'Greek capital letter tau', 'c2c_hsch' ),
				'&Upsilon;' => __( 'Greek capital letter upsilon', 'c2c_hsch' ),
				'&Phi;'     => __( 'Greek capital letter phi', 'c2c_hsch' ),
				'&Chi;'     => __( 'Greek capital letter chi', 'c2c_hsch' ),
				'&Psi;'     => __( 'Greek capital letter psi', 'c2c_hsch' ),
				'&Omega;'   => __( 'Greek capital letter omega', 'c2c_hsch' ),
				'&alpha;'   => __( 'Greek small letter alpha', 'c2c_hsch' ),
				'&beta;'    => __( 'Greek small letter beta', 'c2c_hsch' ),
				'&gamma;'   => __( 'Greek small letter gamma', 'c2c_hsch' ),
				'&delta;'   => __( 'Greek small letter delta', 'c2c_hsch' ),
				'&epsilon;' => __( 'Greek small letter epsilon', 'c2c_hsch' ),
				'&zeta;'    => __( 'Greek small letter zeta', 'c2c_hsch' ),
				'&eta;'     => __( 'Greek small letter eta', 'c2c_hsch' ),
				'&theta;'   => __( 'Greek small letter theta', 'c2c_hsch' ),
				'&iota;'    => __( 'Greek small letter iota', 'c2c_hsch' ),
				'&kappa;'   => __( 'Greek small letter kappa', 'c2c_hsch' ),
				'&lambda;'  => __( 'Greek small letter lambda', 'c2c_hsch' ),
				'&mu;'      => __( 'Greek small letter mu', 'c2c_hsch' ),
				'&nu;'      => __( 'Greek small letter nu', 'c2c_hsch' ),
				'&xi;'      => __( 'Greek small letter xi', 'c2c_hsch' ),
				'&omicron;' => __( 'Greek small letter omicron', 'c2c_hsch' ),
				'&pi;'      => __( 'Greek small letter pi', 'c2c_hsch' ),
				'&rho;'     => __( 'Greek small letter rho', 'c2c_hsch' ),
				'&sigmaf;'  => __( 'Greek small letter final sigma', 'c2c_hsch' ),
				'&sigma;'   => __( 'Greek small letter sigma', 'c2c_hsch' ),
				'&tau;'     => __( 'Greek small letter tau', 'c2c_hsch' ),
				'&upsilon;' => __( 'Greek small letter upsilon', 'c2c_hsch' ),
				'&phi;'     => __( 'Greek small letter phi', 'c2c_hsch' ),
				'&chi;'     => __( 'Greek small letter chi', 'c2c_hsch' ),
				'&psi;'     => __( 'Greek small letter psi', 'c2c_hsch' ),
				'&omega;'   => __( 'Greek small letter omega', 'c2c_hsch' )
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
		$moreinnards = '<dl id="morehtmlspecialcharacters">';
		$i = 0;
		foreach ( array_keys( $codes ) as $cat ) {
			if ( 'common' != $cat )
				$moreinnards .= "<dt>$cat:</dt><dd>";
			foreach ( $codes[$cat] as $code => $description ) {
					$ecode = str_replace( '&', '&amp;', esc_attr( $code ) );
					$description = esc_attr( $description );
					$item = "<acronym onclick=\"send_to_editor('$ecode');\" title='$ecode $description'>$code</acronym> ";
					if ( 'common' == $cat )
						$innards .= $item;
					else
						$moreinnards .= $item;
			}
			if ( 'common' != $cat )
				$moreinnards .= '</dd>';
		}
		$moreinnards .= '</dl>';
		$innards = '<div class="htmlspecialcharacter"><span id="commoncodes">' . $innards . '</span>';
		$innards .= '<a href="#" class="htmlspecialcharacter_helplink" title="' .
			esc_attr( __( 'Click to toggle display of help', 'c2c_hsch' ) ) . '">' . __( 'Help?', 'c2c_hsch' ) . '</a>';
		$innards .= ' ';
		$innards .= '<a href="#" class="htmlspecialcharacter_morelink" title="' .
			esc_attr( __( 'Click to toggle the display of more special characters', 'c2c_hsch' ) ) . '">' .
			__( 'See <span id="htmlhelper_more">more</span><span id="htmlhelper_less">less</span>', 'c2c_hsch' ) . '</a>';
		$innards .= $moreinnards;
		$innards .= '<p id="htmlhelperhelp">' . __( 'Click to insert character into post. Mouse-over character for more info. Some characters may not display in older browsers.', 'c2c_hsch' ) . '</p></div>';

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
	 * Registers styles.
	 *
	 * @since 1.8
	 */
	public static function register_styles() {
		wp_register_style( __CLASS__ . '_admin', plugins_url( 'admin.css', __FILE__ ) );
	}

	/**
	 * Enqueues stylesheets.
	 *
	 * @since 1.8
	 */
	public static function enqueue_admin_css() {
		wp_enqueue_style( __CLASS__ . '_admin' );
	}

	/**
	 * Enqueues JS.
	 *
	 * @since 1.8
	 */
	public static function enqueue_admin_js() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( __CLASS__ . '_admin', plugins_url( 'admin.js', __FILE__ ), array( 'jquery' ), self::version(), true );
	}

} // end c2c_HTMLSpecialCharactersHelper

c2c_HTMLSpecialCharactersHelper::init();

endif; // end if !class_exists()
