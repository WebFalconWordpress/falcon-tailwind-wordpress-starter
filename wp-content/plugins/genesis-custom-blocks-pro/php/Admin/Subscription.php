<?php
/**
 * Handle Genesis Pro subscription.
 *
 * @package   Genesis\CustomBlocksPro
 * @copyright Copyright(c) 2022, Genesis Custom Blocks Pro
 * @license   http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace Genesis\CustomBlocksPro\Admin;

use stdClass;
use Genesis\CustomBlocks\ComponentAbstract;

/**
 * Class Subscription
 */
class Subscription extends ComponentAbstract {

	/**
	 * Option name where the subscription key is stored for this and other Genesis Pro plugins.
	 *
	 * @var string
	 */
	const SUBSCRIPTION_KEY_OPTION_NAME = 'genesis_pro_subscription_key';

	/**
	 * Option name where the subscription endpoint response is stored.
	 *
	 * @var string
	 */
	const PRODUCT_INFO_OPTION_NAME = 'genesis_custom_blocks_pro_subscription_response';

	/**
	 * Error code when there is no subscription key.
	 *
	 * @var string
	 */
	const ERROR_CODE_NO_KEY = 'no-key';

	/**
	 * Option name where the subscription expiration is stored.
	 *
	 * @var string
	 */
	const PRODUCT_INFO_OPTION_EXPIRATION = 'genesis_custom_blocks_pro_subscription_expiration';

