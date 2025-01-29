/* global wpforms, wpformsElementorVars, wpformsModernFileUpload, wpformsRecaptchaLoad, grecaptcha, WPFormsRepeaterField, WPFormsStripePaymentElement */

'use strict';

/**
 * WPForms integration with Elementor on the frontend.
 *
 * @since 1.6.2 Moved from `wpforms-elementor.js`
 */
var WPFormsElementorFrontend = window.WPFormsElementorFrontend || ( function( document, window, $ ) {

	/**
	 * Public functions and properties.
	 *
	 * @since 1.6.2
	 *
	 * @type {object}
	 */
	var app = {

		/**
		 * Flag to force load ChoicesJS.
		 *
		 * @since 1.9.0
		 *
		 * @type {boolean}
		 */
		forceLoadChoices: false,

		/**
		 * Flag to force set Stripe.
		 *
		 * @since 1.9.3
		 *
		 * @type {boolean}
		 */
		forceSetStripe: false,

		/**
		 * Start the engine.
		 *
		 * @since 1.6.2
		 */
		init: function() {

			app.events();
		},

		/**
		 * Register JS events.
		 *
		 * @since 1.6.2
		 */
		events() {
			window.addEventListener( 'elementor/popup/show', function( event ) {

				const $modal = $( '#elementor-popup-modal-' + event.detail.id ),
					$form = $modal.find( '.wpforms-form' );

				if ( ! $form.length ) {
					return;
				}

				app.forceSetStripe = true;

				app.initFields( $form );
			} );

			// Force load ChoicesJS for elementor popup.
			$( document ).on( 'elementor/popup/show', () => {
				app.forceLoadChoices = true;

				wpforms.loadChoicesJS();
			} );

			$( document ).on( 'wpformsBeforeLoadElementChoices', ( event, el ) => {
				// Do not initialize on elementor popup.
				if ( ! app.isFormInElementorPopup( el ) || app.forceLoadChoices ) {
					return;
				}

				event.preventDefault();
			} );

			$( document ).on( 'wpformsBeforeStripePaymentElementSetup', ( event, el ) => {
				// Do not initialize on elementor popup.
				if ( ! app.isFormInElementorPopup( el ) || app.forceSetStripe ) {
					return;
				}

				event.preventDefault();
			} );
		},

		/**
		 * Check if the form is in Elementor popup.
		 *
		 * @since 1.9.3
		 *
		 * @param {Object} form Form element.
		 *
		 * @return {boolean} True if the form is in Elementor popup, false otherwise.
		 */
		isFormInElementorPopup( form ) {
			return $( form ).parents( 'div[data-elementor-type="popup"]' ).length;
		},

		/**
		 * Init all things for WPForms.
		 *
		 * @since 1.6.2
		 *
		 * @param {object} $form jQuery selector.
		 */
		initFields: function( $form ) {

			// Init WPForms things.
			wpforms.ready();

			// Init `Modern File Upload` field.
			if ( 'undefined' !== typeof wpformsModernFileUpload ) {
				wpformsModernFileUpload.init();
			}

			// Init CAPTCHA.
			if ( 'undefined' !== typeof wpformsRecaptchaLoad ) {
				if ( 'recaptcha' === wpformsElementorVars.captcha_provider && 'v3' === wpformsElementorVars.recaptcha_type ) {
					if ( 'undefined' !== typeof grecaptcha ) {
						grecaptcha.ready( wpformsRecaptchaLoad );
					}
				} else {
					wpformsRecaptchaLoad();
				}
			}

			// Init Repeater fields.
			if ( 'undefined' !== typeof WPFormsRepeaterField ) {
				WPFormsRepeaterField.ready();
			}

			// Init Stripe payment.
			if ( 'undefined' !== typeof WPFormsStripePaymentElement ) {
				WPFormsStripePaymentElement.setupStripeForm( $form );
			}

			// Register a custom event.
			$( document ).trigger( 'wpforms_elementor_form_fields_initialized', [ $form ] );
		},
	};

	return app;

}( document, window, jQuery ) );

// Initialize.
WPFormsElementorFrontend.init();
