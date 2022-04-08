<?php
/**
 * Vilva Customizer Typography Control
 *
 * @package Vilva
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists( 'Vilva_Typography_Control' ) ) {
    
    class Vilva_Typography_Control extends WP_Customize_Control{
    
    	public $tooltip = '';
    	public $js_vars = array();
    	public $output = array();
    	public $option_type = 'theme_mod';
    	public $type = 'vilva-typography';
    
    	/**
    	 * Refresh the parameters passed to the JavaScript via JSON.
    	 *
    	 * @access public
    	 * @return void
    	 */
    	public function to_json() {
    		parent::to_json();
    
    		if ( isset( $this->default ) ) {
    			$this->json['default'] = $this->default;
    		} else {
    			$this->json['default'] = $this->setting->default;
    		}
    		$this->json['js_vars'] = $this->js_vars;
    		$this->json['output']  = $this->output;
    		$this->json['value']   = $this->value();
    		$this->json['choices'] = $this->choices;
    		$this->json['link']    = $this->get_link();
    		$this->json['tooltip'] = $this->tooltip;
    		$this->json['id']      = $this->id;
    		$this->json['l10n']    = apply_filters( 'vilva_il8n_strings', array(
    			'on'                 => esc_attr__( 'ON', 'vilva' ),
    			'off'                => esc_attr__( 'OFF', 'vilva' ),
    			'all'                => esc_attr__( 'All', 'vilva' ),
    			'cyrillic'           => esc_attr__( 'Cyrillic', 'vilva' ),
    			'cyrillic-ext'       => esc_attr__( 'Cyrillic Extended', 'vilva' ),
    			'devanagari'         => esc_attr__( 'Devanagari', 'vilva' ),
    			'greek'              => esc_attr__( 'Greek', 'vilva' ),
    			'greek-ext'          => esc_attr__( 'Greek Extended', 'vilva' ),
    			'khmer'              => esc_attr__( 'Khmer', 'vilva' ),
    			'latin'              => esc_attr__( 'Latin', 'vilva' ),
    			'latin-ext'          => esc_attr__( 'Latin Extended', 'vilva' ),
    			'vietnamese'         => esc_attr__( 'Vietnamese', 'vilva' ),
    			'hebrew'             => esc_attr__( 'Hebrew', 'vilva' ),
    			'arabic'             => esc_attr__( 'Arabic', 'vilva' ),
    			'bengali'            => esc_attr__( 'Bengali', 'vilva' ),
    			'gujarati'           => esc_attr__( 'Gujarati', 'vilva' ),
    			'tamil'              => esc_attr__( 'Tamil', 'vilva' ),
    			'telugu'             => esc_attr__( 'Telugu', 'vilva' ),
    			'thai'               => esc_attr__( 'Thai', 'vilva' ),
    			'serif'              => _x( 'Serif', 'font style', 'vilva' ),
    			'sans-serif'         => _x( 'Sans Serif', 'font style', 'vilva' ),
    			'monospace'          => _x( 'Monospace', 'font style', 'vilva' ),
    			'font-family'        => esc_attr__( 'Font Family', 'vilva' ),
    			'font-size'          => esc_attr__( 'Font Size', 'vilva' ),
    			'font-weight'        => esc_attr__( 'Font Weight', 'vilva' ),
    			'line-height'        => esc_attr__( 'Line Height', 'vilva' ),
    			'font-style'         => esc_attr__( 'Font Style', 'vilva' ),
    			'letter-spacing'     => esc_attr__( 'Letter Spacing', 'vilva' ),
    			'text-align'         => esc_attr__( 'Text Align', 'vilva' ),
    			'text-transform'     => esc_attr__( 'Text Transform', 'vilva' ),
    			'none'               => esc_attr__( 'None', 'vilva' ),
    			'uppercase'          => esc_attr__( 'Uppercase', 'vilva' ),
    			'lowercase'          => esc_attr__( 'Lowercase', 'vilva' ),
    			'top'                => esc_attr__( 'Top', 'vilva' ),
    			'bottom'             => esc_attr__( 'Bottom', 'vilva' ),
    			'left'               => esc_attr__( 'Left', 'vilva' ),
    			'right'              => esc_attr__( 'Right', 'vilva' ),
    			'center'             => esc_attr__( 'Center', 'vilva' ),
    			'justify'            => esc_attr__( 'Justify', 'vilva' ),
    			'color'              => esc_attr__( 'Color', 'vilva' ),
    			'select-font-family' => esc_attr__( 'Select a font-family', 'vilva' ),
    			'variant'            => esc_attr__( 'Variant', 'vilva' ),
    			'style'              => esc_attr__( 'Style', 'vilva' ),
    			'size'               => esc_attr__( 'Size', 'vilva' ),
    			'height'             => esc_attr__( 'Height', 'vilva' ),
    			'spacing'            => esc_attr__( 'Spacing', 'vilva' ),
    			'ultra-light'        => esc_attr__( 'Ultra-Light 100', 'vilva' ),
    			'ultra-light-italic' => esc_attr__( 'Ultra-Light 100 Italic', 'vilva' ),
    			'light'              => esc_attr__( 'Light 200', 'vilva' ),
    			'light-italic'       => esc_attr__( 'Light 200 Italic', 'vilva' ),
    			'book'               => esc_attr__( 'Book 300', 'vilva' ),
    			'book-italic'        => esc_attr__( 'Book 300 Italic', 'vilva' ),
    			'regular'            => esc_attr__( 'Normal 400', 'vilva' ),
    			'italic'             => esc_attr__( 'Normal 400 Italic', 'vilva' ),
    			'medium'             => esc_attr__( 'Medium 500', 'vilva' ),
    			'medium-italic'      => esc_attr__( 'Medium 500 Italic', 'vilva' ),
    			'semi-bold'          => esc_attr__( 'Semi-Bold 600', 'vilva' ),
    			'semi-bold-italic'   => esc_attr__( 'Semi-Bold 600 Italic', 'vilva' ),
    			'bold'               => esc_attr__( 'Bold 700', 'vilva' ),
    			'bold-italic'        => esc_attr__( 'Bold 700 Italic', 'vilva' ),
    			'extra-bold'         => esc_attr__( 'Extra-Bold 800', 'vilva' ),
    			'extra-bold-italic'  => esc_attr__( 'Extra-Bold 800 Italic', 'vilva' ),
    			'ultra-bold'         => esc_attr__( 'Ultra-Bold 900', 'vilva' ),
    			'ultra-bold-italic'  => esc_attr__( 'Ultra-Bold 900 Italic', 'vilva' ),
    			'invalid-value'      => esc_attr__( 'Invalid Value', 'vilva' ),
    		) );
    
    		$defaults = array( 'font-family'=> false );
    
    		$this->json['default'] = wp_parse_args( $this->json['default'], $defaults );
    	}
    
    	/**
    	 * Enqueue scripts and styles.
    	 *
    	 * @access public
    	 * @return void
    	 */
    	public function enqueue() {
    		wp_enqueue_style( 'vilva-typography', get_template_directory_uri() . '/inc/custom-controls/typography/typography.css', null );
            
            wp_enqueue_script( 'jquery-ui-core' );
    		wp_enqueue_script( 'jquery-ui-tooltip' );
    		wp_enqueue_script( 'jquery-stepper-min-js' );
    		wp_enqueue_script( 'vilva-selectize', get_template_directory_uri() . '/inc/js/selectize.js', array( 'jquery' ), false, true );
    		wp_enqueue_script( 'vilva-typography', get_template_directory_uri() . '/inc/custom-controls/typography/typography.js', array( 'jquery', 'vilva-selectize' ), false, true );
    
    		$google_fonts   = vilva_Fonts::get_google_fonts();
    		$standard_fonts = vilva_Fonts::get_standard_fonts();
    		$all_variants   = vilva_Fonts::get_all_variants();
    
    		$standard_fonts_final = array();
    		foreach ( $standard_fonts as $key => $value ) {
    			$standard_fonts_final[] = array(
    				'family'      => $value['stack'],
    				'label'       => $value['label'],
    				'is_standard' => true,
    				'variants'    => array(
    					array(
    						'id'    => 'regular',
    						'label' => $all_variants['regular'],
    					),
    					array(
    						'id'    => 'italic',
    						'label' => $all_variants['italic'],
    					),
    					array(
    						'id'    => '700',
    						'label' => $all_variants['700'],
    					),
    					array(
    						'id'    => '700italic',
    						'label' => $all_variants['700italic'],
    					),
    				),
    			);
    		}
    
    		$google_fonts_final = array();
    
    		if ( is_array( $google_fonts ) ) {
    			foreach ( $google_fonts as $family => $args ) {
    				$label    = ( isset( $args['label'] ) ) ? $args['label'] : $family;
    				$variants = ( isset( $args['variants'] ) ) ? $args['variants'] : array( 'regular', '700' );
    
    				$available_variants = array();
    				foreach ( $variants as $variant ) {
    					if ( array_key_exists( $variant, $all_variants ) ) {
    						$available_variants[] = array( 'id' => $variant, 'label' => $all_variants[ $variant ] );
    					}
    				}
    
    				$google_fonts_final[] = array(
    					'family'   => $family,
    					'label'    => $label,
    					'variants' => $available_variants
    				);
    			}
    		}
    
    		$final = array_merge( $standard_fonts_final, $google_fonts_final );
    		wp_localize_script( 'vilva-typography', 'all_fonts', $final );
    	}
    
    	/**
    	 * An Underscore (JS) template for this control's content (but not its container).
    	 *
    	 * Class variables for this control class are available in the `data` JS object;
    	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
    	 *
    	 * I put this in a separate file because PhpStorm didn't like it and it fucked with my formatting.
    	 *
    	 * @see    WP_Customize_Control::print_template()
    	 *
    	 * @access protected
    	 * @return void
    	 */
    	protected function content_template(){ ?>
    		<# if ( data.tooltip ) { #>
                <a href="#" class="tooltip hint--left" data-hint="{{ data.tooltip }}"><span class='dashicons dashicons-info'></span></a>
            <# } #>
            
            <label class="customizer-text">
                <# if ( data.label ) { #>
                    <span class="customize-control-title">{{{ data.label }}}</span>
                <# } #>
                <# if ( data.description ) { #>
                    <span class="description customize-control-description">{{{ data.description }}}</span>
                <# } #>
            </label>
            
            <div class="wrapper">
                <# if ( data.default['font-family'] ) { #>
                    <# if ( '' == data.value['font-family'] ) { data.value['font-family'] = data.default['font-family']; } #>
                    <# if ( data.choices['fonts'] ) { data.fonts = data.choices['fonts']; } #>
                    <div class="font-family">
                        <h5>{{ data.l10n['font-family'] }}</h5>
                        <select id="vilva-typography-font-family-{{{ data.id }}}" placeholder="{{ data.l10n['select-font-family'] }}"></select>
                    </div>
                    <div class="variant vilva-variant-wrapper">
                        <h5>{{ data.l10n['style'] }}</h5>
                        <select class="variant" id="vilva-typography-variant-{{{ data.id }}}"></select>
                    </div>
                <# } #>   
                
            </div>
            <?php
    	}    

        protected function render_content(){
        }
    }
}