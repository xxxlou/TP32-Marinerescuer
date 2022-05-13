<?php

/**
 * Suppress "error - 0 - No summary was found for this file" on phpdoc generation
 *
 * @package WPDataAccess\Data_Publisher
 */
namespace WPDataAccess\Data_Publisher;

use  WPDataAccess\Data_Dictionary\WPDA_Dictionary_Lists ;
use  WPDataAccess\Data_Dictionary\WPDA_List_Columns_Cache ;
use  WPDataAccess\Data_Tables\WPDA_Data_Tables ;
use  WPDataAccess\Plugin_Table_Models\WPDA_Table_Settings_Model ;
use  WPDataAccess\Plugin_Table_Models\WPDA_Publisher_Model ;
use  WPDataAccess\Premium\WPDAPRO_CPT\WPDAPRO_CPT_Services ;
use  WPDataAccess\Simple_Form\WPDA_Simple_Form ;
use  WPDataAccess\Simple_Form\WPDA_Simple_Form_Item_Boolean ;
use  WPDataAccess\Simple_Form\WPDA_Simple_Form_Item_Enum ;
use  WPDataAccess\Simple_Form\WPDA_Simple_Form_Item_Enum_Radio ;
use  WPDataAccess\WPDA ;
use function  GuzzleHttp\Psr7\_caseless_remove ;
/**
 * Class WPDA_Publisher_Form extends WPDA_Simple_Form
 *
 * Data entry form which allows users to create, update and test publications. A publication consists of a database
 * table, a number of columns and some options. A shortcode can be generated for a publication. The shortcode can
 * be copied to the clipboard and from there pasted in a WordPress post or page. The shortcode is used to add a
 * dynamic HTML table to a post or page that supports searching, pagination and sorting. Tables are created with
 * jQuery DataTables.
 *
 * @author  Peter Schulz
 * @since   2.0.15
 */
class WPDA_Publisher_Form extends WPDA_Simple_Form
{
    protected  $hyperlinks = array() ;
    protected  $color = array() ;
    protected  $databases = array() ;
    protected  $cpts = array() ;
    protected  $cpts_non_selectable = array() ;
    protected  $cpts_selected = array() ;
    protected  $cfds = array() ;
    protected  $cfds_selectable = array() ;
    protected  $cfds_hidden = array() ;
    /**
     * WPDA_Publisher_Form constructor.
     *
     * @param string $schema_name Database schema name
     * @param string $table_name Database table name
     * @param object $wpda_list_columns Handle to instance of WPDA_List_Columns
     * @param array  $args
     */
    public function __construct(
        $schema_name,
        $table_name,
        &$wpda_list_columns,
        $args = array()
    )
    {
        // Add column labels.
        $args['column_headers'] = array(
            'pub_id'                          => __( 'Pub ID', 'wp-data-accesss' ),
            'pub_name'                        => __( 'Publication Name', 'wp-data-accesss' ),
            'pub_schema_name'                 => __( 'Database', 'wp-data-access' ),
            'pub_data_source'                 => __( '', 'wp-data-access' ),
            'pub_table_name'                  => __( 'Table/View Name', 'wp-data-accesss' ),
            'pub_column_names'                => __( 'Column Names (* = all)', 'wp-data-accesss' ),
            'pub_format'                      => __( 'Column Labels', 'wp-data-accesss' ),
            'pub_query'                       => __( '* Query', 'wp-data-access' ),
            'pub_sort_icons'                  => __( 'Sort Icons', 'wp-data-access' ),
            'pub_styles'                      => __( 'Styling', 'wp-data-access' ),
            'pub_style_premium'               => __( 'Enable Premium Styling', 'wp-data-access' ),
            'pub_style_color'                 => __( 'Color', 'wp-data-access' ),
            'pub_style_space'                 => __( 'Spacing', 'wp-data-access' ),
            'pub_style_corner'                => __( 'Corner Radius', 'wp-data-access' ),
            'pub_style_modal_width'           => __( 'Modal Width', 'wp-data-access' ),
            'pub_responsive'                  => __( 'Output', 'wp-data-accesss' ),
            'pub_responsive_popup_title'      => __( 'Popup Title', 'wp-data-accesss' ),
            'pub_responsive_cols'             => __( 'Number Of Columns', 'wp-data-accesss' ),
            'pub_responsive_type'             => __( 'Type', 'wp-data-accesss' ),
            'pub_responsive_modal_hyperlinks' => __( 'Hyperlinks On Modal', 'wp-data-access' ),
            'pub_responsive_icon'             => __( 'Show Icon', 'wp-data-accesss' ),
            'pub_default_where'               => __( 'WHERE Clause', 'wp-data-access' ),
            'pub_default_orderby'             => __( 'Default Order By', 'wp-data-access' ),
            'pub_table_options_searching'     => __( 'Allow Searching?', 'wp-data-access' ),
            'pub_table_options_ordering'      => __( 'Allow Ordering?', 'wp-data-access' ),
            'pub_table_options_paging'        => __( 'Allow Paging?', 'wp-data-access' ),
            'pub_table_options_serverside'    => __( 'Server Side Processing?', 'wp-data-access' ),
            'pub_table_options_nl2br'         => __( 'NL > BR', 'wp-data-access' ),
            'pub_table_options_advanced'      => __( 'Advanced Options', 'wp-data-access' ),
            'pub_extentions'                  => __( 'Publication Extentions', 'wp-data-access' ),
            'pub_cpt'                         => __( 'Post type', 'wp-data-access' ),
            'pub_cpt_fields'                  => __( 'Custom fields', 'wp-data-access' ),
            'pub_cpt_query'                   => __( 'CPT query', 'wp-data-access' ),
            'pub_cpt_format'                  => __( 'Field labels', 'wp-data-access' ),
        );
        $this->check_table_type = false;
        $this->title = 'Data Publisher';
        $args['help_url'] = 'https://wpdataaccess.com/docs/documentation/data-publisher/data-publisher-getting-started/';
        parent::__construct(
            $schema_name,
            $table_name,
            $wpda_list_columns,
            $args
        );
        // Get available databases.
        $schema_names = WPDA_Dictionary_Lists::get_db_schemas();
        foreach ( $schema_names as $schema_name ) {
            array_push( $this->databases, $schema_name['schema_name'] );
        }
        // Add scripts and styles.
        WPDA_Data_Tables::enqueue_styles_and_script();
    }
    
