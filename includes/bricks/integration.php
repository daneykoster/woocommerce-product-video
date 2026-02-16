<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register Bricks Elements
 */
add_action( 'bricks/register_elements', function() {
    // Explicitly include the class file here
    require_once __DIR__ . '/elements/product-video-gallery.php';

    // Register the element
    if ( class_exists( 'Bricks_Product_Video_Gallery' ) ) {
        \Bricks\Elements::register_element( new \Bricks_Product_Video_Gallery() );
    }
} );
