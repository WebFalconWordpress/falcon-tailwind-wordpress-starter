<?php
/**
 * Admin > Addons page for Pro.
 * Template of the single addon item.
 *
 * @since 1.6.7
 *
 * @var string $image                 Image URL.
 * @var array  $addon                 Addon data.
 * @var string $url                   Addon page URL.
 * @var string $button                Button HTML.
 * @var bool   $has_settings_link     Flag for addons with settings link.
 * @var string $settings_url          Addon settings link.
 * @var bool   $has_cap               Check is user has capability to manage addon.
 */

use WPForms\Admin\Education\Helpers;
use WPForms\Requirements\Requirements;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$addon['title']           = str_replace( ' Addon', '', $addon['title'] );
$licenses                 = [ 'basic', 'plus', 'pro', 'elite', 'agency', 'ultimate' ];
$addon_licenses           = $addon['license'];
$common_licenses          = array_intersect( $licenses, $addon_licenses );
$minimum_required_license = reset( $common_licenses );
$image_alt                = sprintf( /* translators: %s - addon title. */
	__( '%s logo', 'wpforms' ),
	$addon['title']
);

$badge = Helpers::get_addon_badge( $addon );

$item_classes = [
	'wpforms-addons-list-item',
	! empty( $badge ) ? 'has-badge' : '',
];

?>
<div class="<?php echo wpforms_sanitize_classes( $item_classes, true ); ?>">
	<div class="wpforms-addons-list-item-header">
		<img src="<?php echo esc_url( WPFORMS_PLUGIN_URL . 'assets/images/' . $addon['icon'] ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>">

		<div class="wpforms-addons-list-item-header-meta">
			<div class="wpforms-addons-list-item-header-meta-title">
				<?php
				printf(
					'<a href="%1$s" title="%2$s" target="_blank" rel="noopener noreferrer" class="addon-link">%3$s</a>',
					esc_url( $url ),
					esc_attr__( 'Learn more', 'wpforms' ),
					esc_html( $addon['title'] )
				);
				?>

				<?php echo $badge; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<div class="wpforms-addons-list-item-header-meta-excerpt">
				<?php echo esc_html( $addon['excerpt'] ); ?>
			</div>
		</div>
	</div>

	<?php if ( $has_cap ) : ?>
		<div class="wpforms-addons-list-item-footer wpforms-addons-list-item-footer-<?php echo esc_attr( $addon['status'] ); ?>" data-plugin="<?php echo esc_attr( $addon['status'] === 'missing' ? $addon['url'] : $addon['path'] ); ?>" data-type="addon">
			<?php if ( $addon['status'] === 'incompatible' ) : ?>
				<div class="wpforms-addons-list-item-error">
					<div class="wpforms-addons-list-item-error-msg">
						<?php esc_html_e( 'This addon is not compatible.', 'wpforms' ); ?>
					</div>

					<div class="wpforms-addons-list-item-error-desc">
						<?php echo wp_kses_post( Requirements::get_instance()->get_addon_compatible_message( $addon['path'] ) ); ?>
					</div>
				</div>
			<?php else : ?>
				<div>
					<?php if ( $addon['action'] === 'upgrade' ) : ?>
						<?php Helpers::print_badge( $minimum_required_license, 'lg' ); ?>
					<?php endif; ?>

					<?php if ( $has_settings_link && $addon['action'] !== 'upgrade' ) : ?>
						<a href="<?php echo esc_url( $settings_url ); ?>" class="wpforms-addons-list-item-footer-settings-link">
							<?php esc_html_e( 'Settings', 'wpforms' ); ?>
						</a>
					<?php endif; ?>
				</div>

				<div class="wpforms-addons-list-item-footer-actions">
					<?php echo $button; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