    /**
     * Overwrites method add_buttons
     */
    public function add_buttons()
    {
        $index = $this->get_item_index( 'pub_id' );
        $pub_id_item = $this->form_items[$index];
        $pub_id = $pub_id_item->get_item_value();
        $disabled = ( 'new' === $this->action ? 'disabled' : '' );
        ?>
			<a href="javascript:void(0)"
			   onclick="test_publication()"
			   class="button wpda_tooltip <?php 
        echo  esc_attr( $disabled ) ;
        ?>"
			   title="Test publication"
			>
				<span class="material-icons wpda_icon_on_button">bug_report</span><?php 
        echo  __( 'Test', 'wp-data-access' ) ;
        ?>
			</a>
			<?php 
        $this->show_shortcode( $pub_id );
    }
    
    /**
     * Overwrites method prepare_items
     *
     * @param bool $set_back_form_values
     */
    public function prepare_items( $set_back_form_values = false )
    {
        parent::prepare_items( $set_back_form_values );
        $i = 0;
        foreach ( $this->form_items as $form_item ) {
            // Prepare listbox for column pub_schema_name
            
            if ( $form_item->get_item_name() === 'pub_schema_name' ) {
                if ( '' === $form_item->get_item_value() || null === $form_item->get_item_value() ) {
                    $form_item->set_item_value( WPDA::get_user_default_scheme() );
                }
                $form_item->set_enum( $this->databases );
                $this->form_items[$i] = new WPDA_Simple_Form_Item_Enum( $form_item );
            }
            
            // Prepare listbox for column pub_table_name
            if ( $form_item->get_item_name() === 'pub_table_name' ) {
                $this->form_items[$i] = new WPDA_Simple_Form_Item_Enum( $form_item );
            }
            // Set default value for popup title.
            if ( $form_item->get_item_name() === 'pub_responsive_popup_title' ) {
                $form_item->set_item_default_value( __( 'Row details', 'wp-data-access' ) );
            }
            // Prepare listbox for column pub_responsive.
            
            if ( $form_item->get_item_name() === 'pub_responsive' ) {
                $form_item->set_enum( array( 'Responsive', 'Flat' ) );
                $form_item->set_enum_options( array( 'Yes', 'No' ) );
            }
            
            // Prepare selection for column pub_column_names.
            
            if ( $form_item->get_item_name() === 'pub_column_names' ) {
                $title = __( 'Select columns shown in publication', 'wp-data-access' );
                $form_item->set_item_hide_icon( true );
                $form_item->set_item_js( 'jQuery("#pub_column_names").parent().parent().find("td.icon").append("<a id=\'select_columns\' class=\'button wpda_tooltip\' href=\'javascript:void(0)\' title=\'' . $title . '\' onclick=\'select_columns()\'>' . '<span class=\'material-icons wpda_icon_on_button\'>view_list</span> ' . __( 'Select', 'wp-data-access' ) . '</a>");' );
            }
            
            // Prepare column label settings.
            
            if ( $form_item->get_item_name() === 'pub_format' ) {
                $title = __( 'Define columns for publication (not necessary if already defined in Data Explorer table settings)', 'wp-data-access' );
                $form_item->set_item_hide_icon( true );
                $form_item->set_item_class( 'hide_item' );
                $form_item->set_item_js( 'jQuery("#pub_format").parent().parent().find("td.data").append("<a id=\'format_columns\' class=\'button wpda_tooltip\' href=\'javascript:void(0)\' title=\'' . $title . '\' onclick=\'format_columns()\'>' . '<span class=\'material-icons wpda_icon_on_button\'>label</span> ' . __( 'Click to define column labels', 'wp-data-access' ) . '</a>");' );
            }
            
            if ( 'pub_responsive_popup_title' === $form_item->get_item_name() || 'pub_responsive_cols' === $form_item->get_item_name() || 'pub_responsive_type' === $form_item->get_item_name() || 'pub_responsive_modal_hyperlinks' === $form_item->get_item_name() || 'pub_responsive_icon' === $form_item->get_item_name() ) {
                $form_item->set_hide_item_init( true );
            }
            if ( 'pub_table_options_advanced' === $form_item->get_item_name() ) {
                if ( '' === $form_item->get_item_value() || null === $form_item->get_item_value() ) {
                    $form_item->set_item_value( '{}' );
                }
            }
            
            if ( 'pub_table_options_searching' === $form_item->get_item_name() || 'pub_table_options_ordering' === $form_item->get_item_name() || 'pub_table_options_paging' === $form_item->get_item_name() || 'pub_table_options_serverside' === $form_item->get_item_name() ) {
                if ( 'pub_table_options_searching' !== $form_item->get_item_name() ) {
                    $form_item->set_hide_item_init( true );
                }
                $form_item->checkbox_value_on = 'on';
                if ( 'new' === $this->action ) {
                    $form_item->set_item_value( 'on' );
                }
                $this->form_items[$i] = new WPDA_Simple_Form_Item_Boolean( $form_item );
            }
            
            
            if ( 'pub_table_options_nl2br' === $form_item->get_item_name() ) {
                $form_item->set_hide_item_init( true );
                $form_item->checkbox_value_on = 'on';
                $this->form_items[$i] = new WPDA_Simple_Form_Item_Boolean( $form_item );
            }
            
            
            if ( $form_item->get_item_name() === 'pub_styles' ) {
                $options = $form_item->get_item_enum();
                $option_values = $options;
                $option_values[0] = 'default = stripe + hover + order-column + row-border';
                $form_item->set_enum( $option_values );
                $form_item->set_enum_options( $options );
            }
            
            if ( 'pub_default_orderby' === $form_item->get_item_name() ) {
                $form_item->set_item_hide_icon( true );
            }
            $i++;
        }
    }
    
    protected function init_custom_fields()
    {
    }
    
    protected function add_fieldsets()
    {
        $fields = array();
        foreach ( $this->form_items as $item ) {
            $fields[$item->get_item_name()] = true;
        }
        $data = array(
            'pub_id',
            'pub_name',
            'pub_schema_name',
            'pub_table_name',
            'pub_column_names',
            'pub_format'
        );
        $styling = array( 'pub_sort_icons', 'pub_styles' );
        $this->fieldsets = array(
            'Publication Data'    => array(
            'id'     => 'pub_main',
            'fields' => $data,
        ),
            'Publication Type'    => array(
            'id'         => 'pub_type',
            'fields'     => array(
            'pub_responsive',
            'pub_responsive_popup_title',
            'pub_responsive_cols',
            'pub_responsive_type',
            'pub_responsive_modal_hyperlinks',
            'pub_responsive_icon'
        ),
            'expandable' => true,
        ),
            'Publication Styling' => array(
            'id'         => 'pub_styling',
            'fields'     => $styling,
            'expandable' => true,
        ),
        );
        $this->fieldsets['Advanced Settings'] = array(
            'id'         => 'pub_advanced',
            'fields'     => array(
            'pub_default_where',
            'pub_default_orderby',
            'pub_table_options_searching',
            'pub_table_options_ordering',
            'pub_table_options_paging',
            'pub_table_options_serverside',
            'pub_table_options_nl2br',
            'pub_table_options_advanced'
        ),
            'expandable' => true,
        );
    }
    
