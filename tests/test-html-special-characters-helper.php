<?php

class HTML_Special_Characters_Helper_Test extends WP_UnitTestCase {

	function tearDown() {
		parent::tearDown();

		remove_filter( 'c2c_html_special_characters', array( $this, 'more_html_special_characters' ) );
	}


	/**
	 *
	 * DATA PROVIDERS
	 *
	 */


	/**
	 *
	 * HELPER FUNCTIONS
	 *
	 */


	function get_currencies() {
		return array(
			'name'     => __( 'Currency', 'c2c_hsch' ),
			'&cent;'   => __( 'cent sign', 'c2c_hsch' ),
			'&pound;'  => __( 'British Pound', 'c2c_hsch' ),
			'&yen;'    => __( 'Japanese Yen', 'c2c_hsch' ),
			'&euro;'   => __( 'Euro symbol', 'c2c_hsch' ),
			'&fnof;'   => __( 'Dutch Florin symbol', 'c2c_hsch' ),
			'&curren;' => __( 'generic currency symbol', 'c2c_hsch' )
		);
	}

	function get_accented_a() {
		return array(
			'name'     => 'Accented A',
			'&Agrave;' => 'A grave accent',
			'&Aacute;' => 'A accute accent',
			'&Acirc;'  => 'A circumflex',
			'&Atilde;' => 'A tilde',
			'&Auml;'   => 'A umlaut',
			'&Aring;'  => 'A ring',
			'&AElig;'  => 'AE ligature',
		);
	}

	// Add a new grouping of characters (accented 'A's).
	function more_html_special_characters( $characters ) {
		$characters['accented_a'] = $this->get_accented_a();
		return $characters; // Important!
	}


	/**
	 *
	 * TESTS
	 *
	 */


	function test_class_name() {
		$this->assertTrue( class_exists( 'c2c_HTMLSpecialCharactersHelper' ) );
	}

	function test_version() {
		$this->assertEquals( '2.0', c2c_HTMLSpecialCharactersHelper::version() );
	}

	function test_get_default_html_special_characters_returns_all_categories_by_default() {
		$data = c2c_HTMLSpecialCharactersHelper::get_default_html_special_characters();

		$this->assertEquals(
			array( 'common', 'punctuation', 'currency', 'math', 'symbols', 'greek' ),
			array_keys( $data )
		);
	}

	function test_html_special_characters_returns_all_categories_by_default() {
		$data = c2c_HTMLSpecialCharactersHelper::html_special_characters();

		$this->assertEquals(
			array( 'common', 'punctuation', 'currency', 'math', 'symbols', 'greek' ),
			array_keys( $data )
		);
	}

	function test_html_special_characters_returns_special_characters_array() {
		$data = c2c_HTMLSpecialCharactersHelper::html_special_characters();

		$this->assertEquals( $this->get_currencies(), $data['currency'] );
	}

	function test_get_default_html_special_characters_returns_specified_category() {
		$this->assertEquals( $this->get_currencies(), c2c_HTMLSpecialCharactersHelper::get_default_html_special_characters( 'currency' ) );
	}

	function test_html_special_characters_returns_specified_category() {
		$this->assertEquals( $this->get_currencies(), c2c_HTMLSpecialCharactersHelper::html_special_characters( 'currency' ) );
	}

	function test_get_default_html_special_characters_with_unknown_category_returns_empty_array() {
		$this->assertEmpty( c2c_HTMLSpecialCharactersHelper::get_default_html_special_characters( 'unknown' ) );
	}

	function test_html_special_characters_with_unknown_category_returns_empty_array() {
		$this->assertEmpty( c2c_HTMLSpecialCharactersHelper::html_special_characters( 'unknown' ) );
	}

	function test_adding_new_character_category_via_c2c_html_special_characters_filter() {
		add_filter( 'c2c_html_special_characters', array( $this, 'more_html_special_characters' ) );

		$data = c2c_HTMLSpecialCharactersHelper::html_special_characters();

		$this->assertTrue( array_key_exists( 'accented_a', $data ) );
		$this->assertEquals( $this->get_accented_a(), $data['accented_a'] );
	}

	/*
	 * TEST TODO:
	 * - Meta box is registered for default post types (page, post)
	 * - Meta box is not registered for non-default post type
	 * - Meta box is registered for non-default post type via 'c2c_html_special_characters_helper_post_types' filter
	 * - JS is not enqueued on frontend
	 * - JS is enqueue on appropriate admin page(s)
	 * - JS is not enqueued on inappropriate admin page(s)
	 * - CSS is not enqueued on frontend
	 * - CSS is enqueue on appropriate admin page(s)
	 * - CSS is not enqueued on inappropriate admin page(s)
	 */
}
