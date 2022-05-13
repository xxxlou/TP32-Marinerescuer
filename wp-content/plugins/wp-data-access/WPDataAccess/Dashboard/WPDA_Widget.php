<?php

// phpcs:ignore Standard.Category.SniffName.ErrorCode
namespace WPDataAccess\Dashboard;

use  WPDataAccess\WPDA ;
/**
 * Abstract widget base class
 */
abstract class WPDA_Widget
{
    /**
     * Nonce seed
     */
    const  WIDGET_ADD = 'WPDA_WIDGET_ADD' ;
    /**
     * Nonce seed
     */
    const  WIDGET_REFRESH = 'WPDA_WIDGET_REFRESH' ;
    /**
     * Widget sequence number
     *
     * @var int
     */
    protected static  $widget_sequence_nr = 0 ;
    /**
     * Active column number
     *
     * @var int|mixed
     */
    protected  $column = 1 ;
    /**
     * Share indicator
     *
     * @var bool
     */
    protected  $can_share = false ;
    /**
     * Layout indicator
     *
     * @var bool
     */
    protected  $has_layout = false ;
    /**
     * Settings indicator
     *
     * @var bool
     */
    protected  $has_setting = false ;
    /**
     * Refresh indicator
     *
     * @var bool
     */
    protected  $can_refresh = false ;
    /**
     * Widget name
     *
     * @var mixed|string
     */
    protected  $name = 'No name' ;
    /**
     * Widget title
     *
     * @var mixed|string
     */
    protected  $title = 'No title' ;
    /**
     * Widget content
     *
     * @var mixed|string
     */
    protected  $content = 'Loading...' ;
    /**
     * Nonce
     *
     * @var null
     */
    protected  $wp_nonce = null ;
    /**
     * Current widget id
     *
     * @var int|mixed
     */
    protected  $widget_id = 0 ;
    /**
     * Widget positioning
     *
     * @var string
     */
    protected  $position = 'append' ;
    /**
     * Current state
     *
     * @var mixed|string|null
     */
    protected  $state = null ;
    /**
     * Lock indicator
     *
     * @var bool
     */
    protected  $is_locked = false ;
    /**
     * Widget shares
     *
     * @var array
     */
    protected  $share = array(
        'post'  => 'true',
        'page'  => 'true',
        'embed' => 'block',
        'allow' => array(),
    ) ;
    /**
     * Constructor
     *
     * @param array $args Constructor arguments.
     */
    public function __construct( $args = array() )
    {
        wp_enqueue_script( 'jquery-ui-widget' );
        if ( isset( $args['name'] ) ) {
            $this->name = $args['name'];
        }
        if ( isset( $args['column'] ) ) {
            $this->column = $args['column'];
        }
        if ( isset( $args['title'] ) ) {
            $this->title = $args['title'];
        }
        if ( isset( $args['content'] ) ) {
            $this->content = $args['content'];
        }
        if ( isset( $args['position'] ) && 'prepend' === $args['position'] ) {
            $this->position = 'prepend';
        }
        
        if ( isset( $args['widget_id'] ) ) {
            $this->widget_id = $args['widget_id'];
            // Used to add widgets via ajax.
        } else {
            $this->widget_id = ++self::$widget_sequence_nr;
            // Used to add widgets on page load.
        }
        
        if ( isset( $args['is_locked'] ) ) {
            $this->is_locked = true === $args['is_locked'] || 'true' === $args['is_locked'];
        }
        if ( isset( $args['share'] ) && isset(
            $args['share']['roles'],
            $args['share']['users'],
            $args['share']['post'],
            $args['share']['page'],
            $args['share']['embed'],
            $args['share']['allow']
        ) ) {
            $this->share = array(
                'roles' => $args['share']['roles'],
                'users' => $args['share']['users'],
                'post'  => $args['share']['post'],
                'page'  => $args['share']['page'],
                'embed' => $args['share']['embed'],
                'allow' => $args['share']['allow'],
            );
        }
        $this->state = ( isset( $args['state'] ) ? $args['state'] : 'new' );
        $this->wp_nonce = wp_create_nonce( static::WIDGET_REFRESH . WPDA::get_current_user_login() );
    }
    
