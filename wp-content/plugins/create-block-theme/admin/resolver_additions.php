<?php

function augment_resolver_with_utilities() {

	//Ultimately it is desireable for Core to have this functionality natively.
	// In the meantime we are patching the functionality we are expecting into the Theme JSON Resolver here
	if ( ! class_exists( 'WP_Theme_JSON_Resolver' ) ) {
		return;
	}

	class MY_Theme_JSON_Resolver extends WP_Theme_JSON_Resolver {

		/**
		 * Export the combined (and flattened) THEME and CUSTOM data.
		 *
		 * @param string $content ['all', 'current', 'user'] Determines which settings content to include in the export.
		 * @param array $extra_theme_data Any theme json extra data to be included in the export.
		 * All options include user settings.
		 * 'current' will include settings from the currently installed theme but NOT from the parent theme.
		 * 'all' will include settings from the current theme as well as the parent theme (if it has one)
		 * 'variation' will include just the user custom styles and settings.
		 */
		public static function export_theme_data( $content, $extra_theme_data = null ) {
			if ( class_exists( 'WP_Theme_JSON_Gutenberg' ) ) {
				$theme = new WP_Theme_JSON_Gutenberg();
			} else {
				$theme = new WP_Theme_JSON();
			}

			if ( 'all' === $content && wp_get_theme()->parent() ) {
				// Get parent theme.json.
				$parent_theme_json_data = static::read_json_file( static::get_file_path_from_theme( 'theme.json', true ) );
				$parent_theme_json_data = static::translate( $parent_theme_json_data, wp_get_theme()->parent()->get( 'TextDomain' ) );

				// Get the schema from the parent JSON.
				$schema = $parent_theme_json_data['$schema'];
				if ( array_key_exists( 'schema', $parent_theme_json_data ) ) {
					$schema = $parent_theme_json_data['$schema'];
				}

				if ( class_exists( 'WP_Theme_JSON_Gutenberg' ) ) {
					$parent_theme = new WP_Theme_JSON_Gutenberg( $parent_theme_json_data );
				} else {
					$parent_theme = new WP_Theme_JSON( $parent_theme_json_data );
				}
				$theme->merge( $parent_theme );
			}

			if ( 'all' === $content || 'current' === $content ) {
				$theme_json_data = static::read_json_file( static::get_file_path_from_theme( 'theme.json' ) );
				$theme_json_data = static::translate( $theme_json_data, wp_get_theme()->get( 'TextDomain' ) );

				// Get the schema from the parent JSON.
				if ( array_key_exists( 'schema', $theme_json_data ) ) {
					$schema = $theme_json_data['$schema'];
				}

				if ( class_exists( 'WP_Theme_JSON_Gutenberg' ) ) {
					$theme_theme = new WP_Theme_JSON_Gutenberg( $theme_json_data );
				} else {
					$theme_theme = new WP_Theme_JSON( $theme_json_data );
				}
				$theme->merge( $theme_theme );
			}

			if ( class_exists( 'WP_Theme_JSON_Resolver_Gutenberg' ) ) {
				$theme->merge( WP_Theme_JSON_Resolver_Gutenberg::get_user_data() );
			} else {
				$theme->merge( static::get_user_data() );
			}

			// Merge the extra theme data received as a parameter
			if ( ! empty( $extra_theme_data ) ) {
				if ( class_exists( 'WP_Theme_JSON_Gutenberg' ) ) {
					$extra_data = new WP_Theme_JSON_Gutenberg( $extra_theme_data );
				} else {
					$extra_data = new WP_Theme_JSON( $extra_theme_data );
				}
				$theme->merge( $extra_data );
			}

			$data = $theme->get_data();

			// Add the schema.
			if ( empty( $schema ) ) {
				global $wp_version;
				$theme_json_version = 'wp/' . substr( $wp_version, 0, 3 );
				if ( defined( 'IS_GUTENBERG_PLUGIN' ) ) {
					$theme_json_version = 'trunk';
				}
				$schema = 'https://schemas.wp.org/' . $theme_json_version . '/theme.json';
			}
			$data['$schema'] = $schema;
			$theme_json      = wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
			return preg_replace( '~(?:^|\G)\h{4}~m', "\t", $theme_json );

		}

	}
}

add_action( 'plugins_loaded', 'augment_resolver_with_utilities' );
