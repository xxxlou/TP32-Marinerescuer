<?php

/**
 * Plugin Name: CloudTables
 * Plugin URI: cloudtables.com
 * Description: WordPress integration for CloudTables, to embed tables and forms into your WordPress site.
 * Requires PHP: 5.4
 * Author: SpryMedia Ltd
 * Version: 1.0.0
 * License: GPLv2 or later
 */

require('Api.php');

class CloudTables {
	public static function activate () {}
	public static function deactivate () {}
	public static function uninstall () {}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Private properties
	 */
	private $_cloudtables_options;


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Constructor
	 */
	public function __construct() {
		add_shortcode('cloudtable', [$this, 'shortcode']);
		add_action( 'init', [$this, 'register_block'] );
		add_action( 'enqueue_block_editor_assets', [$this, 'editor_variables'] );

		if ( is_admin() ) {
			add_action( 'admin_menu', [$this, 'add_plugin_page'] );
			add_action( 'admin_init', [$this, 'admin_page_init'] );
			add_filter(
				'plugin_action_links_' . plugin_basename( __FILE__ ),
				[ &$this, 'plugin_manage_link' ],
				10,
				4
			);
		}

		$this->_cloudtables_options = get_option( 'cloudtables_option_name' );		
	}

	/**
	 * Show a link on the plugins page to the settings - based on how Akismet does it
	 */
	function plugin_manage_link( $actions, $plugin_file, $plugin_data, $context ) {
		$args = array( 'page' => 'cloudtables' );
		$url = add_query_arg( $args, class_exists( 'Jetpack' )
			? admin_url( 'admin.php' )
			: admin_url( 'options-general.php' )
		);

		return array_merge(
			[
				'configure' => '<a href="'.$url.'">Settings</a>'
			],
			$actions
		);
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Public methods
	 */

	/**
	 * Add the plugin page to admin menu
	 */
	public function add_plugin_page() {
		add_options_page(
			'CloudTables', // page_title
			'CloudTables', // menu_title
			'manage_options', // capability
			'cloudtables', // menu_slug
			[$this, 'admin_page'] // function
		);
	}

	/**
	 * Admin page HTML
	 */
	public function admin_page() {
		?>
		<div class="wrap">
			<h2>CloudTables</h2>
			<p>CloudTables is a table and form builder which can be easily embedded into your WordPress pages using short tags. To allow access to the data via short tags, please enter the CloudTables API key you wish to use to read and write data - this can be found in the "Security -> API Keys" section of your CloudTables application.</p>
			<p>To display a CloudTable in your pages or posts, either use the "CloudTables" option in the block Editor, or if you prefer short codes, use <code>[cloudtable id="..."]</code>, where the <code>id</code> attribute is the ID of the table you wish to show. You may optionally also specify a <code>key</code> option which will be used instead of the API keys specified below.</p>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'cloudtables_option_group' );
					do_settings_sections( 'cloudtables-admin' );
					submit_button();
				?>
			</form>
			<?php
				$this->_display_datasets();
			?>
		</div>
	<?php }

	/**
	 * Admin page parameters
	 */
	public function admin_page_init() {
		register_setting(
			'cloudtables_option_group', // option_group
			'cloudtables_option_name', // option_name
			array( $this, function ($input) {
				$sanitary_values = array();

				if ( isset( $input['apikey'] ) ) {
					$sanitary_values['apikey'] = sanitize_text_field( $input['apikey'] );
				}

				if ( isset( $input['apikey_editor'] ) ) {
					$sanitary_values['apikey_editor'] = sanitize_text_field( $input['apikey_editor'] );
				}

				if ( isset( $input['subdomain'] ) ) {
					$sanitary_values['subdomain'] = sanitize_text_field( $input['subdomain'] );
				}

				return $sanitary_values;
			} )
		);

		add_settings_section(
			'cloudtables_setting_section', // id
			'Settings', // title
			array( $this, 'cloudtables_section_info' ), // callback
			'cloudtables-admin' // page
		);

		add_settings_field(
			'subdomain', // id
			'Sub-domain', // title
			array( $this, 'field_subdomain' ), // callback
			'cloudtables-admin', // page
			'cloudtables_setting_section' // section
		);

		add_settings_field(
			'apikey_editor', // id
			'Editor API Key', // title
			array( $this, 'field_apikey_editor' ), // callback
			'cloudtables-admin', // page
			'cloudtables_setting_section' // section
		);

		add_settings_field(
			'apikey', // id
			'Visitor API Key', // title
			array( $this, 'field_apikey_user' ), // callback
			'cloudtables-admin', // page
			'cloudtables_setting_section' // section
		);
	}

	/**
	 * Section header - noop
	 */
	public function cloudtables_section_info() {
	}


	/**
	 * Get the datasets that are available from CloudTables
	 */
	public function datasets () {
		$api = $this->_api_inst();

		return $api
			? $api->datasets()
			: [];
	}

	/**
	 * Setup the variables that will be used by the Javascritp block editor
	 */
	public function editor_variables() {
		$ct = new CloudTables();

		wp_localize_script(
			'cloudtables_block',
			'cloudtables_data',
			[
				'datasets' => $ct->datasets(),
				'img_path' => plugins_url('', __FILE__ )
			]
		);
	}

	/**
	 * API key input (editor)
	 */
	public function field_apikey_editor() {
		printf(
			'<input class="regular-text" type="text" name="cloudtables_option_name[apikey_editor]" id="apikey_editor" value="%s" style="margin-bottom: 0.5em"><br>This API key will be used for users who have editing access to your site. Typically you should set this using a read / write access key from CloudTables.',
			isset( $this->_cloudtables_options['apikey_editor'] )
				? esc_attr( $this->_cloudtables_options['apikey_editor'])
				: ''
		);
	}

