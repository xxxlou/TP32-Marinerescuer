<?php

/**
 * Suppress "error - 0 - No summary was found for this file" on phpdoc generation
 *
 * @package WPDataAccess\Simple_Form
 */

namespace WPDataAccess\Simple_Form {

	/**
	 * Class WPDA_Simple_Form_Item_File
	 *
	 * Database column is handled files
	 *
	 * @author  Peter Schulz
	 * @since   5.1.3
	 */
	class WPDA_Simple_Form_Item_File extends WPDA_Simple_Form_Item {

		/**
		 * WPDA_Simple_Form_Item_File constructor.
		 *
		 * @param array $args
		 */
		public function __construct( $args = [] ) {
			parent::__construct( $args );
		}

		/**
		 * Overwrite method
		 *
		 * @param string $action
		 * @param string $update_keys_allowed
		 */
		public function show( $action, $update_keys_allowed ) {
			parent::show( $action, $update_keys_allowed );
		}

		/**
		 * Overwrite method
		 */
		protected function show_item() {
		}

	}

}