<?php

namespace WPForms\Pro\Integrations\AI\Admin\Pages;

use WPForms\Pro\Integrations\AI\Admin\Builder\Enqueues;
use WPForms\Pro\Integrations\AI\Helpers;

/**
 * Enqueue assets on the Form Templates admin page in Pro.
 *
 * @since 1.9.2
 */
class Templates {

	/**
	 * The Builder enqueues class instance.
	 *
	 * @since 1.9.2
	 *
	 * @var Enqueues
	 */
	private $builder_enqueues;

	/**
	 * Initialize.
	 *
	 * @since 1.9.2
	 */
	public function init() {

		$this->hooks();

		$this->builder_enqueues = new Enqueues();
	}

	/**
	 * Register hooks.
	 *
	 * @since 1.9.2
	 */
	private function hooks() {

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueues' ] );
		add_action( 'wpforms_admin_form_templates_list_before', [ $this, 'output_card' ] );
	}

	/**
	 * Enqueue styles and scripts.
	 *
	 * @since 1.9.2
	 */
	public function enqueues() {

		$min = wpforms_get_min_suffix();

		wp_enqueue_style(
			'wpforms-ai-forms-admin',
			WPFORMS_PLUGIN_URL . "assets/pro/css/integrations/ai/form-templates-page{$min}.css",
			[],
			WPFORMS_VERSION
		);

		wp_enqueue_script(
			'wpforms-ai-form-templates-page',
			WPFORMS_PLUGIN_URL . "assets/pro/js/integrations/ai/form-generator/form-templates-page{$min}.js",
			[],
			WPFORMS_VERSION,
			true
		);

		wp_localize_script(
			'wpforms-ai-form-templates-page',
			'wpforms_ai_form_templates_page',
			[
				'newFormUrl'      => admin_url( 'admin.php?page=wpforms-builder&view=setup&ai-form' ),
				'isLicenseActive' => Helpers::is_license_active(),
			]
		);
	}

	/**
	 * Output Generate with AI card.
	 *
	 * @since 1.9.3
	 *
	 * @noinspection HtmlUnknownTarget
	 */
	public function output_card() {

		printf(
			'<div class="wpforms-template" id="wpforms-template-generate">
				<div class="wpforms-template-thumbnail">
					<div class="wpforms-template-thumbnail-placeholder">
						<img src="%1$s" alt="%2$s" loading="lazy">
					</div>
				</div>
				<div class="wpforms-template-name-wrap">
					<h3 class="wpforms-template-name categories has-access favorite slug subcategories fields" data-categories="all,new" data-subcategories="" data-fields="" data-has-access="1" data-favorite="" data-slug="generate">
						%2$s
					</h3>
					<span class="wpforms-badge wpforms-badge-sm wpforms-badge-inline wpforms-badge-purple wpforms-badge-rounded">%3$s</span>
				</div>
				<p class="wpforms-template-desc">
					%4$s
				</p>
				<div class="wpforms-template-buttons">
					<a href="#" class="wpforms-template-generate wpforms-btn wpforms-btn-md wpforms-btn-purple-dark">
						%5$s
					</a>
				</div>
			</div>',
			esc_url( WPFORMS_PLUGIN_URL ) . 'assets/images/integrations/ai/ai-feature-icon.svg',
			esc_html__( 'Generate With AI', 'wpforms' ),
			esc_html__( 'NEW!', 'wpforms' ),
			esc_html__( 'Write simple prompts to create complex forms catered to your specific needs.', 'wpforms' ),
			esc_html__( 'Generate Form', 'wpforms' )
		);
	}
}
