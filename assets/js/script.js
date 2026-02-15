jQuery(document).ready(function ($) {
    // Wait for the window to load to ensure Bricks slider is initialized
    $(window).on('load', function () {
        const $bricksSlider = $('.brx-product-gallery-thumbnail-slider');
        const $mainGallery = $('.woocommerce-product-gallery'); // Main gallery wrapper
        const $videoSlides = $('.woocommerce-product-gallery__image.wcpv-video-slide');

        if ($bricksSlider.length && $videoSlides.length) {
            const $wrapper = $bricksSlider.find('.brx-thumbnail-slider-wrapper');

            // Find ALL slides in the main gallery to determine correct indices
            const $allSlides = $mainGallery.find('.woocommerce-product-gallery__wrapper .woocommerce-product-gallery__image');

            $videoSlides.each(function () {
                const $slide = $(this);
                const thumbUrl = $slide.data('thumb') || '';

                // Calculate the index of this video slide among ALL slides
                const slideIndex = $allSlides.index($slide);

                if (thumbUrl) {
                    const $newThumb = $('<div class="woocommerce-product-gallery__image" style="float: left; display: block; cursor: pointer;">' +
                        '<img src="' + thumbUrl + '" class="" draggable="false">' +
                        '</div>');

                    const thumbSliderInstance = $bricksSlider.data('flexslider');

                    // Try to use FlexSlider API first (Best practice)
                    if (thumbSliderInstance && typeof thumbSliderInstance.addSlide === 'function') {
                        // addSlide accepts HTML or Object. It appends by default.
                        thumbSliderInstance.addSlide($newThumb);

                        // Re-bind click if addSlide doesn't handle asNavFor binding for new slides automatically
                        // (FlexSlider sometimes needs a nudge or manual binding for dynamic slides)
                        // We'll add a manual click handler just in case, wrapped to avoid double-binding/conflict
                        $newThumb.on('click', function (e) {
                            e.preventDefault();
                            if ($mainGallery.data('flexslider')) {
                                // Force sync
                                $mainGallery.flexslider(slideIndex);
                            }
                        });
                    } else {
                        // Fallback: Manual appending
                        $newThumb.on('click', function (e) {
                            e.preventDefault();
                            if ($mainGallery.data('flexslider')) {
                                $mainGallery.flexslider(slideIndex);
                            } else {
                                console.log('Main gallery FlexSlider instance not found, attempting fallback.');
                            }
                        });
                        $wrapper.append($newThumb);
                    }
                }
            });

            // Trigger a resize to update layout
            $(window).trigger('resize');
        }
    });
});
