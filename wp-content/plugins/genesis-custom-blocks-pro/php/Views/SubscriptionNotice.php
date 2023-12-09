<?php
/**
 * Genesis Custom Blocks subscription notice.
 *
 * @package   Genesis\CustomBlocksPro
 * @copyright Copyright(c) 2022, Genesis Custom Blocks Pro
 * @license   http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

?>
<div class="notice notice-error">
	<p>
		<?php esc_html_e( 'Please enter your Genesis Pro Subscription key in order to receive updates for Genesis Custom Blocks.', 'genesis-custom-blocks-pro' ); ?>
	</p>
	<p>
		<?php
		printf(
			/* translators: %1$s: link to the account portal */
			esc_html__( 'Get your subscription key from your %1$s.', 'genesis-custom-blocks-pro' ),
			wp_kses(
				sprintf(
					'<a href="https://my.wpengine.com/products/genesis_pro" target="_blank" rel="noreferrer noopener">%1$s</a>',
					esc_html__( 'WP Engine Account Portal', 'genesis-custom-blocks-pro' )
				),
				[
					'a' => [
						'href'   => [],
						'target' => [],
						'rel'    => [],
					],
				]
			)
		);
		?>
	</p>
</div>
