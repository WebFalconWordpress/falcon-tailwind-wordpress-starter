<?php
/**
 * The Genesis Pro subscription response.
 *
 * @package   Genesis\CustomBlocksPro
 * @copyright Copyright(c) 2022, Genesis Custom Blocks Pro
 * @license   http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace Genesis\CustomBlocksPro\Admin;

use stdClass;

/**
 * Class Subscription
 */
class SubscriptionResponse {

	/**
	 * Endpoint to validate the Genesis Pro subscription key.
	 *
	 * @var string
	 */
	const ENDPOINT = 'https://wp-product-info.wpesvc.net/v1/plugins/genesis-custom-blocks-pro/subscriptions/';

	/**
	 * The code expected in a success response.
	 *
	 * @var string
	 */
	const SUCCESS_CODE = 200;

	/**
	 * Whether the subscription key is valid.
	 *
	 * @var bool
	 */
	private $is_valid = false;

	/**
	 * The error code, if any.
	 *
	 * @var string|null
	 */
	private $error_code;

	/**
	 * The product info.
	 *
	 * @var stdClass|null
	 */
	private $product_info;

	/**
	 * Constructs the class.
	 *
	 * @param string $subscription_key The subscription key to check.
	 */
	public function __construct( $subscription_key ) {
		$this->evaluate( $subscription_key );
	}

	/**
	 * Evaluates the response, storing the response body and a possible error message.
	 *
	 * @param string $subscription_key The subscription key to check.
	 */
	public function evaluate( $subscription_key ) {
		// So get_plugin_data is always defined.
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		$response = wp_remote_get(
			self::ENDPOINT . $subscription_key,
			[
				'timeout'    => defined( 'DOING_CRON' ) && DOING_CRON ? 30 : 3,
				'user-agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . get_bloginfo( 'url' ),
				'body'       => [
					'version' => isset( $current_plugin_data['Version'] ) ? $current_plugin_data['Version'] : genesis_custom_blocks_pro()->get_version(),
				],
			]
		);

		if ( empty( $subscription_key ) ) {
			$this->error_code = Subscription::ERROR_CODE_NO_KEY;
			update_option( Subscription::PRODUCT_INFO_OPTION_EXPIRATION, time() + MINUTE_IN_SECONDS * 5, false );
			update_option( Subscription::PRODUCT_INFO_OPTION_NAME, $this, false );
			return;
		}

		if ( is_wp_error( $response ) || self::SUCCESS_CODE !== wp_remote_retrieve_response_code( $response ) ) {
			if ( is_wp_error( $response ) ) {
				$this->error_code = $response->get_error_code();
			} else {
				$response_body    = json_decode( wp_remote_retrieve_body( $response ), false );
				$this->error_code = ! empty( $response_body->error_code ) ? $response_body->error_code : 'unknown';
			}

			// Cache an empty object for 5 minutes to give the product info API time to recover.
			update_option( Subscription::PRODUCT_INFO_OPTION_EXPIRATION, time() + MINUTE_IN_SECONDS * 5, false );
			update_option( Subscription::PRODUCT_INFO_OPTION_NAME, $this, false );
			return;
		}

		$this->is_valid     = true;
		$this->product_info = new stdClass();

		// Delete any existing API error codes since we have a valid API response.
		$current_plugin_data    = get_plugin_data( genesis_custom_blocks_pro()->get_file() );
		$current_plugin_version = isset( $current_plugin_data['Version'] ) ? $current_plugin_data['Version'] : genesis_custom_blocks_pro()->get_version();
		$response_body          = json_decode( wp_remote_retrieve_body( $response ) );

		if ( ! is_object( $response_body ) ) {
			$response_body = new stdClass();
		}

		$response_body->name          = isset( $response_body->name ) ? $response_body->name : __( 'Genesis Custom Blocks Pro', 'genesis-custom-blocks-pro' );
		$response_body->stable_tag    = isset( $response_body->stable_tag ) ? $response_body->stable_tag : $current_plugin_version;
		$response_body->new_version   = $response_body->stable_tag;
		$response_body->download_link = isset( $response_body->download_link ) ? $response_body->download_link : '';
		$response_body->package       = isset( $response_body->download_link ) ? $response_body->download_link : '';
		$response_body->slug          = genesis_custom_blocks_pro()->get_slug();
		$this->product_info           = $response_body;

		update_option( Subscription::PRODUCT_INFO_OPTION_EXPIRATION, time() + HOUR_IN_SECONDS * 12, false );
		update_option( Subscription::PRODUCT_INFO_OPTION_NAME, $this, false );
	}

	/**
	 * Gets whether the subscription key is valid.
	 *
	 * @return bool
	 */
	public function is_valid() {
		return $this->is_valid;
	}

	/**
	 * Gets the error code, if any.
	 *
	 * @return string|null
	 */
	public function get_error_code() {
		return $this->error_code;
	}

	/**
	 * Gets the product info, or null if there isn't any.
	 *
	 * @return stdClass|null
	 */
	public function get_product_info() {
		return $this->product_info;
	}
}