    /**
     * Construct widget container
     *
     * @return false|string
     */
    protected function container()
    {
        ob_start();
        ?>
			<script type="application/javascript" class="wpda-widget-<?php 
        echo  esc_attr( $this->widget_id ) ;
        ?>">
				jQuery(function() {
					var widget = `<?php 
        echo  $this->html() ;
        // phpcs:ignore WordPress.Security.EscapeOutput
        ?>`;

					jQuery("#wpda-dashboard-column-<?php 
        echo  esc_attr( $this->column ) ;
        ?>").<?php 
        echo  esc_attr( $this->position ) ;
        ?>(widget);
					jQuery("#wpda-widget-<?php 
        echo  esc_attr( $this->widget_id ) ;
        ?>").data("name", "<?php 
        echo  esc_attr( $this->name ) ;
        ?>" );

					jQuery("#wpda-widget-<?php 
        echo  esc_attr( $this->widget_id ) ;
        ?> .wpda-widget-close").on("click", function() {
						removePanelFromDashboard(jQuery(this).closest('.wpda-widget'));
					});
				});
			</script>
			<?php 
        $this->js();
        return ob_get_clean();
    }
    
    /**
     * Construct widget html
     *
     * @return string
     */
    protected function html()
    {
        $share = '';
        $layout = '';
        $setting = '';
        $refresh = ( $this->can_refresh ? "<i class='fas fa-sync-alt wpda-widget-refresh wpda_tooltip' title='Refresh'></i> &nbsp;" : '' );
        $close = ( !$this->is_locked ? '<i class="fas fa-window-close wpda-widget-close wpda_tooltip" title="Close"></i>' : '' );
        $widget = <<<EOF
                <div id="wpda-widget-{$this->widget_id}" data-id="{$this->widget_id}" class="wpda-widget ui-widget">
                    <div class="wpda-widget-content">
                        <div class="ui-widget-header">
                            <span>{$this->name}</span>
                            <span class="icons">
\t\t\t\t\t\t\t\t{$share}
\t\t\t\t\t\t\t\t{$layout}
\t\t\t\t\t\t\t\t{$setting}
\t\t\t\t\t\t\t\t{$refresh}
\t\t\t\t\t\t\t\t{$close}
\t\t\t\t\t\t\t</span>
                        </div>
                        <div class="ui-widget-content">
                            {$this->content}
                        </div>
                    </div>
                </div>
EOF;
        return $widget;
    }
    
    /**
     * Cross origin check
     *
     * @param WPDA_Widget $widget Widget.
     * @return bool
     */
    protected static function check_cors( $widget )
    {
        
        if ( isset( $_POST['wpda_caller'] ) && 'embedded' === $_POST['wpda_caller'] ) {
            // phpcs:ignore WordPress.Security.NonceVerification
            $share = ( isset( $widget['widgetShare'] ) ? $widget['widgetShare'] : null );
            
            if ( 'block' === $share['embed'] ) {
                WPDA::sent_header( 'application/json', '*' );
                echo  static::msg( 'ERROR', 'No access' ) ;
                // phpcs:ignore WordPress.Security.EscapeOutput
                wp_die();
            } else {
                
                if ( '*' === $share['embed'] ) {
                    WPDA::sent_header( 'application/json', '*' );
                    return true;
                } else {
                    // Access is already checked with sonce token.
                    WPDA::sent_header( 'application/json', '*' );
                    return true;
                }
            
            }
        
        }
        
        return false;
    }
    
    /**
     * Abstract method forcing each subclass to add its own specific javascript code
     *
     * @return mixed
     */
    protected abstract function js();
    
    // Method to add custom JavaScript code.
    /**
     * Add widget to dashboard
     *
     * @return void
     */
    public function add()
    {
        echo  $this->container() ;
        // phpcs:ignore WordPress.Security.EscapeOutput
        ?>
			<script type="application/javascript">
				jQuery(function() {
					increaseWidgetSequenceNr();
				});
			</script>
			<?php 
    }
    
    /**
     * Abstract widget method forcing each subclass to implement its own specific widget functionality
     *
     * @return mixed
     */
    public static abstract function widget();
    
    /**
     * Construct widget via ajax (general part used for each widget)
     *
     * @return void
     */
    public static function ajax_widget()
    {
        $wp_nonce = ( isset( $_POST['wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wp_nonce'] ) ) : '' );
        
        if ( !wp_verify_nonce( $wp_nonce, static::WIDGET_ADD . WPDA::get_current_user_login() ) ) {
            WPDA::sent_header( 'application/json' );
            echo  static::msg( 'ERROR', 'Token expired, please refresh page' ) ;
            // phpcs:ignore WordPress.Security.EscapeOutput
            wp_die();
        }
        
        static::widget();
    }
    
    /**
     * Abstract refresh method forcing each subclass to implement its own specific refresh functionality
     *
     * @return mixed
     */
    public static abstract function refresh();
    
    /**
     * Refresh widget via ajax (general part used for each widget)
     *
     * @return void
     */
    public static function ajax_refresh()
    {
        $wp_nonce = ( isset( $_POST['wp_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wp_nonce'] ) ) : '' );
        
        if ( !wp_verify_nonce( $wp_nonce, static::WIDGET_REFRESH . WPDA::get_current_user_login() ) ) {
            WPDA::sent_header( 'application/json' );
            echo  static::msg( 'ERROR', 'Token expired, please refresh page' ) ;
            // phpcs:ignore WordPress.Security.EscapeOutput
            wp_die();
        }
        
        static::refresh();
    }
    
    /**
     * Construct JSON response message
     *
     * @param string $status Response status.
     * @param string $msg Response message.
     * @return mixed
     */
    protected static function msg( $status, $msg )
    {
        $error = array(
            'status' => $status,
            'msg'    => $msg,
        );
        return wp_json_encode( $error );
    }

}