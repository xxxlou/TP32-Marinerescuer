<?php

$options  = get_site_option('freehtml5map_options');
$update   = false;
$show_notifications = get_option('freehtml5map_notifications', 1);




if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'new':
            $type      = intval($_REQUEST['map_type']);
            $name      = sanitize_text_field($_REQUEST['name']);
            $defaults  = free_map_plugin_map_defaults($name,$type);

            if (is_array($defaults)) {
                $options[] = $defaults;
                $update    = true;
            }
            break;
        case 'delete':
            free_map_plugin_delete_action();
            break;
    }
}

if ($update) update_site_option('freehtml5map_options',$options);

function free_map_plugin_delete_action() {
    $options  = get_site_option('freehtml5map_options');
    $map_id = intval($_REQUEST['map_id']);
    if ( ! isset($options[$map_id])) {
        echo '<script>document.location.href="?page=free-map-plugin-maps"</script>';
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        check_admin_referer('delete');
        unset($options[$map_id]);
        update_site_option('freehtml5map_options', $options);
        echo '<script>document.location.href="?page=free-map-plugin-maps"</script>';
        exit;
    } else {
?>
<form method="post">
<?php echo wp_nonce_field('delete'); ?>
<input type="hidden" name="<?php echo $map_id ?>">
<h1><?php _e('Delete map', 'html5-maps') ?></h1>
<p><?php echo sprintf(__('You are going to delete following map: <b>%s</b>.', 'html5-maps'), $options[$map_id]['name']) ?></p>
<p><?php _e('<b style="color: red">Attention!</b> All settings for the map will be deleted permanently!', 'html5-maps') ?></p>
<p><?php _e('Are you sure?', 'html5-maps') ?></p>
<br><br><br><br>
<a class="button button-primary" href="?page=free-map-plugin-maps"><?php _e('No, return back', 'html5-maps'); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<button class="button deletion"><?php _e('Yes, delete', 'html5-maps') ?></button>
</form>
<?php
    exit;
    }
}

class Map_List_Table extends WP_List_Table {

    public function prepare_items()
    {
        $columns  = $this->get_columns();
        $hidden   = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data     = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    public function get_columns()
    {
        $columns = array(
            'checkbox'  => '<input type="checkbox" class="maps_toggle" autocomplete="off" />',
            'name'      => __( 'Name', 'html5-maps' ),
            'type'      => __( 'Map', 'html5-maps' ),
            'shortcode' => __( 'Shortcode', 'html5-maps' ),
            'edit'      => __( 'Edit', 'html5-maps' ),
        );

        return $columns;
    }

    public function get_hidden_columns()
    {
        return array();
    }

    public function get_sortable_columns()
    {
        return array('name' => array('name', false));
    }

    private function table_data()
    {

        $data      = array();
        $options   = get_site_option('freehtml5map_options');

        if (is_array($options) && count($options)) {
            foreach ($options as $map_id => $map_data) {

                $data[] = array(
                                'id'        => $map_id,
                                'name'      => $map_data['name'],
                                'type'      => isset($map_data['type']) ? $map_data['type'] : '',
                                'shortcode' => '[freehtml5map id="'.$map_id.'"]',
                                'edit'      => '<div style="float: left"><a href="admin.php?page=free-map-plugin-options&map_id='.$map_id.'">'.__( 'Map settings', 'html5-maps' ).'</a><br />
                                                <a href="admin.php?page=free-map-plugin-states&map_id='.$map_id.'">'.__( 'Map detailed settings', 'html5-maps' ).'</a><br />
                                                <a href="admin.php?page=free-map-plugin-view&map_id='.$map_id.'">'.__( 'Preview', 'html5-maps' ).'</a><br /></div>
                                                <div style="float: right; padding-right: 20px;">
                                                <a href="admin.php?page=free-map-plugin-maps&action=delete&map_id='.$map_id.'" class="delete" style="color:#FF0000">'.__( 'Delete', 'html5-maps' ).'</a>
                                                </div>
                                                ',
                                );
            }
        }

        return $data;
    }

    public function column_default( $item, $column_name )
    {

        switch( $column_name ) {
            case 'checkbox':
                echo '&nbsp;<input type="checkbox" value="'.$item['id'].'" class="map_checkbox" autocomplete="off" />';
                break;
            case 'name':
            case 'type':
            case 'shortcode':
            case 'edit':
                return $item[ $column_name ];
        }
    }

    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'name';
        $order   = 'asc';

        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }

        $result = strcmp( $a[$orderby], $b[$orderby] );

        if($order === 'asc')
        {
            return $result;
        }

        return -$result;
    }

}


$listtable = new Map_List_Table();
$listtable->prepare_items();

