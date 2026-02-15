<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add the custom tab to the product data metabox.
 */
function wcpv_add_product_video_tab( $tabs ) {
	$tabs['video'] = array(
		'label'    => __( 'Product Video', 'wc-product-video' ),
		'target'   => 'product_video_options',
		'class'    => array( 'show_if_simple', 'show_if_variable' ),
		'priority' => 25,
	);
	return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'wcpv_add_product_video_tab' );

/**
 * Add the custom fields to the Product Video tab.
 */
function wcpv_add_product_video_fields() {
	global $woocommerce, $post;

	echo '<div id="product_video_options" class="panel woocommerce_options_panel hidden">';

	// Get existing video URLs
	$video_urls = get_post_meta( $post->ID, '_product_video_url', true );
	if ( empty( $video_urls ) ) {
		$video_urls = array( '' ); // Start with one empty row
	} elseif ( ! is_array( $video_urls ) ) {
		$video_urls = array( $video_urls ); // Backward compatibility
	}

	echo '<div class="options_group wcpv-options-group">';
	echo '<p class="form-field wcpv-header"><strong>' . __( 'Product Videos', 'wc-product-video' ) . '</strong></p>';
	
	echo '<div id="wcpv_video_list">';
	
	foreach ( $video_urls as $index => $url ) {
		echo '<div class="wcpv-video-row">';
		echo '<span class="wcpv-move-handle dashicons dashicons-move"></span>';
		echo '<input type="text" class="short wcpv-video-url" name="_product_video_url[]" value="' . esc_attr( $url ) . '" placeholder="https://...">';
		echo '<button type="button" class="button wcpv-upload-video-button">' . __( 'Media Library', 'wc-product-video' ) . '</button>';
		echo '<button type="button" class="button wcpv-remove-video-button text-error" style="background: none; border: none; color: #a00; cursor: pointer;">' . __( 'Remove', 'wc-product-video' ) . '</button>';
		echo '</div>';
	}
	
	echo '</div>'; // End list

	echo '<div class="wcpv-actions">';
	echo '<button type="button" class="button button-primary wcpv-add-video-button">' . __( 'Add Video', 'wc-product-video' ) . '</button>';
	echo '<p class="description">' . __( 'Enter YouTube, Vimeo, or MP4 URLs. Drag and drop to reorder.', 'wc-product-video' ) . '</p>';
	echo '</div>';
	
	echo '</div>'; // Close wcpv-options-group
	echo '</div>'; // Close product_video_options
}
add_action( 'woocommerce_product_data_panels', 'wcpv_add_product_video_fields' );

/**
 * Save the custom fields.
 */
function wcpv_save_product_video_fields( $post_id ) {
	if ( isset( $_POST['_product_video_url'] ) && is_array( $_POST['_product_video_url'] ) ) {
		$video_urls = array_map( 'esc_url_raw', $_POST['_product_video_url'] );
		// Filter out empty values
		$video_urls = array_filter( $video_urls );
		// Reset keys
		$video_urls = array_values( $video_urls );
		update_post_meta( $post_id, '_product_video_url', $video_urls );
	} else {
		delete_post_meta( $post_id, '_product_video_url' );
	}
}
add_action( 'woocommerce_process_product_meta', 'wcpv_save_product_video_fields' );
