
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls } from '@wordpress/block-editor';
import { SelectControl, TextControl, PanelBody, PanelRow } from '@wordpress/components';

registerBlockType( 'cloudtables/table-block', {
	title: 'CloudTable',
	icon: 'cloud',
	category: 'layout',
	attributes: {
		apikey: { type: 'string' },
		datasetId: { type: 'string' },
	},
	edit: (props) => {
		let datasetId = props.attributes.datasetId || '';
		let apikey = props.attributes.apikey || '';
		let options = window.cloudtables_data
			.datasets
			.map((ds) => {
				return {
					label: ds.name,
					value: ds.id,
				}
			});
		
		options.unshift({
			label: 'No data set selected',
			value: '',
		});

		let datasetLabel = options
			.find(option => option.value === datasetId)
			.label;
		
		let stylesDisplay = {
			background: 'url("'+ window.cloudtables_data.img_path + '/logo.png") no-repeat top center',
			paddingTop: '50px',
			textAlign: 'center',
		};

		return [
			<InspectorControls>
				<PanelBody title='Properties'>
					<PanelRow>
						<SelectControl
							label="Data set"
							value={ datasetId }
							options={ options }
							onChange={ id => props.setAttributes( { datasetId: id } ) }
						/>  
					</PanelRow>
					<PanelRow>
						<TextControl
							label="API key (optional)"
							value={ apikey }
							onChange={ val => props.setAttributes( { apikey: val } ) }
							help="If you wish to use a specific API key for clients accessing this CloudTable, please set it here. Otherwise, leave this field empty to use the default specified in Settings."
						/>  
					</PanelRow>
				</PanelBody>
			</InspectorControls>,
			<div style={stylesDisplay}>
				<p>{ datasetLabel }</p>
			</div>
		];
	},
	save: (props) => {
		let datasetId = props.attributes.datasetId || '';
		let apikey = props.attributes.apikey || '';
		let shortcode = datasetId
			? '[cloudtable id="' + datasetId + '"'
			: '';

		if (apikey) {
			shortcode += ' key="' + apikey + '"'
		}

		shortcode += ']';

		return <div><p>{shortcode}</p></div>
	},
} );
