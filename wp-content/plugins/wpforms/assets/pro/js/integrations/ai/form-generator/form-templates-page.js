/* global wpforms_ai_form_templates_page */
// noinspection ES6ConvertVarToLetConst
/**
 * WPForms AI Form Templates Page.
 *
 * @since 1.9.3
 */
// eslint-disable-next-line no-var
var WPFormsAIFormTemplates = window.WPFormsAIFormTemplates || ( function( document, window, $ ) {
	/**
	 * Public functions and properties.
	 *
	 * @since 1.9.3
	 *
	 * @type {Object}
	 */
	const app = {
		/**
		 * Start the engine.
		 *
		 * @since 1.9.3
		 */
		init() {
			app.events();

			if ( wpforms_ai_form_templates_page.isLicenseActive ) {
				return;
			}

			// Update the button if the license is not active.
			$( '#wpforms-template-generate .wpforms-template-generate' )
				.removeClass( 'wpforms-template-generate' )
				.addClass( 'education-modal' )
				.data( 'action', 'license' )
				.data( 'field-name', 'AI Forms' );
		},

		/**
		 * Events.
		 *
		 * @since 1.9.3
		 */
		events() {
			$( document ).on( 'click', '.wpforms-template-generate', app.openNewFormGenerator );
		},

		/**
		 * Open the New Form Generator.
		 *
		 * @since 1.9.3
		 *
		 * @param {Object} e Event object.
		 */
		openNewFormGenerator( e ) {
			e.preventDefault();

			window.location = wpforms_ai_form_templates_page.newFormUrl;
		},
	};

	return app;
}( document, window, jQuery ) );

// Initialize.
WPFormsAIFormTemplates.init();
