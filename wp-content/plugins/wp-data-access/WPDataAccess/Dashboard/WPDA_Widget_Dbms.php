<?php

// phpcs:ignore Standard.Category.SniffName.ErrorCode
namespace WPDataAccess\Dashboard;

use  WPDataAccess\WPDA ;
/**
 * DBMS widget implementation
 */
class WPDA_Widget_Dbms extends WPDA_Widget
{
    /**
     * DBMS widget id
     *
     * @var string
     */
    protected  $id ;
    /**
     * Database schema name
     *
     * @var mixed
     */
    protected  $schema_name ;
    /**
     * Constructor
     *
     * @param array $args Arguments.
     */
    public function __construct( $args = array() )
    {
        parent::__construct( $args );
        $this->can_share = true;
        $this->can_refresh = true;
        global  $wpdb ;
        
        if ( isset( $args['schema_name'] ) ) {
            $schema_name = $args['schema_name'];
            
            if ( 'wpdb' === $schema_name ) {
                $schema_name = $wpdb->dbname;
                $this->title = "WordPress database ({$schema_name})";
            } else {
                $this->title = "Remote database {$schema_name}";
            }
        
        } else {
            $schema_name = $wpdb->dbname;
            $this->title = "WordPress database ({$schema_name})";
        }
        
        $this->id = 'dbms' . $this->widget_id . str_replace( ':', '_', $schema_name );
        $this->schema_name = $schema_name;
        $info = static::get_data( $schema_name );
        $dbms_status = "\n\t\t\t\t<div id='{$this->id}' class='wpda-dbms-container'>\n\t\t\t\t\t<ul>\n\t\t\t\t\t\t<li><a href='#{$this->id}-1'>Instance</a></li>\n\t\t\t\t\t\t<li><a href='#{$this->id}-2'>Variables</a></li>\n\t\t\t\t\t\t<li><a href='#{$this->id}-3'>Status</a></li>\n\t\t\t\t\t</ul>\n\t\t\t\t\t<div id='{$this->id}-1'>\n\t\t\t\t\t\t<table class='wpda_dbms_table' class='ui-widget-content'>\n\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t<th>Host</th>\n\t\t\t\t\t\t\t\t<td class='hostname'>{$info['hostname']}</td>\n\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t<th>Port</th>\n\t\t\t\t\t\t\t\t<td class='post'>{$info['port']}</td>\n\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t<th>SSL</th>\n\t\t\t\t\t\t\t\t<td class='ssl'>{$info['ssl']}</td>\n\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t<th>Version</th>\n\t\t\t\t\t\t\t\t<td class='version'>{$info['version']} {$info['version_comment']}</td>\n\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t<th>Compiled For</th>\n\t\t\t\t\t\t\t\t<td class='compiled'>{$info['version_compile_os']} ({$info['version_compile_machine']})</td>\n\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t<th>Uptime</th>\n\t\t\t\t\t\t\t\t<td class='uptime'>{$info['uptime']}</td>\n\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t<th class='wpda-widget-dbms-separator' colspan='2' id='{$this->id}-dir' class='wpda-dbms-link'><i class='fas fa-caret-right'></i> Server Directories</th>\n\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t<tr class='dir' style='display: none'>\n\t\t\t\t\t\t\t\t<th>Base Directory</th>\n\t\t\t\t\t\t\t\t<td class='basedir'>{$info['basedir']}</td>\n\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t<tr class='dir' style='display: none'>\n\t\t\t\t\t\t\t\t<th>Data Directory</th>\n\t\t\t\t\t\t\t\t<td class='datadir'>{$info['datadir']}</td>\n\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t<tr class='dir' style='display: none'>\n\t\t\t\t\t\t\t\t<th>Plugins Directory</th>\n\t\t\t\t\t\t\t\t<td class='plugin_dir'>{$info['plugin_dir']}</td>\n\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t<tr class='dir' style='display: none'>\n\t\t\t\t\t\t\t\t<th class='wpda-widget-dbms-separator-end'>Tmp Directory<br/></th>\n\t\t\t\t\t\t\t\t<td class='tmpdir'>{$info['tmpdir']}</td>\n\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t<th colspan='2' id='{$this->id}-log' class='wpda-dbms-link'><i class='fas fa-caret-right'></i> Log Files</th>\n\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t<tr class='log' style='display: none'>\n\t\t\t\t\t\t\t\t<th>Error Log</th>\n\t\t\t\t\t\t\t\t<td class='log_error'>{$info['log_error']} [ON]</td>\n\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t<tr class='log' style='display: none'>\n\t\t\t\t\t\t\t\t<th>General Log</th>\n\t\t\t\t\t\t\t\t<td class='general_log'>{$info['general_log_file']} [{$info['general_log']}]</td>\n\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t\t<tr class='log' style='display: none'>\n\t\t\t\t\t\t\t\t<th>Slow Query Log</th>\n\t\t\t\t\t\t\t\t<td class='slow_query'>{$info['slow_query_log_file']} [{$info['slow_query_log']}]</td>\n\t\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t</table>\n\t\t\t\t\t</div>\n\t\t\t\t\t<div id='{$this->id}-2' style='display: none'></div>\n\t\t\t\t\t<div id='{$this->id}-3' style='display: none'></div>\n\t\t\t\t</div>\n\t\t\t";
        $this->content = $dbms_status;
    }
    
