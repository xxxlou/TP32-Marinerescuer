<?php

free_map_check_map_exists();

$options = get_site_option('freehtml5map_options');
$option_keys = is_array($options) ? array_keys($options) : array();
$map_id  = (isset($_REQUEST['map_id'])) ? intval($_REQUEST['map_id']) : array_shift($option_keys) ;

$states  = $options[$map_id]['map_data'];
$states  = json_decode($states, true);

$sId = isset($_GET['s_id']) ? $_GET['s_id'] : false;

if(isset($_POST['act_type']) && $_POST['act_type'] == 'free_map_plugin_states_save') {
    check_admin_referer('free_map_plugin_states_save');

    if($sId)
    {
        $s_id = 'st'.$sId;
        $vals = $states[$s_id];
        if(isset($_POST['name'][$vals['id']]))
            $states[$s_id]['name'] = sanitize_text_field(stripcslashes($_POST['name'][$vals['id']]));

	if(isset($_POST['shortname'][$vals['id']]))
	    $_POST['shortname'][$vals['id']] = str_replace('\\\\n',"\n",$_POST['shortname'][$vals['id']]);
            $states[$s_id]['shortname'] = wp_kses_post(stripcslashes($_POST['shortname'][$vals['id']]));

        if(isset($_POST['URL'][$vals['id']]))
            $states[$s_id]['link'] = esc_url(stripslashes($_POST['URL'][$vals['id']]), null, 'url');

        if(isset($_POST['info'][$vals['id']]))
            $states[$s_id]['comment'] = wp_kses_post(stripcslashes($_POST['info'][$vals['id']]));

        if(isset($_POST['image'][$vals['id']]))
            $states[$s_id]['image'] = sanitize_text_field(stripcslashes($_POST['image'][$vals['id']]));

        if(isset($_POST['color'][$vals['id']]))
            $states[$s_id]['color_map'] = sanitize_text_field(stripcslashes($_POST['color'][$vals['id']][0] == '#' ? $_POST['color'][$vals['id']] : '#' . $_POST['color'][$vals['id']]));

        if(isset($_POST['color_'][$vals['id']]))
            $states[$s_id]['color_map_over'] = sanitize_text_field(stripcslashes($_POST['color_'][$vals['id']][0] == '#' ? $_POST['color_'][$vals['id']] : '#' . $_POST['color_'][$vals['id']]));

        if(isset($_POST['descr'][$vals['id']]))
            $options[$map_id]['state_info'][$vals['id']] = wp_kses_post(stripcslashes($_POST['descr'][$vals['id']]));

	    if(isset($_POST['colorSimpleCh'][$vals['id']])) {
		foreach($states as $k=>$v) {
			$states[$k]['color_map'] = sanitize_text_field(stripcslashes($_POST['color'][$vals['id']][0] == '#' ? $_POST['color'][$vals['id']] : '#' . $_POST['color'][$vals['id']]));
		}
	    }

	    if(isset($_POST['colorOverCh'][$vals['id']])) {
		foreach($states as $k=>$v) {
			$states[$k]['color_map_over'] = sanitize_text_field(stripcslashes($_POST['color_'][$vals['id']][0] == '#' ? $_POST['color_'][$vals['id']] : '#' . $_POST['color_'][$vals['id']]));
		}
	    }
    }

    $options[$map_id]['map_data'] = json_encode($states);

    update_site_option('freehtml5map_options', $options);
}



