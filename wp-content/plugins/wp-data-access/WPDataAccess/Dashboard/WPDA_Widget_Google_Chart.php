<?php

// phpcs:ignore Standard.Category.SniffName.ErrorCode
namespace WPDataAccess\Dashboard;

use  WPDataAccess\Connection\WPDADB ;
use  WPDataAccess\WPDA ;
/**
 * Chart widget
 */
class WPDA_Widget_Google_Chart extends WPDA_Widget
{
    const  OPTION_CHART_CACHE = 'wpda-chart-cache' ;
    /**
     * Output type
     *
     * @var mixed|string[]
     */
    protected  $outputType = array( 'Table' ) ;
    // phpcs:ignore
    /**
     * Selected chart types
     *
     * @var array|mixed
     */
    protected  $userChartTypeList = array() ;
    // phpcs:ignore
    /**
     * Database name
     *
     * @var mixed|null
     */
    protected  $dbs = null ;
    /**
     * SQL query
     *
     * @var mixed|null
     */
    protected  $query = null ;
    /**
     * Refresh indicator
     *
     * @var mixed|null
     */
    protected  $refresh = null ;
    /**
     * Cache indicator
     *
     * @var mixed|null
     */
    protected  $cache = null ;
    /**
     * Unit of measurement (for cache)
     *
     * @var mixed|null
     */
    protected  $unit = null ;
    /**
     * Queried columns
     *
     * @var array
     */
    protected  $columns = array() ;
    /**
     * Selected rows
     *
     * @var array
     */
    protected  $rows = array() ;
    /**
     * Chart options
     *
     * @var mixed|null
     */
    protected  $options = null ;
    /**
     * Constructor
     *
     * @param array $args Constructor arguments.
     */
    public function __construct( $args = array() )
    {
        parent::__construct( $args );
        $this->can_share = true;
        $this->has_layout = true;
        $this->has_setting = true;
        $this->can_refresh = true;
        // Create container.
        $this->content = "\n\t\t\t\t<div class='wpda-chart-container'>\n\t\t\t\t\t<div class='wpda_widget_chart_selection'>\n\t\t\t\t\t\t<button href='javascript:void(0)' class='dt-button wpda-chart-button-export'>Export data</button>\n\t\t\t\t\t\t<a href='' target='_blank' style='display:none' class='wpda-chart-button-export-link'>Export data hyperlink</a>\n\t\t\t\t\t\t<button href='javascript:void(0)' class='dt-button wpda-chart-button-print' style='display:none'>Printable version</button>\n\t\t\t\t\t\t<select id='wpda_widget_chart_selection_{$this->widget_id}' style='display:none'></select>\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class='wpda_widget_chart_container' id='wpda_widget_container_{$this->widget_id}'></div>\n\t\t\t\t</div>\n\t\t\t";
    }
    
    /**
     * Chart shortcode implementation
     *
     * @param WPDA_Widget_Google_Chart $widget Chart widget.
     * @return void
     */
    public function do_shortcode( $widget )
    {
    }
    
    /**
     * Embed chart widget on external website
     *
     * @param WPDA_Widget_Google_Chart $widget Chart widget.
     * @param string                   $target_element HTML element id.
     * @return void
     */
    public function do_embed( $widget, $target_element )
    {
    }
    
    /**
     * Edit chart form
     *
     * @return void
     */
    public function edit_chart()
    {
        echo  $this->html() ;
        // phpcs:ignore WordPress.Security.EscapeOutput
        echo  $this->js( 300 ) ;
        // phpcs:ignore WordPress.Security.EscapeOutput
    }
    
