<?php

class React_App {
	public static function bootstrap() {
		// Load the required WordPress packages.
		// Automatically load imported dependencies and assets version.
		$asset_file = include plugin_dir_path( __DIR__ ) . 'build/index.asset.php';

		// Enqueue CSS dependencies of the scripts included in the build.
		foreach ( $asset_file['dependencies'] as $style ) {
			wp_enqueue_style( $style );
		}

		// Enqueue CSS of the app
		wp_enqueue_style( 'create-block-theme-app', plugins_url( 'build/index.css', __DIR__ ), array(), $asset_file['version'] );

		// Load our app.js.
		array_push( $asset_file['dependencies'], 'wp-i18n' );
		wp_enqueue_script( 'create-block-theme-app', plugins_url( 'build/index.js', __DIR__ ), $asset_file['dependencies'], $asset_file['version'] );

		// Enable localization in the app.
		wp_set_script_translations( 'create-block-theme-app', 'create-block-theme' );

		// Define the global variable that will be used to pass data from PHP to JS.
		$script_content = <<<EOT
		window.createBlockTheme = {
			googleFontsDataUrl: '%s',
			adminUrl: '%s',
			themeUrl: '%s',
		};
		EOT;

		// Pass the data to the JS.
		wp_add_inline_script(
			'create-block-theme-app',
			sprintf(
				$script_content,
				esc_url( plugins_url( 'assets/google-fonts/fallback-fonts-list.json', __DIR__ ) ),
				esc_url( admin_url() ),
				esc_url( get_template_directory_uri() )
			),
			'before'
		);
	}
}
