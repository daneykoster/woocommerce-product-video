<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register Bricks Elements
 */
// Function to register the element
function wcpv_register_bricks_element() {
    $element_file = WC_PRODUCT_VIDEO_PATH . 'includes/bricks/elements/product-video-gallery.php';
    
    if ( file_exists( $element_file ) ) {
        require_once $element_file;
        
        if ( class_exists( 'Bricks_Product_Video_Gallery' ) && class_exists( '\Bricks\Elements' ) ) {
            // Check if already registered to avoid error
            if ( ! array_key_exists( 'product_video_gallery', \Bricks\Elements::$elements ) ) {
                 \Bricks\Elements::register_element( new \Bricks_Product_Video_Gallery() );
            }
        }
    }
}

add_action( 'init', 'wcpv_register_bricks_element', 11 );
add_action( 'bricks/register_elements', 'wcpv_register_bricks_element' );
