add_action( 'init', function() {
    $element_files = [
        __DIR__ . '/elements/product-video-gallery.php',
    ];

    foreach ( $element_files as $file ) {
        if ( file_exists( $file ) ) {
            \Bricks\Elements::register_element( $file );
        }
    }
}, 11 );
