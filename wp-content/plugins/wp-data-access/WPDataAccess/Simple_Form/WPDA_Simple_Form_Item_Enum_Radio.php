<?php

namespace WPDataAccess\Simple_Form;

class WPDA_Simple_Form_Item_Enum_Radio extends WPDA_Simple_Form_Item_Enum {

	/**
	 * Overwrite method show_item: create radio group from enum
	 */
	protected function show_item() {
		// Enum column: show values in radio group.
		$index = 0;
		foreach ( $this->item_enum as $value ) {
			$text    = ! isset( $this->item_enum_text[ $index ] ) ? $value : $this->item_enum_text[ $index ];
			if ( null === $this->item_value ) {
				$checked = $value === $this->item_default_value ? 'checked' : '';
			} else {
				$checked = $value === $this->item_value ? 'checked' : '';
			}
			?>
			<label class="wpda_simple_radio">
				<input type="radio"
					name="<?php echo esc_attr( $this->item_name ); ?>"
					value="<?php echo esc_attr( $value ); ?>"
					<?php echo esc_attr( $checked ); ?>
				>
				<?php echo esc_attr( $text ); ?>
			</label>
			<?php
			$index++;
		}
	}

}