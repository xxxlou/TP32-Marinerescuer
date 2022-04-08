<style>
ul.free-map-pb {
    list-style-type: disc;
    padding-left: 40px;
}
ul.free-map-pb ul.free-map-pb {
    list-style-type: circle;
}
small.free-map-pb {
    font-size: 70%;
    vertical-align: super;
	color: #00a650;
}
fieldset.free-html-pb {
    margin-top: 20px;
	margin-bottom: 10px;
}

fieldset.free-html-pb a.button{
    margin-top: -6px;
    padding: 0 20px 1px;
}

.free-map-exemple {
    margin-top: 100px;
}
fieldset.offer {
  font-size: 16px;
  padding: 10px;
  background: #fffde6;
  border: 1px solid #e6e6ce;
}
</style>
<fieldset class="free-html-pb">
<strong style="float: left">Select your premium map</strong>
<div style="float: left; margin-left: 50px">
<select name="map_type" class="chosen-select">
    <option value=""><?php _e( 'Please select the map', 'html5-maps' ) ?></option>

    <?php
        $maps = free_map_get_pb_maps();
        $last_group = ''; $n=0;
        foreach($maps as $id => $map) {

            $n++;

            if ($map->group!=$last_group) {
                if ($n>1) { echo '</optgroup>'; }
                echo '<optgroup label="'.$map->group.'">';
            }

            $last_group = $map->group;
            $map->name_html        = str_replace('+','%20',urlencode($map->name_html));

    ?>
        <option value="<?php echo $id; ?>" data-name-html="<?php echo $map->name_html; ?>" data-href="<?php echo $map->href ?>"><?php echo $map->name; ?></option>

    <?php } ?>

</select> &nbsp;&nbsp;&nbsp;&nbsp; <a class="button button-primary" target="_blank" ><?php _e( 'Go', 'html5-maps' ) ?></a>
</div>
<div class="clear"></div>
</fieldset>
<fieldset class="offer"><strong>LIMITED OFFER!</strong> USE COUPON CODE <strong>DC20</strong> AND GET 20% OFF</fieldset>
<p>
Create a responsive and mobile-friendly interactive map without any coding required. Below are possibilities of the plugin. Premium options are marked.
</p>

<div style="float: left; width: 50%">
<h3>General settings of the map</h3>

<ul class="free-map-pb">
<li>Responsive or fixed size</li>
<li>Create multiple maps with different settings</li>
<li>Display multiple maps on the same page <small class="free-map-pb">premium</small></li>
<li>Map width</li>
<li>Map height</li>
<li>Max width limitation</li>
<li>Border color</li>
<li>Zoom on/off <small class="free-map-pb">premium</small></li>
<li>Max zoom limitation <small class="free-map-pb">premium</small></li>
<li>Zoom increment <small class="free-map-pb">premium</small></li>
<li>Show zoom controls <small class="free-map-pb">premium</small></li>
<li>Enable zoom with mouse wheel <small class="free-map-pb">premium</small></li>
<li>Turn shadows on/off <small class="free-map-pb">premium</small></li>
<li>Shadow thickness <small class="free-map-pb">premium</small></li>
<li>Ability to hide names and abbreviations on the map <small class="free-map-pb">premium</small></li>
<li>Freeze tooltip on click <small class="free-map-pb">premium</small></li>
<li>Select placement of the additional content (right/below)</li>
<li>Automatically scroll to the info area on click <small class="free-map-pb">premium</small></li>
<li>Default content displayed on map load <small class="free-map-pb">premium</small></li>
<li>Color and font size of names on the map <small class="free-map-pb">premium</small></li>
<li>Color and font size of popup names <small class="free-map-pb">premium</small></li>
<li>Color and font size of the tooltip window <small class="free-map-pb">premium</small></li>
<li>Export / import the settings via spreadsheets <small class="free-map-pb">premium</small></li>
<li>Backup / restore settings <small class="free-map-pb">premium</small></li>
<li>No credits <small class="free-map-pb">premium</small></li>
</ul>