	/**
	 * API key input (user)
	 */
	public function field_apikey_user() {
		printf(
			'<input class="regular-text" type="text" name="cloudtables_option_name[apikey]" id="apikey" value="%s" style="margin-bottom: 0.5em"><br>The API key that will be used for non-editor users of your site (i.e. visitors). This will typically be a readonly key.',
			isset( $this->_cloudtables_options['apikey'] )
				? esc_attr( $this->_cloudtables_options['apikey'])
				: ''
		);
	}

	/**
	 * Subdomain input
	 */
	public function field_subdomain() {
		printf(
			'<input class="regular-text" type="text" name="cloudtables_option_name[subdomain]" id="subdomain" value="%s" style="margin-bottom: 0.5em"><br>The sub-domain name of your CloudTables application (i.e. the part before the <code>.cloudtables.com</code>.',
			isset( $this->_cloudtables_options['subdomain'] )
				? esc_attr( $this->_cloudtables_options['subdomain'])
				: ''
		);
	}

	/**
	 * Register the CloudTables block for gutenberg
	 */
	public function register_block() {
		$asset_file = include( plugin_dir_path( __FILE__ ) . 'build/index.asset.php');
	
		wp_register_script(
			'cloudtables_block',
			plugins_url('build/index.js', __FILE__ ),
			$asset_file['dependencies'],
			$asset_file['version']
		);
	
		wp_enqueue_script('cloudtables_block');
	
		register_block_type(
			'cloudtables/table-block',
			[]
		);
	}

	/**
	 * Convert a shortcode to a CloudTables embed tag
	 */
	public function shortcode($attrs = []) {
		$id = isset($attrs['id'])
			? $attrs['id']
			: '-';
		
		$key = isset($attrs['key'])
			? $attrs['key']
			: null;

		if (! $id) {
			return 'CloudTables: Error - No data set ID given.';
		}

		$api = $this->_api_inst($key);

		wp_register_style(
			'cloudtables',
			plugins_url('cloudtables.css', __FILE__ )
		);
	
		wp_enqueue_style('cloudtables');

		if (! $api) {
			return '<p class="cloudtables-error">Sorry - unable to show CloudTable (error code: NOAPI). Please contact your system administrator to resolve this issue.</p>';	
		}

		$script = $api->scriptTag($id);

		wp_register_script(
			'cloudtables-'. $script['unique'],
			$script['url']
		);

		wp_enqueue_script('cloudtables-'. $script['unique']);

		return '<div data-ct-insert="'.htmlspecialchars($script['unique']).'" data-token="'.htmlspecialchars($script['token']).'"></div>';
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Private methods
	 */

	/**
	 * Get a CloudTables API instance
	 */
	private function _api_inst($apikey = null) {
		$options = get_option( 'cloudtables_option_name' );
		$subdomain = isset($options['subdomain'])
			? $options['subdomain']
			: '';

		if ($apikey === null) {
			if (current_user_can( 'edit_posts' )) {
				$apikey = isset($options['apikey_editor'])
					? $options['apikey_editor']
					: '';
			}
			else {
				$apikey = isset($options['apikey'])
					? $options['apikey']
					: '';
			}
		}

		if ($subdomain && $apikey) {
			return new \Cloudtables\Api(
				$subdomain,
				$apikey
				// The following is used for development only
				// ,[
				// 	'domain' => 'ct-devel-05.io',
				// 	'secure' => false
				// ]
			);
		}

		return false;
	}

	/**
	 * HTML for settings page to show the datasets available
	 */
	private function _display_datasets() {
		echo '<h2>Connection status</h2>';

		// Need to use the api rather than our own datasets method to determine if parameters have been
		// provided
		$api = $this->_api_inst();

		if ($api) {
			$datasets = $api->datasets();

			if ($datasets) {
				$date_format = get_option( 'date_format' );
				$time_format = get_option( 'time_format' );

				?>
					<p style="color: green">Good.</p>
					<h3>Available datasets</h3>
					<p>The following datasets are available based on the Editor API Key above.</p>
				
					<table class="widefat">
						<thead>
							<tr>
								<th>Name</th>
								<th>ID</th>
								<th>Number of rows</th>
								<th>Last updated</th>
							</tr>
						</thead>
						<tbody>
							<?php
								for ($i=0 ; $i<count($datasets) ; $i++) {
									$d = strtotime($datasets[$i]['lastData']);
									$formatted = $d
										? date($date_format, $d) .' - '. date($time_format, $d)
										: 'No data yet';

									echo '<tr>';
									echo '<td>'.htmlspecialchars($datasets[$i]['name']).'</td>';
									echo '<td>'.htmlspecialchars($datasets[$i]['id']).'</td>';
									echo '<td>'.htmlspecialchars($datasets[$i]['rowCount']).'</td>';
									echo '<td>'.htmlspecialchars($formatted).'</td>';
									echo '</tr>';
								}
							?>
						</tbody>
					</table>
				<?php
			}
			else {
				echo '<p style="color: red">Failed - incorrect API access key or sub-domain.</p>';
			}
		}
		else {
			echo '<p style="color: orange">Unavailable - no access details provided.</p>';
		}
	}
}

$ct = new CloudTables();

register_activation_hook( __FILE__, 'CloudTables::activate' );
register_deactivation_hook( __FILE__, 'CloudTables::deactivate' );
register_uninstall_hook( __FILE__, 'CloudTables::uninstall' );
