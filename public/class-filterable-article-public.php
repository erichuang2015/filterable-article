<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       www.simondouglas.com
 * @since      1.0.0
 *
 * @package    Filterable_Article
 * @subpackage Filterable_Article/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Filterable_Article
 * @subpackage Filterable_Article/public
 * @author     Simon Douglas <sidouglas.net@gmail.com>
 */
class Filterable_Article_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $post;

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Filterable_Article_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Filterable_Article_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'fa' ) ) {
			remove_filter( 'the_content', 'wpautop' );
			wp_enqueue_script( 'vuejs', 'https://cdn.jsdelivr.net/npm/vue@2.5.13/dist/vue.js' );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/filterable-article-public-min.js', [ 'vuejs' ], $this->version, false );
		}
	}

	/**
	 * supplant
	 * Replaces %tokens% inside a string with an array key/value
	 *
	 * @param $string :string,
	 * @param $array :array
	 *
	 * @return string
	 */
	static public function supplant( $string, $array ) {
		$merged = array_merge( array_fill_keys( array_keys( $array ), '' ), $array );
		$keys   = array_map( function ( $key ) {
			return '%' . $key . '%';
		}, array_keys( $merged ) );

		$return = str_replace( $keys, $merged, $string );

		return preg_replace( '/%.*?(%)/', '', $return );
	}

	static public function get_template( $name, $args = [] ) {
		global $post;

		$plugin_dir  = dirname( __DIR__ ) . '/templates/';
		$search_exts = [ '.php', '.js', '.html' ];
		$extension   = null;

		//First we check if there are overriding templates in the child or parent theme
		foreach ( $search_exts as $ext ) {
			$located = locate_template( [ 'plugins/filterable-article/' . $name . $ext ] );

			if ( ! $located ) {
				$located = file_exists( $plugin_dir . $name . $ext ) ? $plugin_dir . $name . $ext : null;
			}
			if ( $located ) {
				$extension = $ext;
				break;
			}
		}
		if ( $located ) {
			if ( $extension == '.php' ) {
				$args = apply_filters( 'filterable_article_get_template_args', $args, $post, $name );
				if ( is_array( $args ) ) {
					extract( $args );
				}
				ob_start();
				require_once( $located );
				$located = ob_get_contents();
				ob_end_clean();

				return apply_filters( 'filterable_article_get_template', $located, $post, $name );
			}

			return file_get_contents( $located );
		}

		return false;
	}
}
