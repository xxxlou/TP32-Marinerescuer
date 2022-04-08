<?php
/*
Plugin Name: HTML5 Maps
Plugin URI: https://www.fla-shop.com
Description: High-quality map plugin for WordPress. The map depicts regions (states, provinces, counties etc.) and features color, landing page and popup customization.
Version: 1.7.0.6
Author: Fla-shop.com
Author URI: https://www.fla-shop.com
Text Domain: html5-maps
Domain Path: /languages
License: GPLv2 or later
*/

define('FM_SHOW_NOTIFICATIONS_AFTER_UPDATE', false);

if (isset($_REQUEST['action']) && $_REQUEST['action']=='free_map_export') { free_map_export(); }

add_action('plugins_loaded', 'free_map_plugin_load_domain' );

function free_map_plugin_load_domain() {
    load_plugin_textdomain( 'html5-maps', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}

add_action('admin_menu', 'free_map_plugin_menu');

function free_map_plugin_menu() {

    add_menu_page(__('HTML5 Maps','html5-maps'), __('HTML5 Maps','html5-maps'), 'manage_options', 'free-map-plugin-main', 'free_map_plugin_maps' );

    add_submenu_page('free-map-plugin-main', __('Maps','html5-maps'), __('Maps','html5-maps'), 'manage_options', 'free-map-plugin-maps', 'free_map_plugin_maps');

    add_submenu_page('free-map-plugin-main', __('Main settings','html5-maps'), __('Main settings','html5-maps'), 'manage_options', 'free-map-plugin-options', 'free_map_plugin_options');
    add_submenu_page('free-map-plugin-main', __('Detailed settings','html5-maps'), __('Detailed settings','html5-maps'), 'manage_options', 'free-map-plugin-states', 'free_map_plugin_states');
    add_submenu_page('free-map-plugin-main', __('Map Preview','html5-maps'), __('Map Preview','html5-maps'), 'manage_options', 'free-map-plugin-view', 'free_map_plugin_view');
    add_submenu_page('free-map-plugin-main', __('Premium benefits','html5-maps'), __('Premium benefits','html5-maps'), 'manage_options', 'free-map-plugin-prem', 'free_map_plugin_premium_demo');

    remove_submenu_page('free-map-plugin-main','free-map-plugin-main');

}

function free_map_plugin_options() {
    include('editmainconfig.php');
}

function free_map_plugin_states() {
    include('editstatesconfig.php');
}

function free_map_plugin_maps() {
    include('mapslist.php');
}

function free_map_plugin_nav_tabs($page, $map_id = 0)
{
?>
<h2 class="nav-tab-wrapper">
    <a href="?page=free-map-plugin-options&map_id=<?php echo $map_id ?>" class="nav-tab <?php echo $page == 'options' ? 'nav-tab-active' : '' ?>"><?php _e('General settings', 'html5-maps') ?></a>
    <a href="?page=free-map-plugin-states&map_id=<?php echo $map_id ?>" class="nav-tab <?php echo $page == 'states' ? 'nav-tab-active' : '' ?>"><?php _e('Detailed settings', 'html5-maps') ?></a>
    <a href="?page=free-map-plugin-view&map_id=<?php echo $map_id ?>" class="nav-tab <?php echo $page == 'view' ? 'nav-tab-active' : '' ?>"><?php _e('Preview', 'html5-maps') ?></a>
    <a href="?page=free-map-plugin-prem" class="nav-tab <?php echo $page == 'prem' ? 'nav-tab-active' : '' ?> nav-tab-free-map-premium"><?php _e('Premium benefits', 'html5-maps') ?></a>
</h2>
<?php
}

function free_map_plugin_view() {

    free_map_check_map_exists();

    $options = get_site_option('freehtml5map_options');
    $option_keys = is_array($options) ? array_keys($options) : array();
    $map_id  = (isset($_REQUEST['map_id'])) ? intval($_REQUEST['map_id']) : array_shift($option_keys) ;

?>
<div class="wrap">
    <div style="clear: both"></div>

    <h2><?php _e('Map Preview', 'html5-maps') ?></h2>

    <script type="text/javascript">
        jQuery(function(){

            jQuery('select[name=map_id]').change(function() {
                location.href='admin.php?page=free-map-plugin-view&map_id='+jQuery(this).val();
            });

        });
    </script>
    <form method="POST" class="free-html5-map main">
    <span class="title" style="width: 200px;"><?php _e( 'Map:', 'html5-maps' ) ?> </span>
    <select name="map_id" style="width: 285px;">
        <?php foreach($options as $id => $map_data) { ?>
            <option value="<?php echo $id; ?>" <?php echo ($id==$map_id)?'selected':'';?>><?php echo "$map_data[name] ($map_data[type])"; ?></option>
        <?php } ?>
    </select>
    <span class="tipsy-q" original-title="<?php esc_attr_e( 'The map', 'html5-maps' ) ?>">[?]</span>
    <a href="admin.php?page=free-map-plugin-maps" class="page-title-action" style="top: 2px"><?php
    _e('Maps list', 'wp-l10n-domain') ?></a>
    <br /><br />
    </form>
    <style type="text/css">
        .html5-map-bold {font-weight: bold}
    </style>

<?php

    free_map_plugin_nav_tabs('view', $map_id);

    echo '<p>'.sprintf(__('Use shortcode %s for install this map', 'html5-maps'), '<span class="html5-map-bold">[freehtml5map id="'.$map_id.'"]</span>').'</p>';

    echo do_shortcode('<div style="width: 99%">[freehtml5map id="'.$map_id.'"]</div>');
    $map_data = isset($options[$map_id]) ? $options[$map_id] : array();
    if ($map_data) {
        $maps = (array)free_map_get_map_types();
        $def = null;
        foreach ($maps as $map) {
            if ($map->name == $map_data['type']) {
                $def = $map;
            }
        }
        if ($def and $def->onselect_content) {
            echo "<br /><br /><div class='html5-map'><div class='onselect_content'>" . $def->onselect_content.'</div></div>';
        }
    }
    echo "</div>";

}

add_action('admin_init','free_map_plugin_scripts');

function free_map_plugin_scripts(){



    if ( is_admin() ){

        wp_register_style('jquery-tipsy', plugins_url('/static/css/tipsy.css', __FILE__));
        wp_enqueue_style('jquery-tipsy');
        wp_register_style('free-html5-mapadm', plugins_url('/static/css/mapadm.css', __FILE__));
        wp_enqueue_style('free-html5-mapadm');
        wp_enqueue_style('farbtastic');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('farbtastic');
        wp_enqueue_script('tiny_mce');
        wp_register_script('jquery-tipsy', plugins_url('/static/js/jquery.tipsy.js', __FILE__));
        wp_enqueue_script('jquery-tipsy');

        // Chosen
        wp_register_script('chosen.jquery', plugins_url('/static/js/chosen/chosen.jquery.js', __FILE__));
        wp_enqueue_script('chosen.jquery');

        wp_register_script('chosen.proto', plugins_url('/static/js/chosen/chosen.proto.min.js', __FILE__));
        wp_enqueue_script('chosen.proto');

        wp_register_style('chosen', plugins_url('/static/js/chosen/chosen.min.css', __FILE__));
        wp_enqueue_style('chosen');

    }
    else {

        $options = get_site_option('freehtml5map_options');

        wp_register_style('free-html5-map-style', plugins_url('/static/css/map.css', __FILE__));
        wp_enqueue_style('free-html5-map-style');
        wp_register_script('raphael', plugins_url('/static/js/raphael.min.js', __FILE__));
        wp_enqueue_script('raphael');


        $path = isset($options[0]['data_file']) ? $options[0]['data_file'] : $options[0]['defaultDataFile'];
        $path = str_replace('http://cdn.html5maps.com', '//cdn.html5maps.com', $path);
        wp_register_script('free-html5-map-js', $path);
        wp_enqueue_script('free-html5-map-js');

        wp_enqueue_script('jquery');

    }
}

add_action('wp_enqueue_scripts', 'free_map_plugin_scripts_method');

function free_map_plugin_scripts_method() {
    wp_enqueue_script('jquery');
}


add_shortcode( 'freehtml5map', 'free_map_plugin_content' );

function free_map_plugin_content($atts, $content) {

    $dir               = WP_PLUGIN_URL.'/html5-maps/static/';
    $siteURL           = get_site_url();
    $options           = get_site_option('freehtml5map_options');
    $option_keys       = is_array($options) ? array_keys($options) : array();

    if (isset($atts['id'])) {
        $map_id  = intval($atts['id']);
        $options = $options[$map_id];
    } else {
        $map_id  = array_shift($option_keys);
        $options = array_shift($options);
    }

    static $count = 0;

    $isResponsive      = $options['isResponsive'];
    $stateInfoArea     = $options['statesInfoArea'];
    $respInfo          = $isResponsive ? ' htmlMapResponsive' : '';
    $popupNameColor    = $options['popupNameColor'];
    $popupNameFontSize = $options['popupNameFontSize'].'px';

    $style             = (!empty($options['maxWidth']) && $isResponsive) ? 'max-width:'.intval($options['maxWidth']).'px' : '';

    $path_js           = (isset($options['df_type']) ANd $options['df_type']==1) ? $options['data_file'] : $options['defaultDataFile'];
    $path_js           = str_replace('http://cdn.html5maps.com', '//cdn.html5maps.com', $path_js);

    $mapInit = "
        <!-- start Fla-shop.com HTML5 Map -->	
        <div class='freeHtmlMap$stateInfoArea$respInfo' style='$style'>
        <div id='map-container-{$count}' class='freeHtmlMapContainer' data-map-variable='map{$count}'></div>
            <link href='{$dir}css/map.css' rel='stylesheet'>
            <style>
                body .fm-tooltip {
                    color: $popupNameColor;
                    font-size: $popupNameFontSize;
                }
            </style>
            <script src='//cdn.html5maps.com/3d_party/raphael.min.js'></script>
            <script src='{$siteURL}/index.php?freemap_js_data=true&map_id=$map_id&r=".rand(11111,99999)."'></script>
            <script src='$path_js'></script>
            <script>
                var map{$count} = new FlaMap(map_cfg);
                map{$count}.drawOnDomReady('map-container-{$count}');
                map{$count}.on('click', function(ev, sid, map) {
                jQuery('#freeHtmlMapStateInfo{$count}').html('');
                var link     = map.mapConfig.map_data[sid]['link'];
                if (link == '#info') {
                    var id = map.mapConfig.map_data[sid]['id'];
                    jQuery('#freeHtmlMapStateInfo{$count}').html('". __('Loading...', 'html5-maps') ."');
                    jQuery.ajax({
                        type: 'POST',
                        url: '{$siteURL}/index.php?freemap_get_state_info='+id+'&map_id={$map_id}',
                        success: function(data, textStatus, jqXHR){
                            jQuery('#freeHtmlMapStateInfo{$count}').html(data);
                        },
                        dataType: 'text'
                    });
                }

            });
            </script>
            <div id='freeHtmlMapStateInfo{$count}' class='freeHtmlMapStateInfo'></div>
            </div>
            <div style='clear: both'></div>
            <!-- end HTML5 Map -->
    ";

    $count++;

    $mapInit = preg_replace('/\s+/', ' ', $mapInit);
    return $mapInit;
}


$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'free_map_plugin_settings_link' );