echo "<h2>" . __( 'Configuration of Map Areas', 'html5-maps' ) . "</h2>";
?>
<script>
    var imageFieldId = false;
    jQuery(function(){

	jQuery('select[name=map_id],select[name=state_select]').change(function() {

	    if (jQuery(this).prop('name')=='map_id') {
		s_id = 0;
	    } else {
		s_id = jQuery('select[name=state_select] option:selected').val();
	    }

            location.href='admin.php?page=free-map-plugin-states&map_id='+jQuery('select[name=map_id] option:selected').val()+'&s_id='+s_id;
        });

        jQuery('.tipsy-q').tipsy({gravity: 'w'}).css('cursor', 'default');

        jQuery('.color~.colorpicker').each(function(){
            var me = this;

            jQuery(this).farbtastic(function(color){

                var textColor = this.hsl[2] > 0.5 ? '#000' : '#fff';

                jQuery(me).prev().prev().css({
                    background: color,
                    color: textColor
                }).val(color);

                if(jQuery(me).next().find('input').prop('checked')) {
                    return;
                    var dirClass = jQuery(me).prev().prev().hasClass('colorSimple') ? 'colorSimple' : 'colorOver';

                    jQuery('.'+dirClass).css({
                        background: color,
                        color: textColor
                    }).val(color);
                }

            });

            jQuery.farbtastic(this).setColor(jQuery(this).prev().prev().val());

            jQuery(jQuery(this).prev().prev()[0]).bind('change', function(){
                jQuery.farbtastic(me).setColor(this.value);
            });

            jQuery(this).hide();
            jQuery(this).prev().prev().bind('focus', function(){
                jQuery(this).next().next().fadeIn();
            });
            jQuery(this).prev().prev().bind('blur', function(){
                jQuery(this).next().next().fadeOut();
            });
        });

        jQuery('.stateinfo input:radio').click(function(){
            //alert(jQuery(this).prop('id'));
            var el_id = jQuery(this).prop('id').substring(1);
            if(jQuery(this).prop('id').charAt(0)=='n'){
                jQuery("#URL"+el_id).prop("value", "");
                jQuery("#stateURL"+el_id).fadeOut(0);
                jQuery("#stateDescr"+el_id).fadeOut(0);
            }
            else if(jQuery(this).prop('id').charAt(0)=='d'){
                jQuery("#URL"+el_id).prop("value", "#");
                jQuery("#stateURL"+el_id).fadeOut(0);
                jQuery("#stateDescr"+el_id).fadeOut(0);
            }
            else if(jQuery(this).prop('id').charAt(0)=='o'){
                jQuery("#URL"+el_id).prop("value", "http://");
                //jQuery("#URL"+el_id).prop("readonly", false);
                jQuery("#stateURL"+el_id).fadeIn(0);
                jQuery("#stateDescr"+el_id).fadeOut(0);
            }
            else if(jQuery(this).prop('id').charAt(0)=='m'){
                jQuery("#URL"+el_id).prop("value", "#info");
                //jQuery("#URL"+el_id).prop("readonly", false);
                jQuery("#stateURL"+el_id).fadeOut(0);
                jQuery("#stateDescr"+el_id).fadeIn(0);
            }
        });

        jQuery('.colorSimpleCh').bind('click', function(){
            if(this.checked) {
                jQuery('.colorSimpleCh').prop('checked', false);
                this.checked = true;
            }
        });

        jQuery('.colorOverCh').bind('click', function(){
            if(this.checked) {
                jQuery('.colorOverCh').prop('checked', false);
                this.checked = true;
            }
        });

        window.send_to_editorArea = window.send_to_editor;

        window.send_to_editor = function(html) {
            if(imageFieldId === false) {
                window.send_to_editorArea(html);
            }
            else {
                var imgurl = jQuery('img',html).prop('src');

                jQuery('#'+imageFieldId).val(imgurl);
                imageFieldId = false;

                tb_remove();
            }

        }
        try {
        if (window.tinyMCE) {
            tinyMCE.execCommand('mceAddControl', true, 'descr'+this.value);
        }
		} catch (e) {}

	jQuery('input[type=submit]').prop('disabled',false);

    });

    function clearImage(f) {
        jQuery(f).prev().val('');
    }	

    function adjustSubmit() {
        if(jQuery('.colorOverCh:checked').length > 0) {
            var ch = jQuery('.colorOverCh:checked')[0];
            var color = jQuery(ch).parent().prev().prev().prev().val();
            jQuery('.colorOver').val(color);
        }

        if(jQuery('.colorSimpleCh:checked').length > 0) {
            var ch = jQuery('.colorSimpleCh:checked')[0];
            var color = jQuery(ch).parent().prev().prev().prev().val();
            jQuery('.colorSimple').val(color);
        }
    }
</script>

