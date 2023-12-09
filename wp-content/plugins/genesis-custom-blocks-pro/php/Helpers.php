<?php
/**
 * Helper functions.
 *
 * @package   Genesis\CustomBlocksPro
 * @copyright Copyright(c) 2022, Genesis Custom Blocks Pro
 * @license http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

/**
 * Prepare a loop with the first or next row in a repeater.
 *
 * @param string $name The name of the repeater field.
 *
 * @return int
 */
function block_row( $name ) {
	genesis_custom_blocks_pro()->loop()->set_active( $name );
	return genesis_custom_blocks_pro()->loop()->increment( $name );
}

/**
 * Determine whether another repeater row exists to loop through.
 *
 * @param string $name The name of the repeater field.
 *
 * @return bool
 */
function block_rows( $name ) {
	$attributes = genesis_custom_blocks()->loader->get_data( 'attributes' );

	if ( ! isset( $attributes[ $name ] ) ) {
		return false;
	}

	$current_row = genesis_custom_blocks_pro()->loop()->get_row( $name );

	if ( false === $current_row ) {
		$next_row = 0;
	} else {
		$next_row = $current_row + 1;
	}

	return isset( $attributes[ $name ]['rows'][ $next_row ] );
}

/**
 * Resets the repeater block rows after the while loop.
 *
 * Similar to wp_reset_postdata(). Call this after the repeater loop.
 * For example:
 *
 * while ( block_rows( 'example-repeater-name' ) ) :
 *     block_row( 'example-repeater-name' );
 *     block_sub_field( 'example-field' );
 * endwhile;
 * reset_block_rows( 'example-repeater-name' );
 *
 * @param string $name The name of the repeater field.
 */
function reset_block_rows( $name ) {
	genesis_custom_blocks_pro()->loop()->reset( $name );
}

/**
 * Return the total amount of rows in a repeater.
 *
 * @param string $name The name of the repeater field.
 * @return int|bool The total amount of rows. False if the repeater isn't found.
 */
function block_row_count( $name ) {
	$attributes = genesis_custom_blocks()->loader->get_data( 'attributes' );

	if ( ! isset( $attributes[ $name ]['rows'] ) ) {
		return false;
	}

	return count( $attributes[ $name ]['rows'] );
}

/**
 * Return the index of the current repeater row.
 *
 * Note: The index is zero-based, which means that the first row in a repeater has
 * an index of 0, the second row has an index of 1, and so on.
 *
 * @param string $name (Optional) The name of the repeater field.
 * @return int|bool The index of the row. False if the repeater isn't found.
 */
function block_row_index( $name = '' ) {
	if ( '' === $name ) {
		$name = genesis_custom_blocks_pro()->loop()->active;
	}

	if ( ! isset( genesis_custom_blocks_pro()->loop()->loops[ $name ] ) ) {
		return false;
	}

	return genesis_custom_blocks_pro()->loop()->loops[ $name ];
}

/**
 * Return the value of a sub-field.
 *
 * @param string $name The name of the sub-field.
 * @param bool   $is_echo Whether to echo and return the field, or just return the field.
 *
 * @return mixed
 */
function block_sub_field( $name, $is_echo = true ) {
	$attributes = genesis_custom_blocks()->loader->get_data( 'attributes' );

	if ( ! is_array( $attributes ) ) {
		return null;
	}

	$config = genesis_custom_blocks()->loader->get_data( 'config' );

	if ( ! $config ) {
		return null;
	}

	$parent  = genesis_custom_blocks_pro()->loop()->active;
	$pointer = genesis_custom_blocks_pro()->loop()->get_row( $parent );

	if ( ! isset( $config->fields[ $parent ] ) ) {
		return null;
	}

	$control = null;

	// Get the value from the block attributes, with the correct type.
	if ( ! isset( $attributes[ $parent ]['rows'] ) ) {
		return null;
	}

	$parent_attributes = $attributes[ $parent ]['rows'];
	$row_attributes    = $parent_attributes[ $pointer ];

	if ( ! isset( $config->fields[ $parent ]->settings['sub_fields'][ $name ], $row_attributes[ $name ] ) ) {
		return null;
	}

	$field   = $config->fields[ $parent ]->settings['sub_fields'][ $name ];
	$control = $field->control;
	$value   = $row_attributes[ $name ];
	$value   = $field->cast_value( $value );

	/**
	 * Filters the value to be made available or echoed on the front-end template.
	 *
	 * @param mixed       $value   The value.
	 * @param string|null $control The type of the control, like 'user', or null if this is the 'className', which has no control.
	 * @param bool        $is_echo Whether or not this value will be echoed.
	 */
	$value = apply_filters( 'genesis_custom_blocks_field_value', $value, $control, $is_echo );

	if ( $is_echo ) {
		$value = $field->cast_value_to_string( $value );

		/*
		 * Escaping this value may cause it to break in some use cases.
		 * If this happens, retrieve the field's value using block_value(),
		 * and then output the field with a more suitable escaping function.
		 */
		echo wp_kses_post( $value );
	}

	return $value;
}

/**
 * Return the value of a sub-field, without echoing it.
 *
 * @param string $name The name of the sub-field.
 *
 * @uses block_field()
 *
 * @return mixed
 */
function block_sub_value( $name ) {
	return block_sub_field( $name, false );
}
