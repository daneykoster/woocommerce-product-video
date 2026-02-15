jQuery(document).ready(function ($) {
    // Wait for the window to load to ensure Bricks slider is initialized
    $(window).on('load', function () {
        const $bricksSlider = $('.brx-product-gallery-thumbnail-slider');
        const $videoSlides = $('.woocommerce-product-gallery__image.wcpv-video-slide');

        if ($bricksSlider.length && $videoSlides.length) {
            const $wrapper = $bricksSlider.find('.brx-thumbnail-slider-wrapper'); // Or the correct internal wrapper

            // Check if we found the wrapper, sometimes it might be just the slider itself if not initialized yet,
            // but on window load it should be.
            // Bricks 1.9+ might use Splide or Swiper, earlier versions used FlexSlider.
            // The HTML provided shows a structure typical of FlexSlider or a custom implementation with .flex-viewport

            $videoSlides.each(function (index) {
                const $slide = $(this);
                const thumbUrl = $slide.data('thumb') || ''; // Use data-thumb if available

                // Create the thumbnail element
                // We need to match the structure of existing thumbnails
                // <div class="woocommerce-product-gallery__image" ... ><img ... ></div>

                if (thumbUrl) {
                    const $newThumb = $('<div class="woocommerce-product-gallery__image" style="float: left; display: block;">' +
                        '<img src="' + thumbUrl + '" class="" draggable="false">' +
                        '</div>');

                    // Append to the slider wrapper
                    $wrapper.append($newThumb);
                }
            });

            // Trigger a resize or re-init if possible. 
            // For FlexSlider:
            const flex = $bricksSlider.data('flexslider');
            if (flex) {
                // Determine how to add slides in FlexSlider properly or just let it be and hope styling picks it up
                // FlexSlider usually needs valid HTML structure before init.
                // Since it's already init, we might need to use its API or destroy and re-init.
                // However, simply appending might work if we trigger a resize.

                // Try simpler approach: Add slides and trigger window resize
                $(window).trigger('resize');
            }
        }
    });
});