function free_map_plugin_settings_link($links) {
    $settings_link = '<a href="admin.php?page=free-map-plugin-options">'.__('Settings', 'free-map-plugin-main').'</a>';
    array_push($links, $settings_link);
    return $links;
}


add_action( 'parse_request', 'free_map_plugin_wp_request' );

function free_map_plugin_wp_request( $wp ) {

    if (isset($_REQUEST['freemap_js_data']) or isset($_REQUEST['freemap_get_state_info'])) {
        $map_id  = intval($_REQUEST['map_id']);
        $options = get_site_option('freehtml5map_options');
        $options = $options[$map_id];
        $options['map_data'] = htmlspecialchars_decode($options['map_data']);
    }


    if( isset($_GET['freemap_js_data']) ) {

        $data = json_decode($options['map_data'], true);

        foreach ($data as &$d)
        {
            if (isset($d['comment']) AND $d['comment'])
                $d['comment'] = do_shortcode($d['comment']);
            $d['link'] = strpos($d['link'], 'javascript:') === 0 ? '#info' : $d['link'];
        }

        unset($d);
        $options['map_data'] = json_encode($data);

        header( 'Content-Type: application/javascript' );
       ?>

        var	map_cfg = {

        <?php if(!$options['isResponsive']) { ?>
        mapWidth		: <?php echo $options['mapWidth']; ?>,
        mapHeight		: <?php echo $options['mapHeight']; ?>,
        <?php } else { ?>
            mapWidth		: 0,
        <?php } ?>

        shadowWidth		: <?php echo $options['shadowWidth']; ?>,
        shadowOpacity		: <?php echo $options['shadowOpacity']; ?>,
        shadowColor		: "<?php echo $options['shadowColor']; ?>",
        shadowX			: <?php echo $options['shadowX']; ?>,
        shadowY			: <?php echo $options['shadowY']; ?>,

        iPhoneLink		: <?php echo $options['iPhoneLink']; ?>,

        isNewWindow		: <?php echo $options['isNewWindow']; ?>,

        borderColor		: "<?php echo $options['borderColor']; ?>",
        borderColorOver		: "<?php echo $options['borderColorOver']; ?>",

        nameColor		: "<?php echo $options['nameColor']; ?>",
        popupNameColor		: "<?php echo $options['popupNameColor']; ?>",
        //nameFontSize		: "<?php echo $options['nameFontSize'].'px'; ?>",
        popupNameFontSize	: "<?php echo $options['popupNameFontSize'].'px'; ?>",
        nameFontWeight		: "<?php echo $options['nameFontWeight']; ?>",

        overDelay		: <?php echo $options['overDelay']; ?>,
        nameStroke		: <?php echo $options['nameStroke']?'true':'false'; ?>,
        nameStrokeColor		: "<?php echo $options['nameStrokeColor']; ?>",
        map_data        : <?php echo $options['map_data']; ?>
        }

        <?php

        exit;
    }

    if(isset($_GET['freemap_get_state_info'])) {
        $stateId = (int) $_GET['freemap_get_state_info'];

        $info = $options['state_info'][$stateId];
        $info = nl2br($info);
        echo apply_filters('the_content',$info);

        exit;

    }
}


