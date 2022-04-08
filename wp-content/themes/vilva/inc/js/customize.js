jQuery(document).ready(function($) {

	/* Move widgets to general settings panel */
	wp.customize.section( 'sidebar-widgets-featured-area' ).panel( 'general_settings' );
    wp.customize.section( 'sidebar-widgets-featured-area' ).priority( '15' );

    //Scroll to front page section
    $('body').on('click', '#sub-accordion-panel-general_settings .control-subsection .accordion-section-title', function(event) {
        var section_id = $(this).parent('.control-subsection').attr('id');
        Vilva_scrollToSection( section_id );
    }); 

    function Vilva_scrollToSection( section_id ){
    var preview_section_id = "banner_section";

    var $contents = jQuery('#customize-preview iframe').contents();

    switch ( section_id ) {

        case 'accordion-section-sidebar-widgets-featured-area':
        preview_section_id = "featured_area";
        break;
        
        case 'accordion-section-featured_area_settings':
        preview_section_id = "featured_section";
        break;

        case 'accordion-section-popular_area_settings':
        preview_section_id = "popular_section";
        break;

    }

    if( $contents.find('#'+preview_section_id).length > 0 && $contents.find('.home').length > 0 ){
        $contents.find("html, body").animate({
        scrollTop: $contents.find( "#" + preview_section_id ).offset().top
        }, 1000);
    }
}
});

( function( api ) {

    // Extends our custom "example-1" section.
    api.sectionConstructor['vilva-pro-section'] = api.Section.extend( {

        // No events for this type of section.
        attachEvents: function () {},

        // Always make the section active.
        isContextuallyActive: function () {
            return true;
        }
    } );

} )( wp.customize );