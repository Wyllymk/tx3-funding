<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="max-h-screen">
    <div class="mt-20 mx-auto max-w-sm md:max-w-5xl flex flex-col justify-center items-center">

        <?php
	if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

        <?php if ( $order->has_status( 'failed' ) ) : ?>

        <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
            <?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?>
        </p>

        <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
            <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>"
                class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
            <?php if ( is_user_logged_in() ) : ?>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"
                class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
            <?php endif; ?>
        </p>

        <?php else : ?>

        <div class="flex flex-col items-center align-center justify-center text-white">
            <h1 class="text-center text-white font-black">
                <span class="font-light">
                    <?php esc_html_e('Your order ', 'tx3-funding'); ?>
                </span>
                <span class="text-neon-lime font-light">
                    <?php esc_html_e('#', 'tx3-funding'); ?><?php echo $order->get_order_number();?><br>
                </span>
                <?php esc_html_e('has been successfully placed!', 'tx3-funding'); ?>
            </h1>
            <img decoding="async" src="<?php echo get_template_directory_uri(); ?>/assets/img/tick.svg" alt="tick"
                class="my-0 size-40 sm:mr-2">
            <h4 class="text-white">
                <?php esc_html_e('Kindly use your email and password to login to your personal area', 'tx3-funding'); ?>
            </h4>
            <h6 class="">
                <?php esc_html_e('Now you can track the progress of your order in your personal account.', 'tx3-funding'); ?>
            </h6>
            <div class="flex space-x-4 my-4">
                <a class="no-underline font-bold rounded-3xl px-6 py-2.5 border-2 border-neon-lime bg-transparent !text-white hover:bg-neon-lime hover:!text-black transition-all duration-300"
                    href="<?php echo esc_url( site_url( '/my-account/' ) ); ?>">
                    <?php esc_html_e('To your personal area', 'tx3-funding'); ?>
                </a>

                <a class="font-bold no-underline rounded-3xl px-8 md:px-12 py-2.5 border-2 border-neon-lime bg-neon-lime !text-black hover:bg-transparent hover:border-neon-lime hover:!text-white transition-all duration-300"
                    href="<?php echo esc_url( site_url( '/' ) ); ?>">
                    <?php esc_html_e('Main page', 'tx3-funding'); ?>
                </a>

            </div>
        </div>

        <?php endif; ?>

        <?php else : ?>

        <?php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); ?>

        <?php endif; ?>

    </div>
</div>