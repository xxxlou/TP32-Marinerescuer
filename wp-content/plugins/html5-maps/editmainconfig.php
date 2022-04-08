<?php

free_map_check_map_exists();

$options = get_site_option('freehtml5map_options');
$option_keys = is_array($options) ? array_keys($options) : array();
$map_id  = (isset($_REQUEST['map_id'])) ? intval($_REQUEST['map_id']) : array_shift($option_keys) ;

if((isset($_POST['act_type']) && $_POST['act_type'] == 'free_map_plugin_main_save') && current_user_can('manage_options')) {
    check_admin_referer('free_map_plugin_main_save');
    foreach($_REQUEST['options'] as $key => $value) { $_REQUEST['options'][$key] = sanitize_text_field(stripcslashes($value)); }

    $options[$map_id] = wp_parse_args($_REQUEST['options'], $options[$map_id]);
    update_site_option('freehtml5map_options', $options);

}


echo "<h2>" . __( 'HTML5 Map Config', 'html5-maps' ) . "</h2>";
?>
<script xmlns="http://www.w3.org/1999/html">
    jQuery(function($){
        $('.tipsy-q').tipsy({gravity: 'w'}).css('cursor', 'default');

        $('.color~.colorpicker').each(function(){
            $(this).farbtastic($(this).prev().prev());
            $(this).hide();
            $(this).prev().prev().bind('focus', function(){
                $(this).next().next().fadeIn();
            });
            $(this).prev().prev().bind('blur', function(){
                $(this).next().next().fadeOut();
            });
        });

        $('select[name=map_id]').change(function() {
            location.href='admin.php?page=free-map-plugin-options&map_id='+$(this).val();
        });

        $('input[name*=isResponsive]').change(function() {

            var resp = $('input[name*=isResponsive]:eq(0)').prop('checked') ? false : true;
            $('input[name*=maxWidth]').prop('disabled',!resp);
            $('input[name*=mapWidth],input[name*=mapHeight]').prop('disabled',resp);

        });
        $('input[name*=isResponsive]').trigger('change');

        $('.radio-block h4').click(function() {
            $(this).prev('input').trigger('click');    
        });

        $('input[name*=df_type]').change(function() {

            var local = $('input[name*=df_type]:eq(0)').prop('checked') ? false : true;
            $('input[name*=data_file]:eq(0)').prop('disabled',!local);
            $('input[name*=data_file]:eq(1)').prop('disabled',local);

        });
        $('input[name*=df_type]').trigger('change');



    });
</script>

<div class="wrap free-html5-map main full">
<div class="left-block">