    /**
     * DBMS widget shortcode
     *
     * @param WPDA_Widget_Dbms $widget Widget.
     * @return void
     */
    public function do_shortcode( $widget )
    {
    }
    
    /**
     * Embed DBMS widget
     *
     * @param WPDA_Widget_Dbms $widget Widget.
     * @param string           $target_element HTML element id.
     * @return void
     */
    public function do_embed( $widget, $target_element )
    {
    }
    
    /**
     * DBMS widget javascript code
     *
     * @param boolean $is_backend Running in back-end.
     * @return void
     */
    protected function js( $is_backend = true )
    {
        ?>
			<script type='application/javascript' class="wpda-widget-<?php 
        echo  esc_attr( $this->widget_id ) ;
        ?>">
				jQuery(function() {
					<?php 
        $this->js_init( $is_backend );
        ?>

					jQuery("#<?php 
        echo  esc_attr( $this->id ) ;
        ?>").closest(".wpda-widget").find(".wpda-widget-refresh").on("click", function(e, doaction = null) {
						if (doaction===null) {
							getDbmsInfo(
								jQuery("#<?php 
        echo  esc_attr( $this->id ) ;
        ?>"),
								action,
								args_info
							);

							getDbmsVars(
								jQuery("#<?php 
        echo  esc_attr( $this->id ) ;
        ?>-2"),
								action,
								args_vars,
								true
							);

							getDbmsVars(
								jQuery("#<?php 
        echo  esc_attr( $this->id ) ;
        ?>-3"),
								action,
								args_status,
								true
							);
						}
					});

					<?php 
        ?>
				});
			</script>
			<?php 
    }
    
    /**
     * Javascript initialization code
     *
     * @param boolean $is_backend Running in back-end.
     * @param string  $wpda_caller Caller.
     * @return void
     */
    protected function js_init( $is_backend = true, $wpda_caller = '' )
    {
        // Do not add script tags: this code is injected in JS code.
        ?>
			var action = "wpda_widget_dbms_refresh";
			var args_vars = {};
			args_vars.wpda_name = "<?php 
        echo  esc_attr( $this->name ) ;
        ?>";
			<?php 
        
        if ( $is_backend ) {
            ?>
				args_vars.wp_nonce = "<?php 
            echo  esc_attr( $this->wp_nonce ) ;
            ?>";
				<?php 
        } else {
            ?>
				args_vars.wpda_sonce = "<?php 
            echo  esc_attr( WPDA::wpda_create_sonce() ) ;
            ?>";
				<?php 
        }
        
        ?>
			args_vars.wpda_action = "vars";
			args_vars.wpda_caller = "<?php 
        echo  esc_attr( $wpda_caller ) ;
        ?>";
			console.log(args_vars);

			var args_status = Object.assign({}, args_vars);
			args_status.wpda_action = "status";

			var args_info = Object.assign({}, args_vars);
			args_info.wpda_action = "info";

			setTimeout(waitForJUI_<?php 
        echo  esc_attr( $this->id ) ;
        ?>, 1000);
			function waitForJUI_<?php 
        echo  esc_attr( $this->id ) ;
        ?>() {
				if (window.jQuery && typeof jQuery.ui!=="undefined" && jQuery("#<?php 
        echo  esc_attr( $this->id ) ;
        ?>")) {
					jQuery("#<?php 
        echo  esc_attr( $this->id ) ;
        ?>").tabs();
					// console.log("jQuery UI loaded...");
				} else {
					setTimeout(waitForJUI_<?php 
        echo  esc_attr( $this->id ) ;
        ?>, 1000);
					console.log("Waiting for jQuery UI to be loaded...");
				}
			}

			jQuery("#<?php 
        echo  esc_attr( $this->id ) ;
        ?> :nth-child(2)").on("click", function() {
				getDbmsVars(
					jQuery("#<?php 
        echo  esc_attr( $this->id ) ;
        ?>-2"),
					action,
					args_vars
				);
			});

			jQuery("#<?php 
        echo  esc_attr( $this->id ) ;
        ?> :nth-child(3)").on("click", function() {
				getDbmsVars(
					jQuery("#<?php 
        echo  esc_attr( $this->id ) ;
        ?>-3"),
					action,
					args_status
				);
			});

			jQuery("#<?php 
        echo  esc_attr( $this->id ) ;
        ?>-dir").on("click", function(e) {
				toggleIcon(jQuery(this));
				jQuery(this).closest("tbody").find(".dir").toggle();
			});

			jQuery("#<?php 
        echo  esc_attr( $this->id ) ;
        ?>-log").on("click", function() {
				toggleIcon(jQuery(this));
				jQuery(this).closest("tbody").find(".log").toggle();
			});
			<?php 
    }
    
    /**
     * Construct widget via ajax
     *
     * @return void
     */
    public static function widget()
    {
        $panel_name = ( isset( $_REQUEST['wpda_panel_name'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wpda_panel_name'] ) ) : '' );
        // phpcs:ignore WordPress.Security.NonceVerification
        $panel_dbms = ( isset( $_REQUEST['wpda_panel_dbms'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wpda_panel_dbms'] ) ) : '' );
        // phpcs:ignore WordPress.Security.NonceVerification
        $panel_column = ( isset( $_REQUEST['wpda_panel_column'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wpda_panel_column'] ) ) : '1' );
        // phpcs:ignore WordPress.Security.NonceVerification
        $column_position = ( isset( $_REQUEST['wpda_column_position'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wpda_column_position'] ) ) : 'prepend' );
        // phpcs:ignore WordPress.Security.NonceVerification
        $widget_sequence_nr = ( isset( $_REQUEST['wpda_widget_sequence_nr'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wpda_widget_sequence_nr'] ) ) : '1' );
        // phpcs:ignore WordPress.Security.NonceVerification
        $wdg = new WPDA_Widget_Dbms( array(
            'name'        => $panel_name,
            'schema_name' => $panel_dbms,
            'column'      => $panel_column,
            'position'    => $column_position,
            'widget_id'   => $widget_sequence_nr,
        ) );
        WPDA::sent_header( 'text/html; charset=UTF-8' );
        echo  $wdg->container() ;
        // phpcs:ignore WordPress.Security.EscapeOutput
        wp_die();
    }
    
    /**
     * Refresh widget via ajax
     *
     * @return void
     */
    public static function refresh()
    {
        $is_header_send = false;
        $schema_name = null;
        $widget_name = ( isset( $_POST['wpda_name'] ) ? sanitize_text_field( wp_unslash( $_POST['wpda_name'] ) ) : null );
        if ( !$is_header_send ) {
            WPDA::sent_header( 'application/json' );
        }
        
        if ( !isset( $_REQUEST['wpda_action'] ) ) {
            // phpcs:ignore WordPress.Security.NonceVerification
            echo  static::msg( 'ERROR', 'Invalid arguments' ) ;
            // phpcs:ignore WordPress.Security.EscapeOutput
            wp_die();
        }
        
        
        if ( null === $schema_name ) {
            $widgets = get_user_meta( WPDA::get_current_user_id(), WPDA_Dashboard::USER_DASHBOARD );
            $widget_found = false;
            foreach ( $widgets as $widget ) {
                
                if ( isset( $widget[$widget_name] ) ) {
                    $schema_name = $widgets[$widget_name]['dbsDbms'];
                    $widget_found = true;
                }
            
            }
            
            if ( !$widget_found ) {
                echo  static::msg( 'ERROR', 'Invalid arguments' ) ;
                // phpcs:ignore WordPress.Security.EscapeOutput
                wp_die();
            }
        
        }
        
        switch ( $_REQUEST['wpda_action'] ) {
            // phpcs:ignore WordPress.Security.NonceVerification
            case 'vars':
                static::get_vars( $schema_name );
                break;
            case 'status':
                static::get_status( $schema_name );
                break;
            case 'info':
                static::get_info( $schema_name );
                break;
            default:
                echo  static::msg( 'ERROR', 'Invalid arguments' ) ;
                // phpcs:ignore WordPress.Security.EscapeOutput
                wp_die();
        }
    }
    
    /**
     * Get DBMS data
     *
     * @param string $schema_name Database schema name.
     * @return array
     */
    protected static function get_data( $schema_name )
    {
        return array(
            'hostname'                => WPDA::get_dbms_var( 'hostname', $schema_name ),
            'ssl'                     => WPDA::get_dbms_var( 'have_ssl', $schema_name ),
            'port'                    => WPDA::get_dbms_var( 'port', $schema_name ),
            'version'                 => WPDA::get_dbms_var( 'version', $schema_name ),
            'version_comment'         => WPDA::get_dbms_var( 'version_comment', $schema_name ),
            'version_compile_os'      => WPDA::get_dbms_var( 'version_compile_os', $schema_name ),
            'version_compile_machine' => WPDA::get_dbms_var( 'version_compile_machine', $schema_name ),
            'uptime'                  => WPDA::secondsToTime( WPDA::get_dbms_global( 'uptime', $schema_name ) ),
            'basedir'                 => WPDA::get_dbms_var( 'basedir', $schema_name ),
            'datadir'                 => WPDA::get_dbms_var( 'datadir', $schema_name ),
            'plugin_dir'              => WPDA::get_dbms_var( 'plugin_dir', $schema_name ),
            'tmpdir'                  => WPDA::get_dbms_var( 'tmpdir', $schema_name ),
            'log_error'               => WPDA::get_dbms_var( 'log_error', $schema_name ),
            'general_log'             => WPDA::get_dbms_var( 'general_log', $schema_name ),
            'general_log_file'        => WPDA::get_dbms_var( 'general_log_file', $schema_name ),
            'slow_query_log'          => WPDA::get_dbms_var( 'slow_query_log', $schema_name ),
            'slow_query_log_file'     => WPDA::get_dbms_var( 'slow_query_log_file', $schema_name ),
        );
    }
    
    /**
     * Get DBMS vars
     *
     * @param string $schema_name Database schema name.
     * @return void
     */
    protected static function get_vars( $schema_name )
    {
        $vars = WPDA::get_dbms_var( null, $schema_name );
        $json = array();
        foreach ( $vars as $var ) {
            $json[$var[0]] = $var[1];
        }
        echo  json_encode( $json ) ;
        // phpcs:ignore
        wp_die();
    }
    
    /**
     * Get DBMS status
     *
     * @param string $schema_name Database schema name.
     * @return void
     */
    protected static function get_status( $schema_name )
    {
        $vars = WPDA::get_dbms_global( null, $schema_name );
        $json = array();
        foreach ( $vars as $var ) {
            $json[$var[0]] = $var[1];
        }
        echo  json_encode( $json ) ;
        // phpcs:ignore
        wp_die();
    }
    
    /**\
     * Get DBMS info
     *
     * @param string $schema_name Database schema name.
     * @return void
     */
    protected static function get_info( $schema_name )
    {
        $schema_name = sanitize_text_field( wp_unslash( $_REQUEST['wpda_schemaname'] ) );
        // phpcs:ignore
        echo  json_encode( static::get_data( $schema_name ) ) ;
        // phpcs:ignore
        wp_die();
    }

}