	/**
	 * Adds the component filter.
	 */
	public function register_hooks() {
		add_filter( 'pre_update_option_' . self::SUBSCRIPTION_KEY_OPTION_NAME, [ $this, 'save_subscription_key' ] );
		add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'check_for_updates' ] );
		add_filter( 'plugins_api', [ $this, 'custom_plugins_api' ], 10, 3 );
		add_action( 'after_plugin_row_' . genesis_custom_blocks_pro()->get_basename(), [ $this, 'render_plugin_row_notice' ], 9 );
	}

	/**
	 * Gets the subscription key to save if it's valid.
	 *
	 * @param string $key The subscription key that was submitted.
	 * @return string|false The subscription key if it was valid, or false.
	 */
	public function save_subscription_key( $key ) {
		$sanitized_new_key    = $this->sanitize_subscription_key( $key );
		$previously_saved_key = get_option( self::SUBSCRIPTION_KEY_OPTION_NAME );
		if ( empty( $sanitized_new_key ) ) {
			return false;
		}

		$subscription_response = $this->get_fresh_subscription_response( $sanitized_new_key );
		if ( $subscription_response->is_valid() && $previously_saved_key !== $sanitized_new_key ) {
			genesis_custom_blocks_pro()->admin->settings->prepare_notice( $this->get_subscription_success_notice() );
		}

		if ( ! $subscription_response->is_valid() ) {
			genesis_custom_blocks_pro()->admin->settings->prepare_notice( $this->get_subscription_invalid_notice( $subscription_response->get_error_code() ) );
		}

		return $subscription_response->is_valid() ? $sanitized_new_key : false;
	}

	/**
	 * Gets a new subscription response, not a cached one.
	 *
	 * @param string $key The subscription key to check.
	 * @return SubscriptionResponse The subscription response.
	 */
	public function get_fresh_subscription_response( $key ) {
		return new SubscriptionResponse( $key );
	}

	/**
	 * Admin notice for correct subscription details.
	 *
	 * @return string The success notice for the subscription.
	 */
	public function get_subscription_success_notice() {
		return sprintf(
			'<div class="notice notice-success"><p>%1$s</p></div>',
			esc_html__( 'Your Genesis Pro subscription key was successfully activated!', 'genesis-custom-blocks-pro' )
		);
	}

	/**
	 * Admin notice for incorrect subscription details.
	 *
	 * @param string $error_code The error code from the endpoint.
	 * @return string The error notice to display in /wp-admin.
	 */
	public function get_subscription_invalid_notice( $error_code ) {
		return sprintf(
			'<div class="notice notice-error"><p>%1$s</p></div>',
			$this->get_subscription_invalid_message( $error_code )
		);
	}

	/**
	 * Admin message for incorrect subscription details.
	 *
	 * Forked from Genesis Blocks Pro.
	 *
	 * @param string $error_code The error code from the endpoint.
	 * @return string The error message to display.
	 */
	public function get_subscription_invalid_message( $error_code ) {
		$account_portal_link = sprintf(
			'<a href="%1$s" target="_blank" rel="noreferrer noopener">%2$s</a>',
			esc_url( 'https://my.wpengine.com/products/genesis_pro' ),
			esc_html__( 'WP Engine Account Portal', 'genesis-custom-blocks-pro' )
		);

		switch ( $error_code ) {
			case 'key-unknown':
				return sprintf(
					/* translators: %1$s: Link to account portal. */
					esc_html__( 'The subscription key you entered appears to be invalid or is not associated with this product. Please verify the key you have saved here matches the key in your %1$s.', 'genesis-custom-blocks-pro' ),
					$account_portal_link
				);

			case 'key-invalid':
				return sprintf(
					/* translators: %1$s: Link to account portal. */
					esc_html__( 'The subscription key you entered is invalid. Get your subscription key in the %1$s.', 'genesis-custom-blocks-pro' ),
					$account_portal_link
				);

			case 'key-deleted':
				return sprintf(
					/* translators: %1$s: Link to account portal. */
					esc_html__( 'Your subscription key was regenerated in the %1$s but was not updated in this settings page. Update your subscription key here to receive updates.', 'genesis-custom-blocks-pro' ),
					$account_portal_link
				);

			case 'subscription-expired':
				return sprintf(
					/* translators: %1$s: Link to account portal. */
					esc_html__( 'Your Genesis Pro subscription has expired. %1$s now.', 'genesis-custom-blocks-pro' ),
					sprintf(
						'<a href="%1$s" target="_blank" rel="noreferrer noopener">%2$s</a>',
						esc_url( 'https://my.wpengine.com/modify_plan' ),
						esc_html__( 'Renew', 'genesis-custom-blocks-pro' )
					)
				);

			case 'subscription-notfound':
				return esc_html__( 'A valid subscription for your subscription key was not found. Please contact support.', 'genesis-custom-blocks-pro' );

			case 'product-unknown':
				return esc_html__( 'The product you requested information for is unknown. Please contact support.', 'genesis-custom-blocks-pro' );

			case self::ERROR_CODE_NO_KEY:
				return sprintf(
					/* translators: %1$s: Link to the settings page. %2$s: The settings page text that is linked. */
					__( 'There is no Genesis Pro subscription key entered. To get updates and the latest features, please enter your subscription key in the <a href="%1$s" target="_blank" rel="noreferrer noopener">%2$s</a>.', 'genesis-custom-blocks-pro' ),
					esc_url(
						admin_url(
							add_query_arg(
								[
									'post_type' => genesis_custom_blocks()->get_post_type_slug(),
									'page'      => 'genesis-custom-blocks-settings',
								],
								'edit.php'
							)
						)
					),
					esc_html__( 'Genesis Custom Blocks settings', 'genesis-custom-blocks-pro' )
				);

			default:
				return sprintf(
					/* translators: %1$s: Link to account portal. */
					esc_html__( 'There was an unknown error connecting to the update service. Please ensure the key you have saved here matches the key in your %1$s. This issue could be temporary. Please contact support if this error persists.', 'genesis-custom-blocks-pro' ),
					$account_portal_link
				);
		}
	}

	/**
	 * Checks the WPE plugin info API for new versions of the plugin and returns the data required to update this plugin.
	 *
	 * Forked from Genesis Blocks Pro: https://github.com/studiopress/genesis-page-builder/blob/4cf15f2f7a273ea0aee824115005b9c95db48ded/includes/updates/update-functions.php#L24
	 *
	 * @param object $data WordPress update object.
	 * @return object $data An updated object if an update exists, default object if not.
	 */
	public function check_for_updates( $data ) {
		if ( empty( $data ) ) {
			return $data;
		}

		$product_info = $this->get_subscription()->get_product_info();
		if ( empty( $product_info->requires_at_least ) || empty( $product_info->stable_tag ) ) {
			return $data;
		}

		$main_plugin_file    = genesis_custom_blocks_pro()->get_file();
		$current_plugin_data = get_plugin_data( $main_plugin_file );
		$meets_wp_req        = version_compare( get_bloginfo( 'version' ), $product_info->requires_at_least, '>=' );

		// Only update the response if there's a newer version, otherwise WP shows an update notice for the same version.
		if ( $meets_wp_req && version_compare( $current_plugin_data['Version'], $product_info->stable_tag, '<' ) ) {
			$product_info->plugin = 'genesis-custom-blocks-pro/genesis-custom-blocks-pro.php';
			$data->response[ genesis_custom_blocks_pro()->get_basename() ] = $product_info;
		}

		return $data;
	}

	/**
	 * Gets a custom API response for updating the plugin and for displaying information about it in wp-admin.
	 *
	 * The `plugins_api` filter is documented in `wp-admin/includes/plugin-install.php`.
	 * Forked from Genesis Blocks Pro.
	 *
	 * @see https://github.com/studiopress/genesis-page-builder/blob/4cf15f2f7a273ea0aee824115005b9c95db48ded/includes/updates/update-functions.php#L61
	 *
	 * @param false|object|array $api    The response for the current WordPress.org Plugin Installation API request. Default false.
	 * @param string             $action The type of information being requested from the Plugin Installation API.
	 * @param object             $args   Plugin API arguments.
	 * @return false|object|array The plugin API response.
	 */
	public function custom_plugins_api( $api, $action, $args ) {
		unset( $action );
		if ( empty( $args->slug ) || dirname( genesis_custom_blocks_pro()->get_basename() ) !== $args->slug ) {
			return $api;
		}

		$product_info = $this->get_subscription()->get_product_info();
		if ( empty( $product_info ) ) {
			return $api;
		}

		$current_plugin_data        = get_plugin_data( genesis_custom_blocks_pro()->get_file() );
		$meets_wp_req               = version_compare( get_bloginfo( 'version' ), $product_info->requires_at_least, '>=' );
		$current_plugin_requires_wp = isset( $current_plugin_data['RequiresWP'] ) ? $current_plugin_data['RequiresWP'] : 5.0;
		$current_plugin_name        = __( 'Genesis Custom Blocks Pro', 'genesis-custom-blocks-pro' );

		$api                        = new stdClass();
		$api->author                = $current_plugin_name;
		$api->homepage              = 'https://studiopress.com';
		$api->name                  = isset( $product_info->name ) ? $product_info->name : $current_plugin_name;
		$api->requires              = isset( $product_info->requires_at_least ) ? $product_info->requires_at_least : $current_plugin_requires_wp;
		$api->sections['changelog'] = isset( $product_info->sections->changelog ) ? $product_info->sections->changelog : '<h4>1.0</h4><ul><li>' . __( 'Initial release.', 'genesis-custom-blocks-pro' ) . '</li></ul>';
		$api->slug                  = isset( $args->slug ) ? $args->slug : genesis_custom_blocks_pro()->get_slug();

		// Only pass along the update info if the requirements are met and there's actually a newer version.
		if ( $meets_wp_req && isset( $product_info->stable_tag, $product_info->download_link ) && version_compare( $current_plugin_data['Version'], $product_info->stable_tag, '<' ) ) {
			$api->version       = $product_info->stable_tag;
			$api->download_link = $product_info->download_link;
		}

		return $api;
	}

	/**
	 * Fetches and returns the plugin info from the WPE product info API.
	 *
	 * Forked from Genesis Blocks Pro: https://github.com/studiopress/genesis-page-builder/blob/4cf15f2f7a273ea0aee824115005b9c95db48ded/includes/updates/update-functions.php#L99
	 *
	 * @return stdClass|null The product info.
	 */
	public function get_product_info() {
		return $this->get_subscription()->get_product_info();
	}

	/**
	 * Gets the subscription data.
	 *
	 * If it's cached, gets it from the cache.
	 * Otherwise, gets a new response.
	 *
	 * @return SubscriptionResponse The subscription data.
	 */
	public function get_subscription() {
		if ( time() >= (int) get_option( self::PRODUCT_INFO_OPTION_EXPIRATION ) ) {
			delete_option( self::PRODUCT_INFO_OPTION_NAME );
			delete_option( self::PRODUCT_INFO_OPTION_EXPIRATION );
		}

		$cached_response = get_option( self::PRODUCT_INFO_OPTION_NAME );
		if ( $cached_response instanceof SubscriptionResponse ) {
			return $cached_response;
		}

		$sub_key = $this->sanitize_subscription_key( get_option( self::SUBSCRIPTION_KEY_OPTION_NAME ) );
		return $this->get_fresh_subscription_response( $sub_key );
	}

	/**
	 * Gets the sanitized subscription key.
	 *
	 * @param string $subscription_key The subscription key.
	 * @return string The sanitized key.
	 */
	public function sanitize_subscription_key( $subscription_key ) {
		return preg_replace( '/[^A-Za-z0-9_-]/', '', $subscription_key );
	}

	/**
	 * Renders a notice about an invalid subscription key in the plugins.php page plugin row.
	 *
	 * Forked from Genesis Block Pro: https://github.com/studiopress/genesis-page-builder/blob/4cf15f2f7a273ea0aee824115005b9c95db48ded/includes/updates/update-functions.php#L234
	 */
	public function render_plugin_row_notice() {
		$cached_response = get_option( self::PRODUCT_INFO_OPTION_NAME );
		if ( $cached_response instanceof SubscriptionResponse && ! $cached_response->is_valid() ) {
			remove_action( 'after_plugin_row_' . genesis_custom_blocks_pro()->get_basename(), 'wp_plugin_update_row' );

			?>
			<tr class="plugin-update-tr active" id="genesis-custom-blocks-pro-update" data-slug="genesis-custom-blocks-pro" data-plugin="genesis-custom-blocks-pro/genesis-custom-blocks-pro.php">
				<td colspan="3" class="plugin-update">
					<div class="update-message notice inline notice-error notice-alt">
						<p><?php echo wp_kses_post( $this->get_subscription_invalid_message( $cached_response->get_error_code() ) ); ?></p>
					</div>
				</td>
			</tr>
			<?php
		}
	}
}
