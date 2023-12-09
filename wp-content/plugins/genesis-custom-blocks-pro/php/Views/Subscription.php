<?php
/**
 * Genesis Custom Blocks Pro Subscription page.
 *
 * @package   Genesis\CustomBlocksPro
 * @copyright Copyright(c) 2022, Genesis Custom Blocks Pro
 * @license   http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

use Genesis\CustomBlocksPro\Admin\Subscription;

if ( genesis_custom_blocks_pro()->admin->subscription->get_subscription()->is_valid() ) {
	printf(
		'<p>%1$s</p>',
		esc_html__( 'Your Genesis Pro subscription key is valid.', 'genesis-custom-blocks-pro' )
	);
} else {
	printf(
		'<p>%1$s</p>',
		esc_html__( 'No Genesis Pro subscription key was found for this installation.', 'genesis-custom-blocks-pro' )
	);
}

?>
<table class="form-table">
	<tr valign="top">
		<th scope="row">
			<label for="<?php echo esc_attr( Subscription::SUBSCRIPTION_KEY_OPTION_NAME ); ?>"><?php esc_html_e( 'Subscription key', 'genesis-custom-blocks-pro' ); ?></label>
		</th>
		<td>
			<input type="password" name="<?php echo esc_attr( Subscription::SUBSCRIPTION_KEY_OPTION_NAME ); ?>" id="<?php echo esc_attr( Subscription::SUBSCRIPTION_KEY_OPTION_NAME ); ?>" class="regular-text" value="<?php echo esc_attr( get_option( Subscription::SUBSCRIPTION_KEY_OPTION_NAME ) ); ?>" />
		</td>
	</tr>
</table>
