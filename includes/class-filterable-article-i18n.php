<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       www.simondouglas.com
 * @since      1.0.0
 *
 * @package    Filterable_Article
 * @subpackage Filterable_Article/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Filterable_Article
 * @subpackage Filterable_Article/includes
 * @author     Simon Douglas <sidouglas.net@gmail.com>
 */
class Filterable_Article_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'filterable-article',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
