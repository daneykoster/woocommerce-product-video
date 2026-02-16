<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Bricks_Product_Video_Gallery extends \Bricks\Element {
    public $category = 'woocommerce';
    public $name     = 'product_video_gallery';
    public $icon     = 'ti-video-clapper';
    public $css_selector = '.wcpv-product-gallery';

    public function get_label() {
        return esc_html__( 'Product Video Gallery', 'wc-product-video' );
    }

    public function set_control_groups() {
        $this->register_control_group( 'section_gallery', [
            'title' => esc_html__( 'Gallery', 'wc-product-video' ),
        ] );
    }

    public function set_controls() {
        $this->controls['columns'] = [
            'tab'   => 'content',
            'group' => 'section_gallery',
            'label' => esc_html__( 'Columns', 'wc-product-video' ),
            'type'  => 'number',
            'min'   => 1,
            'max'   => 6,
            'default' => 4,
            'css'   => [
                [
                    'property' => '--columns',
                ],
            ],
        ];

        $this->controls['gap'] = [
            'tab'   => 'content',
            'group' => 'section_gallery',
            'label' => esc_html__( 'Gap', 'wc-product-video' ),
            'type'  => 'number',
            'units' => true,
            'default' => '10px',
            'css'   => [
                [
                    'property' => '--gap',
                ],
            ],
        ];
    }

    public function render() {
        global $product;
        
        // Ensure we have a product
        if ( ! $product ) {
            $product = \Bricks\Helpers::get_global_product();
        }

        if ( ! $product ) {
            return;
        }

        $main_image_id = $product->get_image_id();
        $gallery_image_ids = $product->get_gallery_image_ids();
        $video_urls = get_post_meta( $product->get_id(), '_product_video_url', true );

        // Helper to get video embed
        if ( ! function_exists( 'wcpv_get_video_embed' ) ) {
            // Should be available, but safe check
            return;
        }

        echo '<div class="wcpv-product-gallery woocommerce-product-gallery woocommerce-product-gallery--with-images">';
        
        // --- Main Slider ---
        echo '<div class="wcpv-main-slider flexslider">';
        echo '<ul class="slides">';

        // 1. Main Image
        if ( $main_image_id ) {
            $image_url = wp_get_attachment_url( $main_image_id );
            echo '<li class="woocommerce-product-gallery__image" data-thumb="' . esc_url( $image_url ) . '">';
            echo wp_get_attachment_image( $main_image_id, 'woocommerce_single' );
            echo '</li>';
        }

        // 2. Gallery Images
        if ( $gallery_image_ids ) {
            foreach ( $gallery_image_ids as $attachment_id ) {
                $image_url = wp_get_attachment_url( $attachment_id );
                echo '<li class="woocommerce-product-gallery__image" data-thumb="' . esc_url( $image_url ) . '">';
                echo wp_get_attachment_image( $attachment_id, 'woocommerce_single' );
                echo '</li>';
            }
        }

        // 3. Videos
        if ( $video_urls ) {
             if ( ! is_array( $video_urls ) ) {
                $video_urls = array( $video_urls );
            }
            foreach ( $video_urls as $video_url ) {
                if ( empty( $video_url ) ) continue;
                $embed_code = wcpv_get_video_embed( $video_url );
                if ( $embed_code ) {
                    echo '<li class="woocommerce-product-gallery__image wcpv-video-slide" data-thumb="https://img.icons8.com/ios/50/000000/video.png">';
                    echo '<div class="wcpv-video-container">' . $embed_code . '</div>';
                    echo '</li>';
                }
            }
        }

        echo '</ul>'; // .slides
        echo '</div>'; // .wcpv-main-slider

        echo '</div>'; // .wcpv-product-gallery

        // Enqueue generic frontend assets
        wp_enqueue_style( 'wcpv-style' );
        wp_enqueue_script( 'wcpv-script' );
        
        // We might need custom JS for this specific element if we don't rely on generic WC FlexSlider
        // But let's try to leverage standard WC gallery behavior first or write specific JS for this class.
    }
}