<form method="POST">

    <span class="title" style="width: 200px;"><?php _e( 'Map:', 'html5-maps' ) ?> </span>
    <select name="map_id" style="width: 285px;">
        <?php foreach($options as $id => $map_data) { ?>
            <option value="<?php echo $id; ?>" <?php echo ($id==$map_id)?'selected':'';?>><?php echo "$map_data[name] ($map_data[type])"; ?></option>
        <?php } ?>
    </select>
    <span class="tipsy-q" original-title="<?php esc_attr_e( 'The map', 'html5-maps' ) ?>">[?]</span>
    <a href="admin.php?page=free-map-plugin-maps" class="page-title-action" style="top: 2px"><?php
    _e('Maps list', 'wp-l10n-domain') ?></a>
    <br /><br/>

    <?php free_map_plugin_nav_tabs('options', $map_id); ?>

    <br/>

    <p><?php _e( 'Specify general settings of the map. To choose a color, click a color box, select the desired color in the color selection dialog and click anywhere outside the dialog to apply the chosen color.', 'html5-maps' ) ?></p>

    <fieldset>
        <legend><?php _e( 'Map Settings', 'html5-maps' ) ?></legend>

        <span class="title"><?php _e( 'Map name:', 'html5-maps' ) ?> </span><input type="text" name="options[name]" value="<?php echo htmlspecialchars($options[$map_id]['name']); ?>" />
        <span class="tipsy-q" original-title="<?php esc_attr_e( 'Name of the map', 'html5-maps' ) ?>">[?]</span><div class="colorpicker"></div>


        <span class="title"><?php _e( 'Borders Color:', 'html5-maps' ) ?> </span><input class="color" type="text" name="options[borderColor]" value="<?php echo htmlspecialchars($options[$map_id]['borderColor']); ?>" style="background-color: #<?php echo htmlspecialchars($options[$map_id]['borderColor']); ?>" />
        <span class="tipsy-q" original-title="<?php esc_attr_e( 'The color of borders on the map', 'html5-maps' ) ?>">[?]</span><div class="colorpicker"></div>
        <div class="clear"></div>

        <span class="title"><?php _e( 'Responsive layout:', 'html5-maps' ) ?> </span>
        <label><?php _e( 'Disabled:', 'html5-maps' ) ?> <input type="radio" name="options[isResponsive]" value=0 <?php echo !$options[$map_id]['isResponsive']?'checked':''?> /></label>&nbsp;&nbsp;&nbsp;&nbsp;
        <label><?php _e( 'Enabled:', 'html5-maps' ) ?> <input type="radio" name="options[isResponsive]" value=1 <?php echo $options[$map_id]['isResponsive']?'checked':''?> /></label>
        <span class="tipsy-q" original-title="<?php esc_attr_e( 'Type of the layout', 'html5-maps' ) ?>">[?]</span>
        <div class="clear" style="margin-bottom: 10px"></div>

        <span class="title"><?php _e( 'Map width:', 'html5-maps' ) ?> </span><input class="span2" type="text" name="options[mapWidth]" value="<?php echo htmlspecialchars($options[$map_id]['mapWidth']); ?>" />
        <span class="tipsy-q" original-title="<?php esc_attr_e( 'The width of the map', 'html5-maps' ) ?>">[?]</span>
        <div class="clear"></div>

        <span class="title"><?php _e( 'Map height:', 'html5-maps' ) ?> </span><input class="span2" type="text" name="options[mapHeight]" value="<?php echo htmlspecialchars($options[$map_id]['mapHeight']); ?>" />
        <span class="tipsy-q" original-title="<?php esc_attr_e( 'The height of the map', 'html5-maps' ) ?>">[?]</span>
        <div class="clear"></div>

        <span class="title"><?php _e( 'Max width:', 'html5-maps' ) ?> </span><input class="span2" type="text" name="options[maxWidth]" value="<?php echo htmlspecialchars($options[$map_id]['maxWidth']); ?>" disabled />
        <span class="tipsy-q" original-title="<?php esc_attr_e( 'The max width of the map', 'html5-maps' ) ?>">[?]</span>
        <div class="clear"></div>

    </fieldset>

    <fieldset>
        <legend><?php _e( 'Content Info', 'html5-maps' ) ?></legend>    
        <span class="title"><?php _e( 'Additional Info Area:', 'html5-maps' ) ?> </span>
        <label><?php _e( 'At right:', 'html5-maps' ) ?> <input type="radio" name="options[statesInfoArea]" value="right" <?php echo $options[$map_id]['statesInfoArea'] == 'right'?'checked':''?> /></label>&nbsp;&nbsp;&nbsp;&nbsp;
        <label><?php _e( 'At bottom:', 'html5-maps' ) ?> <input type="radio" name="options[statesInfoArea]" value="bottom" <?php echo $options[$map_id]['statesInfoArea'] == 'bottom'?'checked':''?> /></label>
        <span class="tipsy-q" original-title="<?php esc_attr_e( 'Where to place an additional information about state', 'html5-maps' ) ?>">[?]</span><br />
    </fieldset>
    
    <fieldset style="margin-top: 2px;">
        <legend><?php _e( 'Resource', 'html5-maps' ) ?></legend>

        <span class="title" style="float: left; height: 130px; width: 15%;"><?php _e( 'Path to map data file:', 'html5-maps' ) ?> </span>

        <div class="radio-block">
            <input type="radio" name="options[df_type]" value="0" <?php echo (!isset($options[$map_id]['df_type']) OR $options[$map_id]['df_type']==0) ? 'checked' : ''; ?> />
            <h4><?php _e( 'data file on html5maps.com', 'html5-maps' ) ?></h4><span class="tipsy-q" original-title="<?php esc_attr_e( 'Path to map data file', 'html5-maps' ) ?>">[?]</span>
            <div class="clear"></div>
            <input type="text" value="<?php echo htmlspecialchars($options[$map_id]['defaultDataFile']); ?>" readonly />
        </div>

        <div class="radio-block">
            <input type="radio" name="options[df_type]" value="1" <?php echo (isset($options[$map_id]['df_type']) AND $options[$map_id]['df_type']==1) ? 'checked' : ''; ?> />
            <h4><?php _e( 'data file on your server', 'html5-maps' ) ?></h4><span class="tipsy-q" original-title="<?php esc_attr_e( 'Path to map data file', 'html5-maps' ) ?>">[?]</span>
            <div class="clear"></div>
            <input type="text" name="options[data_file]" value="<?php echo isset($options[$map_id]['data_file']) ? htmlspecialchars($options[$map_id]['data_file']) : ''; ?>" />
        </div>

    </fieldset>

    <input type="hidden" name="act_type" value="free_map_plugin_main_save" />
    <?php wp_nonce_field('free_map_plugin_main_save'); ?>
    <p class="submit"><input type="submit" value="<?php esc_attr_e( 'Save Changes', 'html5-maps' ) ?>" class="button-primary" id="submit" name="submit"></p> 

</form>
</div>

<div class="qanner">
            <a href="https://www.fla-shop.com/wordpressmaps.php?utm_source=html5-maps-plugin&utm_medium=dashboard&utm_campaign=banner" target="_blank"><img src="<?php echo WP_PLUGIN_URL.'/html5-maps/static/' ?>html5maps_img.png<?php echo "?r=".time();?>" border="0" width="161" height="601"></a>
</div>

<div class="clear"></div>
</div>