<div class="wrap free-html5-map main full">
<div class="left-block">
    <form method="POST" onsubmit="adjustSubmit()">
    
        <span class="title" style="width: 200px;"><?php _e( 'Map:', 'html5-maps' ) ?> </span>
    <select name="map_id" style="width: 285px;">
        <?php foreach($options as $id => $map_data) { ?>
            <option value="<?php echo $id; ?>" <?php echo ($id==$map_id)?'selected':'';?>><?php echo htmlspecialchars("$map_data[name] ($map_data[type])"); ?></option>
        <?php } ?>
    </select>
    <span class="tipsy-q" original-title="<?php esc_attr_e( 'The map', 'html5-maps' ) ?>">[?]</span>
    <a href="admin.php?page=free-map-plugin-maps" class="page-title-action" style="top: 2px"><?php
    _e('Maps list', 'wp-l10n-domain') ?></a>
    <br /><br/>

    <?php free_map_plugin_nav_tabs('states', $map_id); ?>
    <br>
	<p><?php _e( 'This tab allows you to add the area-specific information and adjust colors of individual area on the map.', 'html5-maps' ) ?></p>
	    <p class="help"><?php _e( '* The term "area" means one of the following: region, state, country, province, county or district, depending on the particular plugin.', 'html5-maps' ) ?></p>

	    <br />

	    <select name="state_select" style="width: 285px;">
		<option value=0><?php _e( 'Select an area', 'html5-maps' ) ?></option>
	    <?php

	    foreach($states as $s_id=>$vals)
	    {
		?>
		<option value="<?php echo $vals['id']?>" <?= $sId == $vals['id'] ? ' selected' : ''?>><?php echo htmlspecialchars(preg_replace('/^\s?<!--\s*?(.+?)\s*?-->\s?$/', '\1', $vals['name'])); ?></option>
		<?php
	    }
	    ?>
	    </select>


	    <div style="clear: both; height: 30px;"></div>


	<?php

	    $mce_options = array(
		//'media_buttons' => false,
		'editor_height'   => 150,
		'textarea_rows'   => 20
	    );

	if($sId) {
	    $vals        = $states['st'.$sId];
	    $rad_nill    = "";
	    $rad_def     = "";
	    $rad_other   = "";
	    $rad_more    = "";
	    $style_input = "";
	    $style_area  = "";

	    $vals['shortname'] = str_replace("\n",'\n',$vals['shortname']);
	    //$vals['shortname'] = str_replace("\\n",'\n',$vals['shortname']);

	    $mce_options['textarea_name'] = "descr[{$vals['id']}]";

	    if(trim($vals['link']) == "") $rad_nill = "checked";
	    elseif(trim($vals['link']) == "#") $rad_def = "checked";
	    elseif(stripos($vals['link'], "javascript:free_map_set_state_text") !== false OR $vals['link'] == '#info') $rad_more = "checked";
	    else $rad_other = "checked";

	    if(($rad_nill == "checked")||($rad_def == "checked")||($rad_more == "checked")) $style_input = "display: none;";
	    if(($rad_nill == "checked")||($rad_def == "checked")||($rad_other == "checked")) $style_area = "display: none;";
	    ?>

	    <fieldset>
		<legend><?php _e( 'Map area', 'html5-maps' ) ?></legend> 

		<div style="" id="stateinfo-<?php echo $vals['id']?>" class="stateinfo">
		    <span class="title"><?php _e( 'Name:', 'html5-maps' ) ?> </span><input class="" type="text" name="name[<?php echo $vals['id']?>]" value="<?php echo htmlspecialchars($vals['name']) ?>" />
		    <span class="tipsy-q" original-title="<?php esc_attr_e( 'Name of Area', 'html5-maps' ) ?>">[?]</span>
		    <div class="clear"></div>

		    <span class="title"><?php _e( 'Shortname:', 'html5-maps' ) ?> </span><input class="" type="text" name="shortname[<?php echo $vals['id']?>]" value="<?php echo htmlspecialchars($vals['shortname']) ?>" />
		    <span class="tipsy-q" original-title="<?php esc_attr_e( 'Shortname of Area', 'html5-maps' ) ?>">[?]</span>
		    <div class="clear"></div>

		    <span class="title"><?php _e( 'What to do when the area is clicked:', 'html5-maps' ) ?> </span>
		    <input type="radio" name="URLswitch[<?php echo $vals['id']?>]" id="n<?php echo $vals['id']?>" value="nill" <?php echo $rad_nill?> >&nbsp;<?php _e( 'Nothing', 'html5-maps' ) ?> <span class="tipsy-q" original-title="<?php esc_attr_e( 'Do not react on mouse clicks', 'html5-maps' ) ?>">[?]</span>
		    <!--input type="radio" name="URLswitch[<?php echo $vals['id']?>]" id="d<?php echo $vals['id']?>" value="def" <?php echo $rad_def?> >&nbsp;<?php _e( 'Show popup balloon on the map', 'html5-maps' ) ?> <span class="tipsy-q" original-title="<?php esc_attr_e( 'Display a popup balloon with the specified information', 'html5-maps' ) ?>">[?]</span-->
		    <input type="radio" name="URLswitch[<?php echo $vals['id']?>]" id="o<?php echo $vals['id']?>" value="other" <?php echo $rad_other?> >&nbsp;<?php _e( 'Open a URL', 'html5-maps' ) ?> <span class="tipsy-q" original-title="<?php esc_attr_e( 'A click on this area opens a specified URL', 'html5-maps' ) ?>">[?]</span>
		    <input type="radio" name="URLswitch[<?php echo $vals['id']?>]" id="m<?php echo $vals['id']?>" value="more" <?php echo $rad_more?> >&nbsp;<?php _e( 'Show more info', 'html5-maps' ) ?> <span class="tipsy-q" original-title="<?php esc_attr_e( 'Displays a side-panel with additional information (contacts, addresses etc.)', 'html5-maps' ) ?>">[?]</span><br />
		    <div style="<?php echo $style_input; ?>" id="stateURL<?php echo $vals['id']?>">
			<span class="title"><?php _e( 'URL:', 'html5-maps' ) ?> </span><input style="width: 240px;" class="" type="text" name="URL[<?php echo $vals['id']?>]" id="URL<?php echo $vals['id']?>" value="<?php echo htmlspecialchars($vals['link']) ?>" />
			<span class="tipsy-q" original-title="<?php esc_attr_e( 'The landing page URL', 'html5-maps' ) ?>">[?]</span></br>
		    </div>
		    <div style="<?php echo $style_area; ?>" id="stateDescr<?php echo $vals['id']?>"><br />
			<span class="title"><?php _e( 'Description:', 'html5-maps' ) ?> <span class="tipsy-q" original-title="<?php esc_attr_e( 'The description is displayed to the right of the map and contains contacts or some other additional information', 'html5-maps' ) ?>">[?]</span> </span>
			<?php wp_editor($options[$map_id]['state_info'][$vals['id']], 'descr'.$vals['id'], $mce_options); ?>
			<!--textarea style="width: 100%" class="" rows="10" cols="45" id="descr<?php echo $vals['id']?>" name="descr[<?php echo $vals['id']?>]"><?php echo htmlspecialchars($options[$map_id]['state_info'][$vals['id']]);  ?></textarea--></br>
		    </div>
		    <span class="title"><?php _e( 'Info for popup balloon:', 'html5-maps' ) ?> <span class="tipsy-q" original-title="<?php esc_attr_e( 'Info for popup balloon', 'html5-maps' ) ?>">[?]</span> </span><textarea style="width:100%; height: 150px;" class="" rows="10" cols="45" name="info[<?php echo $vals['id']?>]"><?php echo $vals['comment']?></textarea><br />
		    <?php
		    if(1) // just disable if((int)$tariff == 2)
		    {
			?>
			<span class="title"><?php _e( 'Area color:', 'html5-maps' ) ?> </span><input class="color colorSimple" type="text" name="color[<?php echo $vals['id']?>]" value="<?php echo htmlspecialchars($vals['color_map'])?>" style="background-color: #<?php echo htmlspecialchars($vals['color_map'])?>"  />
			<span class="tipsy-q" original-title='<?php _e( 'The color of an area.', 'html5-maps' ) ?>'>[?]</span><div class="colorpicker"></div>
			<label><input name="colorSimpleCh[<?php echo $vals['id']?>]" class="colorSimpleCh" type="checkbox" /> <?php _e( 'Apply to all areas', 'html5-maps' ) ?></label>
			<br />
			<span class="title"><?php _e( 'Area hover color:', 'html5-maps' ) ?> </span><input class="color colorOver" type="text" name="color_[<?php echo $vals['id']?>]" value="<?php echo htmlspecialchars($vals['color_map_over'])?>" style="background-color: #<?php echo htmlspecialchars($vals['color_map_over']) ?>"  />
			<span class="tipsy-q" original-title='<?php _e( 'The color of an area when the mouse cursor is over it.', 'html5-maps' ) ?>'>[?]</span><div class="colorpicker"></div>
			<label><input name="colorOverCh[<?php echo $vals['id']?>]" class="colorOverCh" type="checkbox" /> <?php _e( 'Apply to all areas', 'html5-maps' ) ?></label>
			<br />
			<?php
		    }


		    ?>
		    <span class="title"><?php _e( 'Image URL:', 'html5-maps' ) ?> </span>
			<input onclick="imageFieldId = this.id; tb_show('Image', 'media-upload.php?type=image&tab=library&TB_iframe=true');" class="" type="text" id="image-<?php echo $vals['id']?>" name="image[<?php echo $vals['id']?>]" value="<?php echo htmlspecialchars($vals['image']) ?>" />
			<span style="font-size: 10px; cursor: pointer;" onclick="clearImage(this)"><?php _e( 'clear', 'html5-maps' ) ?></span>
		    <span class="tipsy-q" original-title="<?php esc_attr_e( 'The path to file of the image to display in a popup', 'html5-maps' ) ?>">[?]</span><br />
		</div>

	    </fieldset>
	    <?php
	}
	?>

	<input type="hidden" name="act_type" value="free_map_plugin_states_save" />
	<?php wp_nonce_field('free_map_plugin_states_save'); ?>
	<p class="submit"><input type="submit" value="<?php esc_attr_e( 'Save Changes', 'html5-maps' ) ?>" class="button-primary" id="submit" name="submit" disabled></p>
    </form>
</div>

<div class="qanner" style="margin-top: 27px;">
            <a href="https://www.fla-shop.com/wordpressmaps.php?utm_source=html5-maps-plugin&utm_medium=dashboard&utm_campaign=banner" target="_blank"><img src="<?php echo WP_PLUGIN_URL.'/html5-maps/static/' ?>html5maps_img.png<?php echo "?r=".time();?>" border="0" width="161" height="601"></a>
</div>

<div class="clear"></div>
</div>