function free_map_plugin_map_defaults($name='New map',$type=0) {

    $type = free_map_get_map_types($type);

    $initialStatesPath = dirname(__FILE__).'/static/settings/'.$type->defaultSettings;

    if (!file_exists($initialStatesPath)) {
        echo '<div class="error"><p>'.sprintf(__( 'Settings file not found for map of %s', 'html5-maps' ), $type->name).'</p></div>';
        return false;
    }

    $map_data = file_get_contents($initialStatesPath);

    $defaults = array(
                        'name'              => $name,
                        'type'              => "",
                        'map_data'          => $map_data,
                        'mapWidth'          => 500,
                        'mapHeight'         => 400,
                        'maxWidth'          => 780,
                        'shadowWidth'       => 1.5,
                        'shadowOpacity'     => 0.3,
                        'shadowColor'       => "black",
                        'shadowX'           => 0,
                        'shadowY'           => 0,
                        'iPhoneLink'        => "true",
                        'isNewWindow'       => "false",
                        'borderColor'       => "#ffffff",
                        'borderColorOver'   => "#ffffff",
                        'nameColor'         => "#ffffff",
                        'popupNameColor'    => "#000000",
                        'nameFontSize'      => "10",
                        'popupNameFontSize' => "20",
                        'nameFontWeight'    => "bold",
                        'overDelay'         => 300,
                        'statesInfoArea'    => "bottom",
                        'isResponsive'      => "1",
                        'nameStroke'        => true,
                        'nameStrokeColor'   => "#000000",
                        'defaultDataFile'   => "",
                    );


    $type->type = $type->name;
    $type->name = $name;
    $defaults   = wp_parse_args( (array)$type, $defaults );

    $map_data   = json_decode($map_data);
    $count      = count((array)$map_data);

    for($i = 1; $i <= $count; $i++) {
        $defaults['state_info'][$i] = '';
    }

    return $defaults;
}


