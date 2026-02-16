jQuery(document).ready(function ($) {
    /**
     * Helper to inject video thumbnails into Bricks Builder slider.
     */
    function injectBricksVideoThumbnails() {
        const $bricksSlider = $('.brx-product-gallery-thumbnail-slider');
        const $mainGalleryWrapper = $('.woocommerce-product-gallery__wrapper');
        const $videoSlides = $('.woocommerce-product-gallery__image.wcpv-video-slide');

        if (!$bricksSlider.length || !$videoSlides.length) {
            return;
        }

        const $thumbnailsWrapper = $bricksSlider.find('.brx-thumbnail-slider-wrapper');
        if (!$thumbnailsWrapper.length) {
            return;
        }

        // We need existing thumbnails to clone styles from
        const $existingThumbs = $thumbnailsWrapper.find('.woocommerce-product-gallery__image');
        const $templateThumb = $existingThumbs.length ? $existingThumbs.first() : null;

        // Get all slides in main gallery to find index
        // Note: Bricks main gallery might be initialized differently, but structure usually matches WC
        const $allMainSlides = $mainGalleryWrapper.children('.woocommerce-product-gallery__image');

        $videoSlides.each(function () {
            const $videoSlide = $(this);

            // Check if this video slide already has a corresponding thumbnail
            // We can check by data attribute or just index if we are careful
            // For now, let's use a flag on the video slide to mark it as processed?
            // Or just check if we already appended it.
            if ($videoSlide.data('wcpv-thumb-injected')) {
                return;
            }

            const videoIndex = $allMainSlides.index($videoSlide);
            if (videoIndex === -1) return;

            const thumbUrl = $videoSlide.data('thumb') || '';

            // Create the new thumbnail
            let $newThumb;
            if ($templateThumb) {
                // Clone an existing thumbnail to keep classes and structure
                $newThumb = $templateThumb.clone();

                // Update the thumbnail's data-thumb attribute
                $newThumb.attr('data-thumb', thumbUrl);

                // Clean up the image element completely
                const $img = $newThumb.find('img');
                $img.attr('src', thumbUrl);
                $img.attr('srcset', '');
                $img.attr('data-src', thumbUrl);
                $img.attr('data-srcset', '');
                $img.attr('data-large_image', '');
                $img.attr('data-sizes', '');
                $img.removeAttr('width');
                $img.removeAttr('height');
                $img.removeClass('bricks-lazy-hidden');

                // Remove active class if present
                $newThumb.removeClass('flex-active-slide active-slide is-active');
            } else {
                // Fallback creation
                $newThumb = $('<div class="woocommerce-product-gallery__image" data-thumb="' + thumbUrl + '"><img src="' + thumbUrl + '"></div>');
            }

            // Add click event to switch main gallery
            // Note: Bricks might use Swiper or FlexSlider or Splide
            // Try to trigger generic click or find the controller
            $newThumb.on('click', function (e) {
                e.preventDefault();
                // Try FlexSlider on main gallery
                const flex = $('.woocommerce-product-gallery').data('flexslider');
                if (flex) {
                    flex.flexslider(videoIndex);
                } else {
                    // Try to finding a swiper instance? Bricks often uses Swiper for sliders.
                    // If it is Splide/Swiper, we might need to access the instance differently.
                    // For now, assume WC standard FlexSlider or trigger a click on a hidden link?
                    // Let's stick to standard WC logic first.
                    // Also trigger a click on the main gallery dot if it exists?
                    $('.flex-control-nav li').eq(videoIndex).find('img').trigger('click');
                }
            });

            // Append to slider wrapper
            $thumbnailsWrapper.append($newThumb);
            $videoSlide.data('wcpv-thumb-injected', true);
        });
    }

    // Attempt injection on load
    $(window).on('load', injectBricksVideoThumbnails);

    // Also use MutationObserver because Bricks might lazy load or re-init
    const observer = new MutationObserver(function (mutations) {
        // Debounce or just check specifically
        injectBricksVideoThumbnails();
    });

    const targetNode = document.querySelector('.brxe-product-gallery') || document.body;
    if (targetNode) {
        observer.observe(targetNode, { childList: true, subtree: true });
    }

    // Fallback: Check every 1s for a few seconds
    let attempts = 0;
    const interval = setInterval(function () {
        injectBricksVideoThumbnails();
        attempts++;
        if (attempts > 5) clearInterval(interval);
    }, 1000);
});