    /**
     * Overwrites method show
     *
     * @param bool   $allow_save
     * @param string $add_param
     */
    public function show( $allow_save = true, $add_param = '' )
    {
        parent::show( $allow_save, $add_param );
        $index = $this->get_item_index( 'pub_id' );
        $pub_id_item = $this->form_items[$index];
        $pub_id = $pub_id_item->get_item_value();
        $index = $this->get_item_index( 'pub_schema_name' );
        $schema_name_item = $this->form_items[$index];
        $schema_name = $schema_name_item->get_item_value();
        global  $wpdb ;
        $wpdb_name = $wpdb->dbname;
        $index = $this->get_item_index( 'pub_table_name' );
        $table_name_item = $this->form_items[$index];
        $table_name = $table_name_item->get_item_value();
        $table_columns = WPDA_List_Columns_Cache::get_list_columns( $schema_name, $table_name );
        $columns = array();
        foreach ( $table_columns->get_table_columns() as $table_column ) {
            array_push( $columns, $table_column['column_name'] );
        }
        $column_labels = $table_columns->get_table_column_headers();
        $json_editing = WPDA::get_option( WPDA::OPTION_DP_JSON_EDITING );
        $wpda_table_settings_db = WPDA_Table_Settings_Model::query( $table_name, $schema_name );
        
        if ( isset( $wpda_table_settings_db[0]['wpda_table_settings'] ) ) {
            $wpda_table_settings = json_decode( $wpda_table_settings_db[0]['wpda_table_settings'] );
            if ( isset( $wpda_table_settings->hyperlinks ) ) {
                foreach ( $wpda_table_settings->hyperlinks as $hyperlink ) {
                    $hyperlink_label = ( isset( $hyperlink->hyperlink_label ) ? $hyperlink->hyperlink_label : '' );
                    $hyperlink_html = ( isset( $hyperlink->hyperlink_html ) ? $hyperlink->hyperlink_html : '' );
                    if ( $hyperlink_label !== '' && $hyperlink_html !== '' ) {
                        array_push( $this->hyperlinks, $hyperlink_label );
                    }
                }
            }
        }
        
        $index = $this->get_item_index( 'pub_data_source' );
        $pub_data_source_item = $this->form_items[$index];
        $pub_data_source = $pub_data_source_item->get_item_value();
        ?>
			<style>
                #pub_cpt_fields,
				#pub_cpt_format,
				#pub_default_orderby {
                    display: none;
                }
                table.wpda_simple_table td.icon a.button.wpda_tooltip {
                    width: 120px;
					text-align: center;
                }
                span.pub_premium {
                    line-height: 40px;
                }
                span.pub_buttons {
                    float: right;
                    margin-top: 5px;
                    margin-bottom: 5px;
                }
                div.multiselect_sortable_content {
                    margin: 0 0 0 6px;
                }
                div.selection div.selection_title,
                div.selectable div.selectable_title {
                    font-weight: bold;
                }
                div.selection ul.selection_content,
                div.selectable ul.selectable_content {
                    background-color: white;
                    border: 1px solid #8c8f94;
                    border-radius: 4px;
                    height: 120px;
                }
                div.selection ul.selection_content li,
                div.selectable ul.selectable_content li {
                    cursor: pointer;
                    padding: 0 10px;
                    margin: 0;
                    border: 0;
                    user-select: none;
                }
                div.selection ul.selection_content li:hover,
                div.selectable ul.selectable_content li:hover {
                    font-weight: bold;
                    background-color: lightgrey;
                }
                select.multiselect_sortable_hide {
                    display: none;
                }
				.pub-post-types {
                    padding-top: 20px !important;
				}
			</style>
			<script type='text/javascript'>
				let wpda_qb_columns = [];
				let wpda_sp_columns = [];

				let cpts_all = <?php 
        echo  wp_json_encode( $this->cpts ) ;
        ?>;
				let cpts_non_selectable = <?php 
        echo  wp_json_encode( $this->cpts_non_selectable ) ;
        ?>;

				let cfds_selected = <?php 
        echo  wp_json_encode( $this->cfds ) ;
        ?>;
				let cfds_selection = <?php 
        echo  wp_json_encode( $this->cfds_selectable ) ;
        ?>;
				let cfds_hidden = <?php 
        echo  wp_json_encode( $this->cfds_hidden ) ;
        ?>;
				let cfds_default_selectable = [];
				let cfds_default_hidden = [];

				function set_responsive_columns() {
					if (jQuery('#pub_responsive').val() == 'Yes') {
						// Show responsive settings
						jQuery('#pub_responsive_popup_title').parent().parent().show();
						jQuery('#pub_responsive_cols').parent().parent().show();
						jQuery('#pub_responsive_type').parent().parent().show();
						jQuery('#pub_responsive_modal_hyperlinks').parent().parent().show();
						jQuery('#pub_responsive_icon').parent().parent().show();
					} else {
						// Hide responsive settings
						jQuery('#pub_responsive_popup_title').parent().parent().hide();
						jQuery('#pub_responsive_cols').parent().parent().hide();
						jQuery('#pub_responsive_type').parent().parent().hide();
						jQuery('#pub_responsive_modal_hyperlinks').parent().parent().hide();
						jQuery('#pub_responsive_icon').parent().parent().hide();
					}
				}

				function get_selected_columns() {
					let selectedColumns = table_columns;
					let dataSource = jQuery("input[name='pub_data_source']:checked").val();
					if (dataSource==="CPT") {
						selectedColumns = cpt_fields;
					} else if (dataSource==="Table") {
						if (jQuery("#pub_column_names").val() !== "*") {
							selectedColumns = jQuery("#pub_column_names").val().split(",");
						}
					}
					return selectedColumns;
				}

				<?php 
        ?>

				function pre_submit_form() {
					// Simple form will automatically find and execute this function before the submit_form().
					// Process order by.
					let defaultOrderBy = "";
					jQuery(".wpda_dp_orderby").each(function() {
						let index = jQuery(this).data("index");
						let column = jQuery("#orderby" + index).val();
						let order = jQuery("#order" + index).val();
						if (column!=="") {
							if (defaultOrderBy!=="") {
								defaultOrderBy += "|";
							}
							defaultOrderBy += column;
							if (order!=="") {
								defaultOrderBy += "," + order;
							}
						}
					});
					jQuery("#pub_default_orderby").val(defaultOrderBy);

					<?php 
        ?>

					return true;
				}