register_activation_hook( __FILE__, 'free_map_plugin_activation' );

function free_map_plugin_activation() {

    $options = get_site_option('freehtml5map_options', array());
    add_site_option('freehtml5map_options', $options);

    add_option('freehtml5map_notifications',0);

}

register_deactivation_hook( __FILE__, 'free_map_plugin_deactivation' );

function free_map_plugin_deactivation() {

}

register_uninstall_hook( __FILE__, 'free_map_plugin_uninstall' );

function free_map_plugin_uninstall() {
    delete_site_option('freehtml5map_options');
    delete_option('freehtml5map_notifications');
}

add_filter('widget_text', 'do_shortcode');

function free_map_export() {
    $maps    = explode(',',sanitize_text_field($_REQUEST['maps']));
    $options = get_site_option('freehtml5map_options');

    foreach($options as $map_id => $option) {
        if (!in_array($map_id,$maps)) {
            unset($options[$map_id]);
        }
    }

    if (count($options)>0) {
        $options = json_encode($options);
        $options = htmlspecialchars_decode($options);

        header($_SERVER["SERVER_PROTOCOL"] . ' 200 OK');
        header('Content-Type: text/json');
        header('Content-Length: ' . (strlen($options)));
        header('Connection: close');
        header('Content-Disposition: attachment; filename="maps.json";');
        echo $options;

        exit();
    }

}

