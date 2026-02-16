<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register Bricks Elements
 */
add_action( 'bricks/register_elements', function() {
    $element_files = [
        __DIR__ . '/elements/product-video-gallery.php',
    ];

    foreach ( $element_files as $file ) {
        if ( file_exists( $file ) ) {
            require_once $file;
        }
    }

    if ( class_exists( 'Bricks_Product_Video_Gallery' ) ) {
        \Bricks\Elements::register_element( new \Bricks_Product_Video_Gallery() );
    }
} );
