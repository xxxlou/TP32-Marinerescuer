(function( $ ) {
	//Document ready function
	 $(document).ready(function() {
	 		//Get the Tableau URL
	 		function getTableauURL(tableau_url) {
	 			return tableau_url;
	 		}

	    tinymce.create('tinymce.plugins.simple_tableau_viz', {
		    init : function(ed, url) {
           // Register command for when button is clicked
           ed.addCommand('simple_tableau_viz_insert_shortcode', function() {
               var insert_tableau_viz = window.prompt('Enter your Tableau Public Vizualization URL');
               if( insert_tableau_viz ){
                   //If text is selected when button is clicked, use it to create the shortcode
                   content =  '[tableau url="'+getTableauURL(insert_tableau_viz)+'"]';
               } else{
                   content =  '[tableau url="Insert Your Tableau Public Vizualization URL"]';
               }

               tinymce.execCommand('mceInsertContent', false, content);
           });

           // Register buttons
           ed.addButton('simple_tableau_viz', {
						 	title : 'Add Tableau Public Vizualization', cmd : 'simple_tableau_viz_insert_shortcode', image: url + '/../img/tableau-public-logo.png' });
	         },
	     });

	     // Register the TinyMCE plugin
	     tinymce.PluginManager.add('simple_tableau_viz', tinymce.plugins.simple_tableau_viz);
	 });

})( jQuery );