function free_map_check_map_exists($redirect=true) {

    $options  = get_site_option('freehtml5map_options');
    $exists   = (is_array($options) && count($options));

    if ($redirect && !$exists) {

        echo '<script type="text/javascript">location.href = "admin.php?page=free-map-plugin-maps&msg=1";</script>';
        exit();

    } else {
        return $exists;
    }

}

function free_map_get_map_types($id='') {

    $types = dirname(__FILE__).'/static/map_types.json';
    $hwnd  = fopen($types,'r');
    $types = fread($hwnd,filesize($types)); fclose($hwnd);
    $types = json_decode($types);

    if (empty($id)) {
        return $types;
    } else {
        return $types->{$id};
    }

}

function free_map_get_pb_maps() {

    $data = file_get_contents(dirname(__FILE__).'/static/pb.json');
    $data = json_decode($data);

    return $data;

}

function free_map_plugin_premium_demo() {
?>
<div class="wrap free-html5-map">
    <div style="clear: both"></div>

    <h2><?php _e('Premium benefits', 'html5-maps') ?></h2>

<?php

    free_map_plugin_nav_tabs('prem');
    include('pb.php');
?>
</div>
<?php

}

add_action( 'upgrader_process_complete', 'free_map_upgrade_completed', 10, 2 );

function free_map_upgrade_completed( $upgrader_object, $options ) {


	// The path to our plugin's main file
	$our_plugin = plugin_basename( __FILE__ );

	// If an update has taken place and the updated type is plugins and the plugins element exists
	if( $options['action'] === 'update' && $options['type'] === 'plugin' && isset( $options['plugins'] ) ) {

		// Iterate through the plugins being updated and check if ours is there
		foreach( $options['plugins'] as $plugin ) {

			if( $plugin == $our_plugin && FM_SHOW_NOTIFICATIONS_AFTER_UPDATE ) {
				// Set a transient to record that our plugin has just been updated
				update_option('freehtml5map_notifications', 1);
			}
		}
	}
}


add_action( 'admin_menu', 'free_map_notifications_menu_bubble' );

function free_map_notifications_menu_bubble() {
  global $menu;
  $show_notifications = get_option('freehtml5map_notifications', 1);

  if ( $show_notifications && FM_SHOW_NOTIFICATIONS_AFTER_UPDATE ) {

    foreach ( $menu as $key => $value ) {

      if ( $menu[$key][2] == 'free-map-plugin-main' ) {

        $menu[$key][0] .= ' ' . '<span class="update-plugins fhmn-badge">1</span>' . '';

        return;
      }
    }
  }
}

add_action( 'wp_ajax_free_map_notifications_hide', 'free_map_notifications_hide_callback' );

function free_map_notifications_hide_callback() {

  check_ajax_referer('free_map_notifications_nonce', 'nonce');

  $update_result = update_option('freehtml5map_notifications', 0);

  if ($update_result) {
    echo 'ok';
  } else {
    echo 'error';
  }

	wp_die();
}