    /**
     * Chart javascript code
     *
     * @param integer $interval Interval.
     * @return void
     */
    protected function js( $interval = 1000 )
    {
        ?>
			<script type='application/javascript' class="wpda-widget-<?php 
        echo  esc_attr( $this->widget_id ) ;
        ?>">
				jQuery(function() {
					var widget = jQuery("#wpda-widget-<?php 
        echo  esc_attr( $this->widget_id ) ;
        ?>");

					<?php 
        ?>

					widget.find(".wpda-widget-layout").on("click", function() {
						chartLayout("<?php 
        echo  esc_attr( $this->widget_id ) ;
        ?>");
					});

					widget.find(".wpda-widget-setting").on("click", function() {
						chartSettings("<?php 
        echo  esc_attr( $this->widget_id ) ;
        ?>");
					});

					widget.find(".wpda-widget-refresh").on("click", function(e, action = null) {
						if (action==="refresh") {
							refreshChart("<?php 
        echo  esc_attr( $this->widget_id ) ;
        ?>");
						} else {
							getChartData("<?php 
        echo  esc_attr( $this->widget_id ) ;
        ?>");
						}
					});

					jQuery("#wpda_widget_chart_selection_<?php 
        echo  esc_attr( $this->widget_id ) ;
        ?>").on("change", function() {
						refreshChart("<?php 
        echo  esc_attr( $this->widget_id ) ;
        ?>");
					});

					<?php 
        
        if ( 'new' === $this->state ) {
            ?>
						chartSettings("<?php 
            echo  esc_attr( $this->widget_id ) ;
            ?>");
						<?php 
        }
        
        ?>
				});
			</script>
			<?php 
    }
    
    /**
     * Get chart data
     *
     * @param string $dbs Database name.
     * @param string $query SQL query.
     * @return array
     */
    protected static function get_data( $dbs, $query )
    {
        $return_value = array(
            'cols'  => array(),
            'rows'  => array(),
            'error' => '',
        );
        $wpdadb = WPDADB::get_db_connection( $dbs );
        
        if ( null === $wpdadb ) {
            $return_value['error'] = 'Database connection failed';
            return $return_value;
        }
        
        $suppress = $wpdadb->suppress_errors( true );
        // Get column info.
        $wpdadb->query( "\n\t\t\t\tcreate temporary table widget as select * from (\n\t\t\t\t\t{$query}\n\t\t\t\t) resultset limit 0\n\t\t\t" );
        
        if ( '' !== $wpdadb->last_error ) {
            $return_value['error'] = $wpdadb->last_error;
            return $return_value;
        }
        
        $cols = $wpdadb->get_results( 'desc widget', 'ARRAY_A' );
        $cols_return = array();
        foreach ( $cols as $col ) {
            $cols_return[] = array(
                'id'    => $col['Field'],
                'label' => $col['Field'],
                'type'  => self::google_charts_type( $col['Type'] ),
            );
        }
        // Perform query.
        $rows = $wpdadb->get_results( $query, 'ARRAY_A' );
        $rows_return = array();
        foreach ( $rows as $row ) {
            $val = array();
            $index = 0;
            foreach ( $row as $col ) {
                
                if ( 'number' === $cols_return[$index]['type'] ) {
                    
                    if ( is_int( $col ) ) {
                        $col = intval( $col );
                    } else {
                        $col = floatval( $col );
                    }
                
                } elseif ( 'date' === $cols_return[$index]['type'] || 'datetime' === $cols_return[$index]['type'] ) {
                    $year = substr( $col, 0, 4 );
                    $month = substr( $col, 5, 2 );
                    $day = substr( $col, 8, 2 );
                    
                    if ( 'datetime' === $cols_return[$index]['type'] ) {
                        $hrs = substr( $col, 11, 2 );
                        $min = substr( $col, 14, 2 );
                        $sec = substr( $col, 17, 2 );
                        $col = "Date({$year},{$month},{$day},{$hrs},{$min},{$sec})";
                    } else {
                        $col = "Date({$year},{$month},{$day})";
                    }
                
                } elseif ( 'timeofday' === $cols_return[$index]['type'] ) {
                    $hrs = substr( $col, 0, 2 );
                    $min = substr( $col, 3, 2 );
                    $sec = substr( $col, 6, 2 );
                    $col = "[{$hrs},{$min},{$sec},0]";
                }
                
                $val[] = array(
                    'v' => $col,
                );
                $index++;
            }
            $rows_return[] = array(
                'c' => $val,
            );
        }
        $wpdadb->suppress_errors( $suppress );
        $return_value['cols'] = $cols_return;
        $return_value['rows'] = $rows_return;
        return $return_value;
    }
    
    /**
     * Cache query result
     *
     * @param array  $cached_data Cached meta data.
     * @param string $widget_name Widget name.
     * @param array  $data Cached data.
     * @return void
     */
    protected static function write_cache( $cached_data, $widget_name, $data )
    {
    }
    
    /**
     * Read cached data
     *
     * @param string $filename File to read data from.
     * @return false|string|void|null
     */
    protected static function read_cache( $filename )
    {
    }
    
    /**
     * Remove cached data for a specific chart widget
     *
     * @param array  $cached_data Cached meta data.
     * @param string $widget_name Widget name.
     * @return void
     */
    protected static function remove_cache( $cached_data, $widget_name )
    {
    }
    
    /**
     * Chart widget implementation
     *
     * @return void
     */
    public static function widget()
    {
        $panel_name = ( isset( $_REQUEST['wpda_panel_name'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wpda_panel_name'] ) ) : '' );
        // phpcs:ignore WordPress.Security.NonceVerification
        $panel_dbs = ( isset( $_REQUEST['wpda_panel_dbs'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wpda_panel_dbs'] ) ) : '' );
        // phpcs:ignore WordPress.Security.NonceVerification
        $panel_query = ( isset( $_REQUEST['wpda_panel_query'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wpda_panel_query'] ) ) : '' );
        // phpcs:ignore WordPress.Security.NonceVerification
        $panel_column = ( isset( $_REQUEST['wpda_panel_column'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wpda_panel_column'] ) ) : '1' );
        // phpcs:ignore WordPress.Security.NonceVerification
        $column_position = ( isset( $_REQUEST['wpda_column_position'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wpda_column_position'] ) ) : 'prepend' );
        // phpcs:ignore WordPress.Security.NonceVerification
        $widget_sequence_nr = ( isset( $_REQUEST['wpda_widget_sequence_nr'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wpda_widget_sequence_nr'] ) ) : '1' );
        // phpcs:ignore WordPress.Security.NonceVerification
        $wdg = new WPDA_Widget_Google_Chart( array(
            'outputtype' => array( 'Table' ),
            'name'       => $panel_name,
            'dbs'        => $panel_dbs,
            'query'      => $panel_query,
            'column'     => $panel_column,
            'position'   => $column_position,
            'widget_id'  => $widget_sequence_nr,
        ) );
        WPDA::sent_header( 'text/html; charset=UTF-8' );
        echo  $wdg->container() ;
        // phpcs:ignore WordPress.Security.EscapeOutput
        wp_die();
    }
    
    /**
     * Widget refresh implementation
     *
     * @return void
     */
    public static function refresh()
    {
        $is_header_send = false;
        $dbs = null;
        $query = null;
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
        
        
        if ( null === $dbs || null === $query ) {
            $widgets = get_user_meta( WPDA::get_current_user_id(), WPDA_Dashboard::USER_DASHBOARD );
            $widget_found = false;
            foreach ( $widgets as $widget ) {
                
                if ( isset( $widget[$widget_name] ) ) {
                    $dbs = $widget[$widget_name]['chartDbs'];
                    $query = wp_unslash( $widget[$widget_name]['chartSql'] );
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
            case 'get_data':
                echo  wp_json_encode( static::get_data( $dbs, $query ) ) ;
                wp_die();
                break;
            case 'refresh':
                // TODO ???
                wp_die();
                break;
            default:
                echo  static::msg( 'ERROR', 'Invalid arguments' ) ;
                // phpcs:ignore WordPress.Security.EscapeOutput
                wp_die();
        }
    }
    
    /**
     * Supported data types for charts
     *
     * @param string $data_type Original data type.
     * @return string
     */
    public static function google_charts_type( $data_type )
    {
        $type = explode( '(', $data_type );
        switch ( $type[0] ) {
            case 'tinyint':
            case 'smallint':
            case 'mediumint':
            case 'int':
            case 'bigint':
            case 'float':
            case 'double':
            case 'decimal':
            case 'year':
                return 'number';
            case 'date':
                return 'date';
            case 'datetime':
            case 'timestamp':
                return 'datetime';
            case 'time':
                // TODO Timeofday returns an error in Google Charts
                // Workaround = return time as string
                // return 'timeofday';
                // .
            // TODO Timeofday returns an error in Google Charts
            // Workaround = return time as string
            // return 'timeofday';
            // .
            default:
                return 'string';
        }
    }

}