<?php
/**
 * Plugin Name: WooCommerce Product Video
 * Plugin URI:  https://daneykoster.nl
 * Description: Voegt product video's toe aan de WooCommerce product gallery. Werkt met Bricks builder 2.2
 * Version:     1.1.2
 * Author:      Daney Koster
 * Text Domain: wc-product-video
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WC_PRODUCT_VIDEO_PATH', plugin_dir_path( __FILE__ ) );
define( 'WC_PRODUCT_VIDEO_URL', plugin_dir_url( __FILE__ ) );

// Include admin functionality
if ( is_admin() ) {
	require_once WC_PRODUCT_VIDEO_PATH . 'admin/product-meta.php';
	
	add_action( 'admin_enqueue_scripts', 'wcpv_admin_enqueue_scripts' );
}

function wcpv_admin_enqueue_scripts() {
	wp_enqueue_media();
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'wcpv-admin-script', WC_PRODUCT_VIDEO_URL . 'assets/js/admin-script.js', array( 'jquery', 'jquery-ui-sortable' ), '1.1.2', true );
	wp_enqueue_style( 'wcpv-admin-style', WC_PRODUCT_VIDEO_URL . 'assets/css/admin.css', array(), '1.1.2' );
}

// Include frontend functionality
require_once WC_PRODUCT_VIDEO_PATH . 'frontend/gallery-integration.php';
