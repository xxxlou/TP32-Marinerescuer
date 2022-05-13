var el = wp.element.createElement;

wp.blocks.registerBlockType('stv/simple-tableau-viz', {
	title: 'Simple Tableau Viz Block',
	icon: 'analytics',
	keywords: ['tableau'],
	description: 'Embed a Tableau Public Viz',
	category: 'embed',
	attributes: {
	  	  	url: {
		    type: 'string'
		}
	},
	supports: {
		multiple: false,
	},

  edit: function(props) {
  	var url = props.attributes.url,
  	    icon = props.icon,
  		label = 'Simple Tableau Viz Block';

    function updateUrl(event) {
      props.setAttributes({url: event.target.value})
    }
      
	return [
			el(
				wp.components.Placeholder, {
		    		icon: el(
		    					wp.blockEditor.BlockIcon, {
		      						icon: 'analytics',
		      						showColors: true
		    					}
		    				),
		    		label: label,
		    		className: "wp-block-embed"
		  		},
		 		el("input", {
			    	type: "url",
			    	value: url || '',
			    	className: "components-placeholder__input",
			    	"aria-label": label,
			    	placeholder: 'Enter Tableau Public URL to embed here…',
			    	onChange: updateUrl
		  		})
	  		)
			];

	   },
  save: function(props) {
  	var url = props.attributes.url;
    return el("div", {
			  id: "vizContainer",
			  "data-url": url
			});
  }
})