				function getListValues(list) {
					let values = [];
					list.each(
						function() {
							values.push(jQuery(this).text());
						}
					);
					return values;
				}

				function update_table_list(table_name = '') {
					var url = location.pathname + '?action=wpda_get_tables';
					var data = {
						wpdaschema_name: jQuery("[name='pub_schema_name']").val(),
						wpda_wpnonce: '<?php 
        echo  esc_attr( wp_create_nonce( 'wpda-getdata-access-' . WPDA::get_current_user_login() ) ) ;
        ?>'
					};
					jQuery.post(
						url,
						data,
						function (data) {
							jQuery('[name="pub_table_name"]').empty();
							var tables = JSON.parse(data);
							for (var i = 0; i < tables.length; i++) {
								jQuery('<option/>', {
									value: tables[i].table_name,
									html: tables[i].table_name
								}).appendTo("[name='pub_table_name']");
							}
							if (table_name!=='') {
								jQuery("[name='pub_table_name']").val(table_name);
							} else {
								jQuery('#pub_column_names').val('*');
								jQuery('#pub_format').val('');
								table_columns = [];
							}
						}
					);
				}

				function updateWordPressDatabaseName() {
					jQuery("#pub_schema_name option[value='<?php 
        echo  esc_attr( $wpdb_name ) ;
        ?>']").text("WordPress database (<?php 
        echo  esc_attr( $wpdb_name ) ;
        ?>)");
				}

