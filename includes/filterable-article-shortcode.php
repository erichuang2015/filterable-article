<?php

class Filterable_Article_Shortcode {
	private $name;
	private $version;
	private $id;

	public $filter_model = [];
	public $item_model = [];
	public $num_shortcodes = 0;
	public $templates = [];

	static public $counter = - 1;

	public function __construct( $name, $version ) {
		$this->name    = $name;
		$this->version = $version;
		$this->id      = 'filterable-article-' . + time();
		$this->set_templates();
		add_shortcode( 'fa', [ $this, 'filterable_shortcode' ] );
	}

	/**
	 * filterable_shortcode
	 *
	 * @param $atts : Array
	 * @param null $content
	 *
	 * @return string
	 */
	public function filterable_shortcode( $atts, $content = null ) {
		$counter = ++ self::$counter;
		extract( shortcode_atts( array(
			'cats'   => null,
			'parent' => null,
		), $atts ) );

		$this->add_to( 'parent', array_map( function ( $item ) {
			return trim( $item );
		}, explode( ',', $parent ) ) );

		$this->add_to( 'cats', array_map( function ( $item ) {
			return trim( $item );
		}, explode( ',', $cats ) ) );

		return $this->build_template( $counter, $content );
	}

	public function build_template( $counter, $content ) {

		$template = $this->templates['item'];

		$template_model = [
			'content' => $content,
			'counter' => $counter,
			'id'      => $this->id,
		];

		$template = Filterable_Article_Public::supplant( $template, $template_model );

		if ( $counter == 0 ) {
			$this->num_shortcodes     = substr_count( get_the_content(), '[/fa]' );
			$template                 = $this->templates['article'] . $template;
			$template_model['before'] = Filterable_Article_Public::supplant( '<div id="%id%">', $template_model );
		}
		if ( $counter == ( $this->num_shortcodes - 1 ) ) {
			$template .= '</div>' . $this->templates['script'];

			$template_model['item_model']   = json_encode( $this->item_model );
			$template_model['filter_model'] = json_encode( $this->filter_model );
		}

		return Filterable_Article_Public::supplant( $template, $template_model );
	}

	/**
	 * add_to
	 *
	 * @param $category : String
	 * @param $values :Array
	 */
	protected function add_to( $category, $values ) {
		$values = array_filter( $values, function ( $value ) {
			$value = rtrim( $value );

			return $value ? $value : null;
		} );

		$this->item_model[ self::$counter ][ $category ] = $values;

		if ( ! array_key_exists( $category, $this->filter_model ) ) {
			$this->filter_model[ $category ] = [];
		}

		foreach ( $values as $value ) {

			if ( $value && ! in_array( $value, $this->filter_model[ $category ] ) ) {
				$this->filter_model[ $category ][] = trim( $value );
			}
		}
	}

	protected function set_templates() {
		$templates = apply_filters( 'filterable_article_set_templates', [ 'article', 'item', 'script' ] );
		foreach ( $templates as $name ) {
			$this->templates[ $name ] = Filterable_Article_Public::get_template( $name );
		}
	}

}

