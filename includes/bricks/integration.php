<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register Bricks Elements
 */
add_action( 'bricks/register_elements', function() {
    error_log( 'WCPV: bricks/register_elements fired.' );
    
    // Explicitly include the class file here
    require_once __DIR__ . '/elements/product-video-gallery.php';

    // Register the element
    if ( class_exists( 'Bricks_Product_Video_Gallery' ) ) {
        error_log( 'WCPV: Registering Bricks_Product_Video_Gallery.' );
        \Bricks\Elements::register_element( new \Bricks_Product_Video_Gallery() );
    } else {
        error_log( 'WCPV: Bricks_Product_Video_Gallery class not found.' );
    }
} );
