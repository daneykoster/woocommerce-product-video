<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue scripts and styles.
 */
function wcpv_enqueue_scripts() {
	if ( ! is_product() ) {
		return;
	}

	wp_enqueue_style( 'wcpv-style', WC_PRODUCT_VIDEO_URL . 'assets/css/style.css', array(), '1.0.0' );
	wp_enqueue_script( 'wcpv-script', WC_PRODUCT_VIDEO_URL . 'assets/js/script.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'wcpv_enqueue_scripts' );

/**
 * Get embed code from URL.
 */
function wcpv_get_video_embed( $url ) {
	$embed_code = '';

	if ( strpos( $url, 'youtube.com' ) !== false || strpos( $url, 'youtu.be' ) !== false ) {
		// Simple regex for YouTube ID
		preg_match( '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $url, $matches );
		if ( isset( $matches[1] ) ) {
			$video_id   = $matches[1];
			$embed_code = '<iframe width="100%" height="100%" src="https://www.youtube.com/embed/' . esc_attr( $video_id ) . '?enablejsapi=1&version=3&playerapiid=ytplayer" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
		}
	} elseif ( strpos( $url, 'vimeo.com' ) !== false ) {
		$video_id   = (int) substr( parse_url( $url, PHP_URL_PATH ), 1 );
		$embed_code = '<iframe src="https://player.vimeo.com/video/' . esc_attr( $video_id ) . '" width="100%" height="100%" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
	} else {
		$filetype = wp_check_filetype( $url );
		if ( in_array( $filetype['type'], array( 'video/mp4', 'video/webm', 'video/ogg' ) ) ) {
			$embed_code = '<video width="100%" height="100%" controls><source src="' . esc_url( $url ) . '" type="' . esc_attr( $filetype['type'] ) . '">Your browser does not support the video tag.</video>';
		}
	}

	return $embed_code;
}

/**
 * Output the video slide in the product gallery.
 */
function wcpv_output_video_slide() {
	global $product;

	if ( ! $product ) {
		return;
	}

	$video_urls = get_post_meta( $product->get_id(), '_product_video_url', true );

	if ( ! $video_urls ) {
		return;
	}

	// Backward compatibility for string format
	if ( ! is_array( $video_urls ) ) {
		$video_urls = array( $video_urls );
	}

	foreach ( $video_urls as $video_url ) {
		if ( empty( $video_url ) ) {
			continue;
		}

		$embed_code = wcpv_get_video_embed( $video_url );

		if ( $embed_code ) {
			echo '<div class="woocommerce-product-gallery__image wcpv-video-slide" data-thumb="https://img.icons8.com/ios/50/000000/video.png">'; // Placeholder icon for thumbnail
			echo '<div class="wcpv-video-container">';
			echo $embed_code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';
			echo '</div>';
		}
	}
}
// Using priority 20 to ensure it comes after standard thumbnails (usually priority 10? No, standard is just the function call).
// Actually standard WC doesn't use add_action for its own thumbnails, it calls the function directly.
// BUT `woocommerce_product_thumbnails` action IS triggered.
// If we hook here, we output valid HTML for a slide.
add_action( 'woocommerce_product_thumbnails', 'wcpv_output_video_slide', 30 );
