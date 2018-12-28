<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.simondouglas.com
 * @since             1.0.0
 * @package           Filterable_Article
 *
 * @wordpress-plugin
 * Plugin Name:       Filterable Article
 * Plugin URI:        filterable-article
 * Description:       This create an article filter based on shortcodes
 * Version:           1.0.0
 * Author:            Simon Douglas
 * Author URI:        www.simondouglas.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       filterable-article
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'FILTERABLE_ARTICLE_PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-filterable-article-activator.php
 */
function activate_filterable_article() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-filterable-article-activator.php';
	Filterable_Article_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-filterable-article-deactivator.php
 */
function deactivate_filterable_article() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-filterable-article-deactivator.php';
	Filterable_Article_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_filterable_article' );
register_deactivation_hook( __FILE__, 'deactivate_filterable_article' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-filterable-article.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_filterable_article() {

	$plugin = new Filterable_Article();
	$plugin->run();

}
run_filterable_article();
