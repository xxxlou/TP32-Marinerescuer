<?php

// phpcs:ignore Standard.Category.SniffName.ErrorCode
namespace WPDataAccess\Dashboard;

use  WPDataAccess\WPDA ;
/**
 * Implements code widget
 */
class WPDA_Widget_Code extends WPDA_Widget
{
    /**
     * Code id
     *
     * @var mixed
     */
    protected  $code_id ;
    /**
     * Constructor
     *
     * @param array $args Arguments.
     */
    public function __construct( $args = array() )
    {
        parent::__construct( $args );
        $this->can_share = true;
        
        if ( isset( $args['code_id'] ) ) {
            $this->code_id = $args['code_id'];
            
            if ( class_exists( 'Code_Manager\\Code_Manager' ) ) {
                $cm = new \Code_Manager\Code_Manager();
                $this->content = $cm->add_shortcode( array(
                    'id' => $this->code_id,
                ) );
            } else {
                $this->content = 'Code Manager not installed or not activated';
            }
        
        }
    
    }
    
    /**
     * Not implemented
     *
     * @param WPDA_Widget $widget Widget.
     * @return void
     */
    public function do_shortcode( $widget )
    {
        // Not implemented (use Code Manager short code).
    }
    
    /**
     * Embed widget
     *
     * @param WPDA_Widget $widget Widget.
     * @param string      $target_element HTML element is.
     * @return void
     */
    public function do_embed( $widget, $target_element )
    {
    }
    
    /**
     * Javascript section
     *
     * @param boolean $is_backend Back-end indicator.
     * @return void
     */
    protected function js( $is_backend = true )
    {
    }
    
    /**
     * Add widget via ajax
     *
     * @return void
     */
    public static function widget()
    {
        $panel_name = ( isset( $_REQUEST['wpda_panel_name'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wpda_panel_name'] ) ) : '' );
        // phpcs:ignore WordPress.Security.NonceVerification
        $panel_code_id = ( isset( $_REQUEST['wpda_panel_code_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wpda_panel_code_id'] ) ) : '' );
        // phpcs:ignore WordPress.Security.NonceVerification
        $panel_column = ( isset( $_REQUEST['wpda_panel_column'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wpda_panel_column'] ) ) : '1' );
        // phpcs:ignore WordPress.Security.NonceVerification
        $column_position = ( isset( $_REQUEST['wpda_column_position'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wpda_column_position'] ) ) : 'prepend' );
        // phpcs:ignore WordPress.Security.NonceVerification
        $widget_sequence_nr = ( isset( $_REQUEST['wpda_widget_sequence_nr'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wpda_widget_sequence_nr'] ) ) : '1' );
        // phpcs:ignore WordPress.Security.NonceVerification
        $wdg = new WPDA_Widget_Code( array(
            'name'      => $panel_name,
            'code_id'   => $panel_code_id,
            'column'    => $panel_column,
            'position'  => $column_position,
            'widget_id' => $widget_sequence_nr,
        ) );
        WPDA::sent_header( 'text/html; charset=UTF-8' );
        echo  $wdg->container() ;
        // phpcs:ignore WordPress.Security.EscapeOutput
        wp_die();
    }
    
    /**
     * Refresh code widget
     *
     * @return void
     */
    public static function refresh()
    {
        echo  static::msg( 'ERROR', 'Method not available for this panel type' ) ;
        // phpcs:ignore WordPress.Security.EscapeOutput
        wp_die();
    }

}