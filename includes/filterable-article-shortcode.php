<?php

class Filterable_Article_Shortcode {
	private $name;
	private $version;

	public $item_model = [];
	public $filter_model = [];
	public $num_shortcodes = 0;

	static public $counter = - 1;

	public function __construct( $name, $version ) {
		$this->name    = $name;
		$this->version = $version;

		add_shortcode( 'fa', [ $this, 'filterable_shortcode' ], 10, 2 );
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
		$before  = '';
		$after   = '';
		$counter = ++ self::$counter;
		extract( shortcode_atts( array(
			'parent' => null,
			'cats'   => null,
		), $atts ) );

		$this->add_to( 'parent', array_map( function ( $item ) {
			return trim( $item );
		}, explode( ',', $parent ) ) );

		$this->add_to( 'cats', array_map( function ( $item ) {
			return trim( $item );
		}, explode( ',', $cats ) ) );
		if ( self::$counter === 0 ) {
			$this->num_shortcodes = substr_count( get_the_content(), '[/fa]' );
			$before               = '<div id="filterable-article"><filterable-filters v-bind:parent="filter.parent" v-bind:children="filter.cats"></filterable-filters>';
		}
		if ( self::$counter == ( $this->num_shortcodes - 1 ) ) {
			$after = '</div>';
			$after .= $this->initialize_script();
		}

		return "$before<filterable-item v-bind:model=\"items[{$counter}]\" inline-template><div>{$content}</div></filterable-item>$after";
	}

	/**
	 * add_to
	 *
	 * @param $category : String
	 * @param $values :Array
	 */
	private function add_to( $category, $values ) {
		$values = array_filter( $values, function ( $value ) {
			$value = rtrim( $value );

			return $value ? $value : null;
		} );

		$this->item_model[ self::$counter ][ $category ] = $values;

		if( !array_key_exists($category, $this->filter_model)){
			$this->filter_model[$category] = [];
		}

		foreach ( $values as $value ) {

			if ( $value && ! in_array( $value, $this->filter_model[ $category ] ) ) {
				$this->filter_model[ $category ][] = trim( $value );
			}
		}
	}

	/**
	 * initialize_script
	 * @return string
	 */
	private
	function initialize_script() {
		$item_model   = json_encode( $this->item_model );
		$filter_model = json_encode( $this->filter_model );
		$script       = <<<EOF
		<script>
		(function () {
		  window.addEventListener('load', function () {
		    new Vue({
		      el: document.getElementById('filterable-article'),
		      data: {
		        items: $item_model,
		        filter: $filter_model,
		      },
		      components: {
		        filterableFilters: window.filterableFilters, 
		        filterableItem: window.filterableItem 
		      }
		    })
		  })
		}())
	 </script>
EOF;
		return $script;
	}

}