				jQuery(function () {
					updateWordPressDatabaseName();

					<?php 
        if ( wpda_freemius()->is_free_plan() ) {
            ?>
						jQuery("#pub_id").closest("tbody").append("<tr><td></td><td><span class='pub_premium'>The premium version supports custom queries and custom post types</span><span class='pub_buttons'><a href='https://wpdataaccess.com/pricing/' target='_blank' class='button button-primary'>UPGRADE TO PREMIUM</a> <a href='https://wpdataaccess.com/docs/documentation/data-publisher/custom-queries/' target='_blank' class='button'>READ MORE</a></span></td><td></td></tr>");
						jQuery("#pub_sort_icons").closest("tbody").append("<tr><td></td><td><span class='pub_premium'>The premium version allows styling from within the Data Publisher</span><span class='pub_buttons'><a href='https://wpdataaccess.com/pricing/' target='_blank' class='button button-primary'>UPGRADE TO PREMIUM</a> <a href='https://wpdataaccess.com/docs/documentation/data-publisher/premium-styling/' target='_blank' class='button'>READ MORE</a></span></td><td></td></tr>");
						jQuery("#pub_default_where").closest("tbody").append("<tr><td></td><td><span class='pub_premium'>The premium version supports extension management from within the Data Publisher</span><span class='pub_buttons'><a href='https://wpdataaccess.com/pricing/' target='_blank' class='button button-primary'>UPGRADE TO PREMIUM</a> <a href='https://wpdataaccess.com/docs/documentation/data-publisher/premium-extentions/' target='_blank' class='button'>READ MORE</a></span></td><td></td></tr>");
						<?php 
        }
        ?>

					pub_table_options_searching = jQuery('#pub_table_options_searching').parent().parent();
					pub_table_options_ordering = jQuery('#pub_table_options_ordering').parent().parent().children();
					pub_table_options_ordering_tr = jQuery(pub_table_options_ordering).parent().parent();
					pub_table_options_paging = jQuery('#pub_table_options_paging').parent().parent().children();
					pub_table_options_paging_tr = jQuery(pub_table_options_paging).parent().parent();
					pub_table_options_serverside = jQuery('#pub_table_options_serverside').parent().parent().children();
					pub_table_options_serverside_tr = jQuery(pub_table_options_serverside).parent().parent();
					pub_table_options_nl2br = jQuery('#pub_table_options_nl2br').parent().parent().children();
					pub_table_options_nl2br_tr = jQuery(pub_table_options_nl2br).parent().parent();

					jQuery('<span style="width:10px;display:inline-block;"></span>').appendTo(pub_table_options_searching);
					pub_table_options_ordering.appendTo(pub_table_options_searching);
					jQuery('<span style="width:10px;display:inline-block;"></span>').appendTo(pub_table_options_searching);
					pub_table_options_paging.appendTo(pub_table_options_searching);
					jQuery('<span style="width:10px;display:inline-block;"></span>').appendTo(pub_table_options_searching);
					pub_table_options_serverside.appendTo(pub_table_options_searching);
					jQuery('<span style="width:10px;display:inline-block;"></span>').appendTo(pub_table_options_searching);
					pub_table_options_nl2br.appendTo(pub_table_options_searching);

					pub_table_options_ordering_tr.remove();
					pub_table_options_paging_tr.remove();
					pub_table_options_serverside_tr.remove();
					pub_table_options_nl2br_tr.remove();

					set_responsive_columns();

					<?php 
        if ( WPDA::OPTION_DP_JSON_EDITING[1] === $json_editing ) {
            ?>
					var cm = wp.codeEditor.initialize(jQuery('#pub_table_options_advanced'), cm_settings);
					<?php 
        }
        ?>

					jQuery("[name='pub_schema_name']").on('change', function () {
						update_table_list();
					});
					update_table_list('<?php 
        echo  esc_attr( $table_name ) ;
        ?>');

					jQuery("[name='pub_table_name']").on('change', function () {
						jQuery('#pub_column_names').val('*');
						jQuery('#pub_format').val('');
						table_columns = [];
					});

					<?php 
        ?>

					jQuery('#pub_default_where').closest("tr").find('td.icon').empty().append('<span title="Enter a valid sql where clause, for example:\nfirst_name like \'Peter%\'" class="material-icons pointer wpda_tooltip">help</span>');
					jQuery('#pub_table_options_searching').closest("tr").find('td.icon').empty().append('<span title="When paging is disabled, all rows are fetch on page load (this implicitly disables server side processing)\n\nEnable NL > BR to automatically convert New Lines to <BR> tags" class="material-icons pointer wpda_tooltip">help</span>');
					jQuery('#pub_table_options_advanced').closest("tr").find('td.icon').empty().append('<span title=\'Must be valid JSON:\n{"option":"value","option2","value2"}\' class="material-icons pointer wpda_tooltip">help</span>');
					jQuery('#pub_table_options_advanced').closest("tr").find('td.icon').append('<br/><a href="https://datatables.net/reference/option/" target="_blank" title="Click to check jQuery DataTables website for available\noptions (opens in a new tab or window)" class="dashicons dashicons-external wpda_tooltip" style="margin-top:5px;"></a>');
					jQuery('#pub_sort_icons').closest("tr").find('td.icon').empty().append('<span title="default: jQuery DataTables sort icons\nplugin: material ui sort icons\nnone: hide sort icons" class="material-icons pointer wpda_tooltip">help</span>');
					jQuery('#pub_styles').closest("tr").find('td.icon').empty().append('<span title="Hold control key to selected multiple" class="material-icons pointer wpda_tooltip">help</span>');

					jQuery( '.wpda_tooltip' ).tooltip();
					jQuery( '.wpda_tooltip_ic' ).tooltip({
						tooltipClass: "wpda_tooltip_css_ic",
					});

					<?php 
        if ( 'view' === $this->action ) {
            ?>
					jQuery('#format_columns').prop("readonly", true).prop("disabled", true).addClass("disabled");
					jQuery('#select_columns').prop("readonly", true).prop("disabled", true).addClass("disabled");
					<?php 
        }
        ?>

					jQuery('#pub_responsive').on('change', function () {
						set_responsive_columns();
					});

					if (jQuery("#pub_style_color").length) {
						currentValue = jQuery("#pub_style_color").val();
						jQuery("#pub_style_color").replaceWith("<select id='pub_style_color' name='pub_style_color'></select>")
						<?php 
        foreach ( $this->color as $color ) {
            echo  'jQuery("#pub_style_color").append(new Option("' . esc_attr( $color ) . '", "' . esc_attr( $color ) . '"));' ;
        }
        ?>
						jQuery("#pub_style_color").val(currentValue);
					}

					if (jQuery("#pub_style_space").length) {
						jQuery("#pub_style_space").attr("type", "range").attr("min", 0).attr("max", 50);
						jQuery("#pub_style_space").closest("tr").find("td.icon").append("<span id ='pub_style_space_val' class='wpda-range'>");
						jQuery("#pub_style_space").on("change", function() { jQuery("#pub_style_space_val").html(this.value + "px"); });
						jQuery("#pub_style_space_val").html(jQuery("#pub_style_space").val() + "px");
					}

					if (jQuery("#pub_style_corner").length) {
						jQuery("#pub_style_corner").attr("type", "range").attr("min", 0).attr("max", 50);
						jQuery("#pub_style_corner").closest("tr").find("td.icon").append("<span id ='pub_style_corner_val' class='wpda-range'>");
						jQuery("#pub_style_corner").on("change", function() { jQuery("#pub_style_corner_val").html(this.value + "px"); });
						jQuery("#pub_style_corner_val").html(jQuery("#pub_style_corner").val() + "px");
					}

					if (jQuery("#pub_style_modal_width").length) {
						jQuery("#pub_style_modal_width").attr("type", "range").attr("min", 50).attr("max", 100);
						jQuery("#pub_style_modal_width").closest("tr").find("td.icon").append("<span id ='pub_style_modal_width_val' class='wpda-range'>");
						jQuery("#pub_style_modal_width").on("change", function() { jQuery("#pub_style_modal_width_val").html(this.value + "px"); });
						jQuery("#pub_style_modal_width_val").html(jQuery("#pub_style_modal_width").val() + "%");
					}

					// Add default order by UI.
					jQuery("#pub_default_orderby").before(createOrderByLine(0));
					// Restore default sorting.
					let defaultSorting = jQuery("#pub_default_orderby").val();
					let arraySorting = defaultSorting.split("|");
					for (let i=0; i<arraySorting.length; i++) {
						if (arraySorting[i]!=="") {
							if (jQuery("#orderby" + i).length===0) {
								// Add order by line.
								jQuery("#pub_default_orderby").before(createOrderByLine(i));
							}
							let orderBy = arraySorting[i].split(",");
							if (orderBy[0]!=="") {
								jQuery("#orderby" + i).val(orderBy[0]);
								jQuery("#order" + i).val(orderBy[1]);
							}
						}
					}

					<?php 
        ?>
				});

				let no_cols_selected = '* (= show all columns)';

				let table_columns = [];
				let cpt_fields = [];
				<?php 
        foreach ( $columns as $column ) {
            ?>
					table_columns.push('<?php 
            echo  esc_attr( $column ) ;
            ?>');
					<?php 
        }
        ?>

				var hyperlinks = [];
				<?php 
        if ( null !== $this->hyperlinks && is_array( $this->hyperlinks ) ) {
            foreach ( $this->hyperlinks as $hyperlink ) {
                echo  "hyperlinks.push('{$hyperlink}');" ;
                // phpcs:ignore WordPress.Security.EscapeOutput
            }
        }
        ?>

				function select_available(e) {
					var option = jQuery("#columns_available option:selected");
					var add_to = jQuery("#columns_selected");

					option.remove();
					new_option = add_to.append(option);

					if (jQuery("#columns_selected option[value='*']").length > 0) {
						// Remove ALL from selected list.
						jQuery("#columns_selected option[value='*']").remove();
					}

					jQuery('select#columns_selected option').prop("selected", false);
				}

				function select_selected() {
					var option = jQuery("#columns_selected option:selected");
					if (option[0].value === '*') {
						// Cannot remove ALL.
						return;
					}

					var add_to = jQuery("#columns_available");

					option.remove();
					add_to.append(option);

					if (jQuery('select#columns_selected option').length === 0) {
						jQuery("#columns_selected").append(jQuery('<option></option>').attr('value', '*').text(no_cols_selected));
					}

					jQuery('select#columns_available option').prop("selected", false);
				}

				function select_columns() {
					if (!(Array.isArray(table_columns) && table_columns.length)) {
						alert("<?php 
        echo  __( 'To select columns you need to save your publication first', 'wp-data-access' ) ;
        ?>");
						return;
					}

					var columns_available = jQuery(
						'<select id="columns_available" name="columns_available[]" multiple size="8" style="width:200px" onchange="select_available()">' +
						'</select>'
					);
					jQuery.each(table_columns, function (i, val) {
						columns_available.append(jQuery('<option></option>').attr('value', val).text(val));
					});
					for (let i=0; i<hyperlinks.length;i++) {
						columns_available.append(jQuery('<option></option>').attr('value', 'wpda_hyperlink_' + i).text('Hyperlink: ' + hyperlinks[i]));
					}

					var currently_select_option = '';
					var currently_select_values = jQuery('#pub_column_names').val();
					if (currently_select_values == '*') {
						currently_select_values = [];
					} else {
						currently_select_values = currently_select_values.split(',');
					}
					if (currently_select_values.length === 0) {
						currently_select_option = '<option value="*">' + no_cols_selected + '</option>';
					} else {
						for (let i=0; i < currently_select_values.length; i++) {
							if (currently_select_values[i].substr(0,15)==='wpda_hyperlink_') {
								hyperlink_no = currently_select_values[i].substr(15);
								if (hyperlink_no<hyperlinks.length) {
									option_text = 'Hyperlink: ' + hyperlinks[hyperlink_no];
									currently_select_option += '<option value="' + currently_select_values[i] + '">' + option_text + '</option>';
								}
							} else {
								option_text = currently_select_values[i];
								currently_select_option += '<option value="' + currently_select_values[i] + '">' + option_text + '</option>';
							}
						}
					}

					var columns_selected = jQuery(
						'<select id="columns_selected" name="columns_selected[]" multiple size="8" style="width:200px" onchange="select_selected()">' +
						currently_select_option +
						'</select>'
					);

					var dialog_table = jQuery('<table style="width:410px"></table>');
					var dialog_table_row = dialog_table.append(jQuery('<tr></tr>'));
					dialog_table_row.append(jQuery('<td width="50%"></td>').append(columns_available));
					dialog_table_row.append(jQuery('<td width="50%"></td>').append(columns_selected));

					var dialog_text = jQuery('<div style="width:410px"></div>');
					var dialog = jQuery('<div></div>');

					dialog.append(dialog_text);
					dialog.append(dialog_table);

					jQuery(dialog).dialog(
						{
							dialogClass: 'wp-dialog no-close',
							title: 'Add column(s) to publication',
							modal: true,
							autoOpen: true,
							closeOnEscape: false,
							resizable: false,
							width: 'auto',
							buttons: {
								"OK": function () {
									var selected_columns = '';
									jQuery("#columns_selected option").each(
										function () {
											selected_columns += jQuery(this).val() + ',';
										}
									);
									if (selected_columns !== '') {
										selected_columns = selected_columns.slice(0, -1);
									}
									jQuery('#pub_column_names').val(selected_columns);
									jQuery(this).dialog('destroy').remove();
								},
								"Cancel": function () {
									jQuery(this).dialog('destroy').remove();
								}
							}
						}
					);

					// Remove selected columns from available columns
					for (let i = 0; i < currently_select_values.length; i++) {
						jQuery("#columns_available option[value='" + currently_select_values[i] + "']").remove();
					}
				}

				function format_cpt_columns() {
					if (!(Array.isArray(cpt_fields) && cpt_fields.length)) {
						alert("<?php 
        echo  __( 'To format columns you need to save your publication first', 'wp-data-access' ) ;
        ?>");
						return;
					}

					let pub_cpt_format = null;
					try {
						pub_cpt_format = JSON.parse(jQuery('#pub_cpt_format').val());
					} catch (e) {
						pub_cpt_format = null;
					}

					let dialog_table = jQuery('<table></table>');
					dialog_table.append(
						jQuery('<tr></tr>').append(
							jQuery('<th style="text-align:left;"><?php 
        echo  __( 'Field Name', 'wp-data-access' ) ;
        ?></th>'),
							jQuery('<th style="text-align:left;"><?php 
        echo  __( 'Field Label', 'wp-data-access' ) ;
        ?></th>'),
						)
					);

					for (let i=0; i<cpt_fields.length; i++) {
						let label = cpt_fields[i];
						try {
							label = pub_cpt_format.cpt_format.cpt_labels[cpt_fields[i]];
						} catch (e) {
							label = cpt_fields[i];
						}
						dialog_table.append(
							jQuery('<tr></tr>').append(
								jQuery('<td style="text-align:left;">' + cpt_fields[i] + '</td>'),
								jQuery('<td style="text-align:left;"><input type="text" class="cpt_label" name="' + cpt_fields[i] + '" value="' + label + '"></td>'),
							)
						);
					}

					let dialog_text = jQuery('<div></div>');
					let dialog = jQuery('<div id="define_cpt_labels"></div>');

					dialog.append(dialog_text);
					dialog.append(dialog_table);

					jQuery(dialog).dialog({
						dialogClass: 'wp-dialog no-close',
						title: 'Define field labels',
						modal: true,
						autoOpen: true,
						closeOnEscape: false,
						resizable: false,
						width: 'auto',
						buttons: {
							"OK": function () {
								// Create JSON from defined field labels
								var cpt_labels = {};
								jQuery("#define_cpt_labels input.cpt_label").each(
									function () {
										cpt_labels[jQuery(this).attr('name')] = jQuery(this).val();
									}
								);

								// Write JSON to column pub_format
								cpt_format = {
									"cpt_format": {
										"cpt_labels": cpt_labels
									}
								};
								jQuery('#pub_cpt_format').val(JSON.stringify(cpt_format));
								jQuery(this).dialog('destroy').remove();
							},
							"Cancel": function () {
								jQuery(this).dialog('destroy').remove();
							}
						}
					});
				}

				function format_columns() {
					if (!(Array.isArray(table_columns) && table_columns.length)) {
						alert("<?php 
        echo  __( 'To format columns you need to save your publication first', 'wp-data-access' ) ;
        ?>");
						return;
					}

					var pub_format_json_string = jQuery('#pub_format').val();

					var columns_labels = [];

					if (pub_format_json_string !== '') {
						// Use previously defined formatting
						var pub_format = JSON.parse(pub_format_json_string);
						if (typeof pub_format['pub_format']['column_labels'] !== 'undefined') {
							columns_labels = pub_format['pub_format']['column_labels'];
						}
					} else {
						// Get column labels from table settings
						columns_labels = <?php 
        echo  json_encode( $column_labels ) ;
        ?>;
					}

					var dialog_table = jQuery('<table></table>');
					dialog_table.append(
						jQuery('<tr></tr>').append(
							jQuery('<th style="text-align:left;"><?php 
        echo  __( 'Column Name', 'wp-data-access' ) ;
        ?></th>'),
							jQuery('<th style="text-align:left;"><?php 
        echo  __( 'Column Label', 'wp-data-access' ) ;
        ?></th>'),
						)
					);

					<?php 
        foreach ( $table_columns->get_table_columns() as $table_column ) {
            ?>
						columns_label = '<?php 
            echo  esc_attr( $table_column['column_name'] ) ;
            ?>';
						if (typeof columns_labels !== 'undefined') {
							if (columns_label in columns_labels) {
								columns_label = columns_labels[columns_label];
							}
						}
						dialog_table.append(
							jQuery('<tr></tr>').append(
								jQuery('<td style="text-align:left;"><?php 
            echo  esc_attr( $table_column['column_name'] ) ;
            ?></td>'),
								jQuery('<td style="text-align:left;"><input type="text" class="column_label" name="<?php 
            echo  esc_attr( $table_column['column_name'] ) ;
            ?>" value="' + columns_label + '"></td>'),
							)
						);
						<?php 
        }
        ?>

					var dialog_text = jQuery('<div></div>');
					var dialog = jQuery('<div id="define_column_labels"></div>');

					dialog.append(dialog_text);
					dialog.append(dialog_table);

					jQuery(dialog).dialog({
						dialogClass: 'wp-dialog no-close',
						title: 'Define column labels',
						modal: true,
						autoOpen: true,
						closeOnEscape: false,
						resizable: false,
						width: 'auto',
						buttons: {
							"OK": function () {
								// Create JSON from defined column labels
								var column_labels = {};
								jQuery('.column_label').each(
									function () {
										column_labels[jQuery(this).attr('name')] = jQuery(this).val();
									}
								);

								// Write JSON to column pub_format
								pub_format = {
									"pub_format": {
										"column_labels": column_labels
									}
								};
								jQuery('#pub_format').val(JSON.stringify(pub_format));
								jQuery(this).dialog('destroy').remove();
							},
							"Cancel": function () {
								jQuery(this).dialog('destroy').remove();
							}
						}
					});
				}

				function getQuery() {
					url = location.pathname + '?action=wpda_query_builder_open_sql';
					jQuery.ajax({
						method: 'POST',
						url: url,
						data: {
							wpda_wpnonce: "<?php 
        echo  esc_attr( wp_create_nonce( 'wpda-query-builder-' . WPDA::get_current_user_id() ) ) ;
        ?>",
							wpda_exclude: ""
						}
					}).done(
						function (msg) {
							if (!Array.isArray(msg.data)) {
								// Show queries
								list = jQuery("<ul/>");
								for (var queryName in msg.data) {
									dbs = msg.data[queryName].schema_name;
									qry = msg.data[queryName].query;

									query = jQuery(`
										<div class="wpda-query-select">
											<div class="wpda-query-select-title ui-widget-header">
												${queryName}
												<span class="fas fa-copy wpda-query-select-title-copy wpda_tooltip_left" title="Copy SQL"></span>
											</div>
											<div class="wpda-query-select-content">
												<textarea>${qry}</textarea>
											</div>
										</div>
									`);
									listitem = jQuery("<li/>").attr("data-dbs", dbs);
									listitem.append(query);

									list.append(listitem);
								}
								dialog = jQuery("<div class='wpda-query'/>").attr("title", "Select from Query Builder");
								dialog.append(list);
								dialog.dialog({
									modal: true,
									resizable: false,
									width: "700px"
								});
								jQuery(".wpda_tooltip_left").tooltip({
									tooltipClass: "wpda_tooltip_dashboard",
									position: { my: "right top", at: "right bottom" }
								});

								jQuery(".wpda-query-select-title-copy").on("click", function() {
									selectedDbs = jQuery(this).closest("li").data("dbs");
									selectedQuery = jQuery(this).closest("li").find("textarea").val();

									jQuery("#pub_schema_name").val(selectedDbs);
									jQuery("#pub_query").val(selectedQuery);

									jQuery(this).closest('.ui-dialog-content').dialog('close');
								});
							} else {
								// No queries found
							}
						}
					).fail(
						function (msg) {
							console.log("WP Data Access error (getSQLFromQueryBuilder):", msg);
						}
					);
				}

				function test_publication() {
					jQuery.ajax({
						type: "POST",
						url: "<?php 
        echo  admin_url( 'admin-ajax.php?action=wpda_test_publication' ) ;
        // phpcs:ignore WordPress.Security.EscapeOutput
        ?>",
						data: {
							wpnonce: "<?php 
        echo  esc_attr( wp_create_nonce( "wpda-publication-{$pub_id}" ) ) ;
        ?>",
							pub_id: "<?php 
        echo  esc_attr( $pub_id ) ;
        ?>"
						}
					}).done(
						function(html) {
							jQuery("body").append(html);
							jQuery('#data_publisher_test_container_<?php 
        echo  esc_html( $pub_id ) ;
        ?>').show();
							//publication_loaded = true;
						}
					);
				}

				function addOrderByColumn() {
					let index = 0;
					jQuery(".wpda_dp_orderby").each(function() {
						if (parseInt(jQuery(this).data("index"))>index) {
							index = parseInt(jQuery(this).data("index"));
						}
					});
					index++;
					jQuery("#pub_default_orderby").before(createOrderByLine(index));
				}

				function removeOrderByColumn(elem) {
					elem.parent().remove();
				}

				function createOrderByLine(index) {
					let selectedColumns = get_selected_columns();

					let options = '<option value=""></option>';
					for (let i=0; i<selectedColumns.length; i++) {
						options += `<option value="${i}">${selectedColumns[i]}</option>`;
					}
					let columns = `<select id="orderby${index}">${options}</select>`;

					let order = `<select id="order${index}"><option></option><option value="asc">Ascending</option><option value="desc">Descending</option></select>`;

					let icon = "";
					if (index===0) {
						icon = `<i class="fas fa-plus-circle wpda_tooltip" title="Add column" onclick="addOrderByColumn()"></i>`;
					} else {
						icon = `<i class="fas fa-minus-circle wpda_tooltip" title="Remove column" onclick="removeOrderByColumn(jQuery(this))"></i>`;
					}

					return `<div data-index="${index}" class="wpda_dp_orderby">${columns}${order}${icon}</div>`;
				}
			</script>
			<?php 
    }
    
    protected function show_shortcode( $pub_id )
    {
        // Show publication shortcode directly from Data Publisher main page
        $shortcode_enabled = 'on' === WPDA::get_option( WPDA::OPTION_PLUGIN_WPDATAACCESS_POST ) && 'on' === WPDA::get_option( WPDA::OPTION_PLUGIN_WPDATAACCESS_PAGE );
        ?>
			<div id="wpda_publication_<?php 
        echo  esc_attr( $pub_id ) ;
        ?>"
				 title="<?php 
        echo  __( 'Publication shortcode', 'wp-data-access' ) ;
        ?>"
				 style="display:none"
			>
				<p>
					Copy the shortcode below into your post or page to make this publications available on your website.
				</p>
				<p class="wpda_shortcode_text">
					<strong>
						[wpdataaccess pub_id="<?php 
        echo  esc_attr( $pub_id ) ;
        ?>"]
					</strong>
				</p>
				<p class="wpda_shortcode_buttons">
					<button class="button wpda_shortcode_clipboard wpda_shortcode_button"
							type="button"
							data-clipboard-text='[wpdataaccess pub_id="<?php 
        echo  esc_attr( $pub_id ) ;
        ?>"]'
							onclick="jQuery.notify('<?php 
        echo  __( 'Shortcode successfully copied to clipboard!' ) ;
        ?>','info')"
					>
						<?php 
        echo  __( 'Copy', 'wp-data-access' ) ;
        ?>
					</button>
					<button class="button button-primary wpda_shortcode_button"
							type="button"
							onclick="jQuery('.ui-dialog-content').dialog('close')"
					>
						<?php 
        echo  __( 'Close', 'wp-data-access' ) ;
        ?>
					</button>
				</p>
				<?php 
        
        if ( !$shortcode_enabled ) {
            ?>
					<p>
						Shortcode wpdataaccess is not enabled for all output types.
						<a href="<?php 
            echo  admin_url( 'options-general.php' ) ;
            // phpcs:ignore WordPress.Security.EscapeOutput
            ?>?page=wpdataaccess" class="wpda_shortcode_link">&raquo; Manage settings</a>
					</p>
					<?php 
        }
        
        ?>
			</div>
			<a href="javascript:void(0)"
			   class="button view wpda_tooltip"
			   title="<?php 
        echo  __( 'Get publication shortcode', 'wp-data-access' ) ;
        ?>"
			   onclick="jQuery('#wpda_publication_<?php 
        echo  esc_attr( $pub_id ) ;
        ?>').dialog()"
			>
				<span style="white-space:nowrap">
					<span class="material-icons wpda_icon_on_button">code</span>
					<?php 
        echo  __( 'Shortcode', 'wp-data-access' ) ;
        ?>
				</span>
			</a>
			<?php 
        WPDA::shortcode_popup();
    }
    
    public static function test_publication()
    {
        $pub_id = ( isset( $_REQUEST['pub_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['pub_id'] ) ) : null );
        $wp_nonce = ( isset( $_REQUEST['wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wpnonce'] ) ) : '' );
        // input var okay.
        $datatables_enabled = WPDA::get_option( WPDA::OPTION_BE_LOAD_DATATABLES ) === 'on';
        $datatables_responsive_enabled = WPDA::get_option( WPDA::OPTION_BE_LOAD_DATATABLES_RESPONSE ) === 'on';
        
        if ( !wp_verify_nonce( $wp_nonce, "wpda-publication-{$pub_id}" ) ) {
            $publication = '<strong>' . __( 'ERROR: Not authorized', 'wp-data-access' ) . '</strong>';
        } elseif ( null === $pub_id ) {
            $publication = '<strong>' . __( 'ERROR: Cannot test publication [wrong arguments]', 'wp-data-access' ) . '</strong>';
        } elseif ( !$datatables_enabled || !$datatables_responsive_enabled ) {
            $publication = '<strong>' . __( 'ERROR: Cannot test publication', 'wp-data-access' ) . '</strong><br/><br/>' . __( 'SOLUTION: Load jQuery DataTables: WP Data Access > Manage Plugin > Back-End Settings', 'wp-data-access' );
        } else {
            $wpda_data_tables = new WPDA_Data_Tables();
            $publication = $wpda_data_tables->show(
                $pub_id,
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            );
            // , '', '', '', true );
        }
        
        ob_start();
        ?>
			<div id="data_publisher_test_container_<?php 
        echo  esc_html( $pub_id ) ;
        ?>">
				<style>
					#data_publisher_test_header_<?php 
        echo  esc_html( $pub_id ) ;
        ?> {
						height: 30px;
						background-color: #ccc;
						padding: 10px;
						margin-bottom: 10px;
					}

					#data_publisher_test_header_<?php 
        echo  esc_html( $pub_id ) ;
        ?> span strong {
						padding-top: 10px;
						font-size: 14px;
						vertical-align: middle;
					}

					#data_publisher_test_container_<?php 
        echo  esc_html( $pub_id ) ;
        ?> {
						display: none;
						padding: 10px;
						position: absolute;
						top: 30px;
						left: 10px;
						color: black;
						overflow-y: auto;
						background-color: white;
						border: 1px solid #ccc;
						width: calc(100% - 100px);
						z-index: 9999;
					}

                    #data_publisher_test_container_<?php 
        echo  esc_html( $pub_id ) ;
        ?> thead input[type='search'],
                    #data_publisher_test_container_<?php 
        echo  esc_html( $pub_id ) ;
        ?> thead select,
                    #data_publisher_test_container_<?php 
        echo  esc_html( $pub_id ) ;
        ?> tfoot input[type='search'],
                    #data_publisher_test_container_<?php 
        echo  esc_html( $pub_id ) ;
        ?> tfoot select {
						padding-top: 5px;
                        padding-bottom: 5px;
                    }

                    #data_publisher_test_container_<?php 
        echo  esc_html( $pub_id ) ;
        ?> .dataTables_filter {
						height: 42px;
					}

					.dataTables_wrapper .dataTables_filter {
						height: 35px;
					}
				</style>
				<div id="data_publisher_test_header_<?php 
        echo  esc_attr( $pub_id ) ;
        ?>">
					<span><strong><?php 
        echo  __( 'Test Publication', 'wp-data-access' ) ;
        ?> (pub_id=<?php 
        echo  esc_attr( $pub_id ) ;
        ?>
							- <?php 
        echo  __( 'publication looks different on your website', 'wp-data-access' ) ;
        ?>)
						</strong></span>
					<span class="button" style="float:right;"
						  onclick="jQuery('#data_publisher_test_container_<?php 
        echo  esc_attr( $pub_id ) ;
        ?>').hide(); jQuery('#data_publisher_test_container_<?php 
        echo  esc_attr( $pub_id ) ;
        ?>').remove();">x</span><br/>
				</div>
				<?php 
        echo  $publication ;
        // phpcs:ignore WordPress.Security.EscapeOutput
        ?>
			</div>
			<script type='text/javascript'>
				jQuery("#data_publisher_test_container_<?php 
        echo  esc_attr( $pub_id ) ;
        ?>").appendTo("#wpbody-content");
			</script>
			<?php 
        echo  ob_get_clean() ;
        // phpcs:ignore WordPress.Security.EscapeOutput
        wp_die();
    }

}