?>

    <?php if (isset($_REQUEST['msg']) && !isset($_REQUEST['action'])) { ?>
        <div class="error"><p><?php _e( 'You need to create your first map. Select a map from the drop-down list below and click "Add a new map"', 'html5-maps' ); ?></p></div>
    <?php } ?>
    <style>
        .column-shortcode {
            min-width: 150px
        }
        .column-edit {
            width: 220px;
        }

        .fhmn {
          color: #0c5460;
          background-color: #d1ecf1;
          border: 1px solid #bee5eb;
          position: relative;
          padding: .75rem 2.5rem .75rem 1.25rem;
          margin-bottom: 1rem;
          max-width: 800px;
        }

        .fhmn-actions {
          display: flex;
          justify-content: space-between;
        }

        .fhmn-text {
          font-size: 1rem;
        }
        .fhmn-title {
          font-size: 1.2rem;
          font-weight: 500;
        }

        .fhmn-close {
          position: absolute;
          top: 0;
          right: 0;
          padding: .75rem 1.25rem;
          font-size: 1rem;
          font-weight: 700;
          line-height: 1;
          color: #000;
          text-shadow: 0 1px 0 #fff;
          opacity: .5;
          transition: opacity .15s ease-in-out,background-color .15s ease-in-out;
          cursor: pointer;
        }
        .fhmn-close:hover {
          opacity: 1;
        }

        .fhmn-btn {
          display: inline-block;
          font-weight: 400;
          text-align: center;
          white-space: nowrap;
          vertical-align: middle;
          -webkit-user-select: none;
          -moz-user-select: none;
          -ms-user-select: none;
          user-select: none;
          border: 1px solid transparent;
          padding: .375rem .75rem;
          font-size: 1rem;
          line-height: 1.5;
          border-radius: .25rem;
          transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
          color: #fff;
          border-color: #28a745;
          text-decoration: none;
          cursor: pointer;
          margin-right: 20px;
        }
        .fhmn-btn-primary {
          background-color: #28a745;
        }
        .fhmn-btn-primary:hover {
          background-color: #218838;
          border-color: #1e7e34;
          color: #fff;
        }
        .fhmn-btn-secondary {
          background-color: transparent;
          color: #218838;
        }
        .fhmn-btn-secondary:hover {
          background-color: #218838;
          border-color: #1e7e34;
          color: #fff;
        }
        .fhmn-btn:focus {
          box-shadow: 0 0 0 0.2rem rgba(40,167,69,.5);
          color: #fff;
        }

        .fhmn-info {
          color: #383d41;
          display: flex;
          align-items: flex-end;
          font-size: .75rem;
          opacity: .7;
        }

        .fhmn-hide {
          opacity: .9;
          text-decoration: underline;
          transition: opacity .2s ease-in-out;
          cursor: pointer;
        }

        .fhmn-hide:hover {
          opacity: 1;
        }

    </style>
    <div class="wrap free-html5-map full">
        <div id="icon-users" class="icon32"></div>
        <? if($show_notifications && FM_SHOW_NOTIFICATIONS_AFTER_UPDATE): ?>
          <div class="fhmn">
            <span class="fhmn-close">&#10006;</span>
            <p class="fhmn-title">
              Christmas sale!
            </p>
            <p class="fhmn-text">
              Get premium map plugins with 25% discount!<br>
              Use the promo code CHRISTMAS at checkout to get extra 25% off!
            </p>
            <ul class="fhmn-actions">
              <li><a href="https://www.fla-shop.com/wordpressmaps.php?utm_source=html5-maps-plugin&utm_medium=dashboard&utm_campaign=notice" class="fhmn-btn-primary fhmn-btn"><?php echo __( 'Shop now', 'html5-maps' ); ?></a></li>
              <li class="fhmn-info"><span class="fhmn-hide"><?php echo __( 'Press here', 'html5-maps' ); ?></span>&nbsp;<?php echo __( 'to hide this notification', 'html5-maps' ); ?></li>
            </ul>
          </div>
        <? endif; ?>
        <h2><?php echo __( 'HTML5 Maps', 'html5-maps' ); ?></h2>

        <div class="left-block">
            <form name="action_form" action="" method="POST" enctype="multipart/form-data" class="html5-map full" style="margin-bottom: -20px; margin-top: 20px">
                <input type="hidden" name="action" value="new" />
                <input type="hidden" name="maps" value="" />

                <fieldset>
                    <legend><?php _e( 'Add a new map', 'html5-maps' ) ?></legend>
                    <span><?php _e( 'New map name:', 'html5-maps' ) ?></span>
                    <input type="text" name="name" value="<?php _e( 'New map', 'html5-maps' ) ?>" />

                    <?php

                       $types = free_map_get_map_types();

                    ?>

                    <select name="map_type" class="chosen-select">
                        <option value=""><?php _e( 'Please select the map', 'html5-maps' ) ?></option>

                        <?php

                            $last_group = ''; $n=0;
                            foreach($types as $id => $type) {

                                $n++;

                                if ($type->group!=$last_group) {
                                    if ($n>1) { echo '</optgroup>'; }
                                    echo '<optgroup label="'.$type->group.'">';
                                }

                                $last_group = $type->group;

                                $type->name_html        = str_replace('+','%20',urlencode($type->name_html));
                                $type->onselect_content = str_replace('+','%20',urlencode($type->onselect_content));

                        ?>
                            <option value="<?php echo $id; ?>" data-name-html="<?php echo $type->name_html; ?>" data-onselect-content="<?php echo $type->onselect_content; ?>" data-license="<?php echo $type->license; ?>"><?php echo $type->name; ?></option>

                        <?php } ?>

                    </select>

                    <input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Add a new map', 'html5-maps' ); ?>" />

                    <div class="onselect_content"></div>

                </fieldset>
            </form>
            <?php $listtable->display(); ?>
            <form name="action_form2" action="" method="POST" enctype="multipart/form-data" class="html5-map full">
                <fieldset>
                    <legend><?php _e( 'Export/import Map Settings', 'html5-maps' ) ?></legend>
                    <p><?php _e( 'To export please select a checkbox of one or more maps, and press Export button', 'html5-maps' ); ?></p>
                    <input type="button" class="button button-secondary export" value="<?php esc_attr_e( 'Export', 'html5-maps' ); ?>"/>
                    <input type="button" class="button button-secondary import" value="<?php esc_attr_e( 'Import', 'html5-maps' ); ?>" disabled />

                    <p>
                        <?php _e( 'The Import function is only available in <a href="https://www.fla-shop.com/wordpressmaps.php">Premium plugins</a> ', 'html5-maps' ) ?>
                    </p>

                </fieldset>

            </form>

        </div>

        <div class="qanner">
            <a href="https://www.fla-shop.com/wordpressmaps.php?utm_source=html5-maps-plugin&utm_medium=dashboard&utm_campaign=banner" target="_blank"><img src="<?php echo WP_PLUGIN_URL.'/html5-maps/static/' ?>html5maps_img.png<?php echo "?r=".time();?>" border="0" width="161" height="601"></a>
        </div>

        <div class="clear"></div>

    </div>


    <script type="text/javascript">
        jQuery(document).ready(function($) {

        	  //notification start

        	  $('.fhmn-close').click(function (e) {
              e.preventDefault()
              $('.fhmn').fadeOut()
	          })

        	  $('.fhmn-hide').click(function (e) {
              e.preventDefault()
		          var data = {
			          action: 'free_map_notifications_hide',
			          nonce: '<?php echo wp_create_nonce('free_map_notifications_nonce') ?>'
		          };

		          $.post( ajaxurl, data, function(response) {
			          if ( response === 'ok') {
				          $('.fhmn').fadeOut()
				          $('.fhmn-badge').fadeOut()
                } else {
			          	console.log(response)
                }
		          })
	          })


	        //notification end

            //$('a.delete').click(function() {
            //    if (confirm('<?php //echo __( 'Remove the map?\nAttention! All settings for the map will be deleted permanently!', 'html5-maps' ); ?>//')) {
            //        return true;
            //    } else {
            //        return false;
            //    }
            //});

            $('.maps_toggle').click(function() {
                $('.map_checkbox,.maps_toggle').not($(this)).each(function() {
                    $(this).prop('checked', !($(this).is(':checked')));
                });
            });

            $('input.export').click(function() {
                if (!$('.map_checkbox:checked').size()) {
                    alert('<?php _e('Select maps you want to export first!', 'html5-maps') ?>');
                    return false;
                }
                $('input[name=action]').val('free_map_export');

                var maps = '';
                $('.map_checkbox:checked').each(function() {
                    if (maps!='') maps+=',';
                    maps+=$(this).val();
                });

                $('input[name=maps]').val(maps);

                $('form[name=action_form]').submit();
                return false;
            });


            var onMapSelect = function(e) {

                var content = $(this).find('option:selected').data('onselect-content');
                content = content ? decodeURIComponent(content) : '';
                var license = $(this).find('option:selected').data('license');

                if (license=="free") {
                    $('.button-primary').prop("disabled",false);
                } else {
                    $('.button-primary').prop("disabled",true);
                }

                $('.onselect_content').html(content);

            };
            $('.chosen-select').chosen().change(onMapSelect);
            onMapSelect();


        });
    </script>

<?php

?>