<h3>Individual area (countries/states/regions) settings</h3>
<ul class="free-map-pb">
<li>Popup name on mouse over</li>
<li>Option to turn off popup names <small class="free-map-pb">premium</small></li>
<li>Name on the map (abbreviation)</li>
<li>Color</li>
<li>Color on mouse over</li>
<li>Ability to apply the selected color settings for all areas on the map</li>
<li>Tooltip balloon content</li>
<li>Tooltip image</li>
<li>On click events:<ul class="free-map-pb">
    <li>Open a link</li>
    <li>Display additional content near the map (text plus any media content)</li>
    <li>Fix tooltip windows <small class="free-map-pb">premium</small></li>
    <li>Display lightbox popup <small class="free-map-pb">premium</small><br>
    Images, HTML, Facebook, Shortcode and more<br>
    Powered by the extremely capable Popup Builder plugin</li>
</ul></li>
</ul>

<h3>Batch settings. Creating groups <small class="free-map-pb">premium</small></h3>
<ul class="free-map-pb">
<li>Creating macro-regions by merging areas together <small class="free-map-pb">premium</small></li>
<li>Create groups visually <small class="free-map-pb">premium</small></li>
<li>Batch settings for multiple areas (tooltips, colors, on click events)&nbsp;<small class="free-map-pb">premium</small><br/>
For example, if you need to assign the same link to 20 areas on the map. Thanks to batch settings, you can quickly select these areas and add the link just once. This is much faster than configuring 20 areas one by one.</li>
</ul>

<h3>Adding points <small class="free-map-pb">premium</small></h3>
<ul class="free-map-pb">
<li>Visual point editor <small class="free-map-pb">premium</small></li>
<li>Point size <small class="free-map-pb">premium</small></li>
<li>Popup name of a point <small class="free-map-pb">premium</small></li>
<li>Tooltip with some content</li>
<li>Label of a point <small class="free-map-pb">premium</small></li>
<li>Font size and position of the label <small class="free-map-pb">premium</small></li>
<li>Color of the point and color on mouse over <small class="free-map-pb">premium</small></li>
<li>On click events of the point <small class="free-map-pb">premium</small><ul class="free-map-pb">
<li>Open a link <small class="free-map-pb">premium</small></li>
<li>Display additional content near the map (text plus any media content)</li>
<li>Fix tooltip windows <small class="free-map-pb">premium</small></li>
<li>Display lightbox popup <small class="free-map-pb">premium</small><br>
Images, HTML, Facebook, Shortcode and more<br>
Powered by the extremely capable Popup Builder plugin</ul></li>
<li>General color settings for labels <small class="free-map-pb">premium</small></li>
<li>Custom icons on request</li>
</ul>
</div>
<div style="float: left; width: 40%">
    <div style="width: 480px; height: 270px;" class="free-map-exemple"></div>

    <div style="width: 480px; height: 270px;" class="free-map-exemple"></div>
</div>
<div class="clear">
<fieldset class="free-html-pb">
<strong style="float: left">Select your premium map</strong>
<div style="float: left; margin-left: 50px">
<select name="map_type2" class="chosen-select">
    <option value=""><?php _e( 'Please select the map', 'html5-maps' ) ?></option>

    <?php
        $last_group = ''; $n=0;
        foreach($maps as $id => $map) {

            $n++;

            if ($map->group!=$last_group) {
                if ($n>1) { echo '</optgroup>'; }
                echo '<optgroup label="'.$map->group.'">';
            }

            $last_group = $map->group;

//            $map->name_html        = str_replace('+','%20',urlencode($map->name_html));

    ?>
        <option value="<?php echo $id; ?>" data-name-html="<?php echo $map->name_html; ?>" data-href="<?php echo $map->href ?>"><?php echo $map->name; ?></option>

    <?php } ?>

</select> &nbsp;&nbsp;&nbsp;&nbsp; <a class="button button-primary" target="_blank" ><?php _e( 'Go', 'html5-maps' ) ?></a>
</div>
<div class="clear"></div>
</fieldset>
<fieldset class="offer"><strong>LIMITED OFFER!</strong> USE COUPON CODE <strong>DC20</strong> AND GET 20% OFF</fieldset>
<script>
jQuery(document).ready(function($) {
    $('.chosen-select').each(function () {
        var a = $(this).next('a');
        a.addClass('disabled');
        $(this).chosen().change(function() {
            var s = $(this).find(':selected');
            var link = s.data('href');
            !!link ? a.prop('href', link) : a.removeAttr('href');
            !!link ? a.removeClass('disabled') : a.addClass('disabled')
        });
    });
});
</script>
