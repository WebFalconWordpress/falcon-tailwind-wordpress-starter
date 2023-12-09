<?php
/**
 * Handles Repeater filters.
 *
 * @package Genesis\CustomBlocksPro
 */

namespace Genesis\CustomBlocksPro\Blocks;

use Genesis\CustomBlocks\Blocks\Block;
use Genesis\CustomBlocks\Blocks\Field;
use Genesis\CustomBlocks\ComponentAbstract;

/**
 * Class RepeaterFilter
 */
class RepeaterFilter extends ComponentAbstract {

	/**
	 * Initiates the class.
	 */
	public function init() {}

	/**
	 * Registers all the hooks.
	 */
	public function register_hooks() {
		add_filter( 'genesis_custom_blocks_field_to_array', [ $this, 'to_array_sub_fields' ], 10, 2 );
		add_filter( 'genesis_custom_blocks_settings_from_array', [ $this, 'from_array_sub_fields' ] );
		add_filter( 'genesis_custom_blocks_template_attributes', [ $this, 'add_default_values' ], 10, 2 );
	}

	/**
	 * Handles the sub-fields setting used by the Repeater when converting to an array.
	 *
	 * @param array $config   The field config.
	 * @param array $settings The field settings.
	 * @return array $config The filtered field config, with any sub_fields added.
	 */
	public function to_array_sub_fields( $config, $settings ) {
		if ( ! isset( $settings['sub_fields'] ) ) {
			return $config;
		}

		foreach ( $settings['sub_fields'] as $key => $field ) {
			if ( method_exists( $field, 'to_array' ) ) {
				$config['sub_fields'][ $key ] = $field->to_array();
			}
		}

		return $config;
	}

	/**
	 * Handles the sub-fields setting used by the Repeater when converting from an array.
	 *
	 * @param array $settings The field settings.
	 * @return array $settings The filtered field settings, with any sub_fields added.
	 */
	public function from_array_sub_fields( $settings ) {
		if ( ! isset( $settings['sub_fields'] ) ) {
			return $settings;
		}

		foreach ( $settings['sub_fields'] as $key => $field ) {
			$settings['sub_fields'][ $key ] = new Field( $field );
		}

		return $settings;
	}

	/**
	 * Adds the default values for the Repeater.
	 *
	 * @param array   $attributes The block attributes.
	 * @param Field[] $fields     The block fields.
	 * @return array $attributes The filtered attributes.
	 */
	public function add_default_values( $attributes, $fields ) {
		if ( is_admin() ) {
			return $attributes;
		}

		foreach ( $fields as $field ) {
			if ( ! isset( $field->settings['sub_fields'], $attributes[ $field->name ]['rows'] ) ) {
				continue;
			}

			$sub_field_settings = $field->settings['sub_fields'];
			$rows               = $attributes[ $field->name ]['rows'];

			// In each row, apply a field's default value if a value doesn't exist in the attributes.
			foreach ( $rows as $row_index => $row ) {
				foreach ( $sub_field_settings as $sub_field_name => $sub_field ) {
					if ( ! isset( $row[ $sub_field_name ] ) && isset( $sub_field_settings[ $sub_field_name ]->settings['default'] ) ) {
						$rows[ $row_index ][ $sub_field_name ] = $sub_field_settings[ $sub_field_name ]->settings['default'];
					}
				}
			}

			$attributes[ $field->name ]['rows'] = $rows;
		}

		return $attributes;
	}
}
