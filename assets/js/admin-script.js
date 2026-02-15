jQuery(document).ready(function ($) {
    var mediaUploader;
    var targetInput;

    // Initialize sortable
    $('#wcpv_video_list').sortable({
        handle: '.wcpv-move-handle',
        placeholder: 'ui-state-highlight',
        axis: 'y'
    });

    // Add new video row
    $(document).on('click', '.wcpv-add-video-button', function (e) {
        e.preventDefault();
        var row = '<div class="wcpv-video-row">' +
            '<span class="wcpv-move-handle dashicons dashicons-move"></span>' +
            '<input type="text" class="short wcpv-video-url" name="_product_video_url[]" value="" placeholder="https://...">' +
            '<button type="button" class="button wcpv-upload-video-button">' + 'Media Library' + '</button>' +
            '<button type="button" class="button wcpv-remove-video-button text-error" style="background: none; border: none; color: #a00; cursor: pointer;">' + 'Remove' + '</button>' +
            '</div>';
        $('#wcpv_video_list').append(row);
    });

    // Remove video row
    $(document).on('click', '.wcpv-remove-video-button', function (e) {
        e.preventDefault();
        if ($('#wcpv_video_list .wcpv-video-row').length > 1) {
            $(this).closest('.wcpv-video-row').remove();
        } else {
            $(this).closest('.wcpv-video-row').find('input').val('');
        }
    });

    $(document).on('click', '.wcpv-upload-video-button', function (e) {
        e.preventDefault();

        targetInput = $(this).siblings('.wcpv-video-url');

        // If the uploader object has already been created, reopen the dialog
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        // Extend the wp.media object
        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Video',
            button: {
                text: 'Choose Video'
            },
            multiple: false,
            library: {
                type: 'video'
            }
        });

        // When a file is selected, grab the URL and set it as the text field's value
        mediaUploader.on('select', function () {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            if (targetInput) {
                targetInput.val(attachment.url);
            }
        });

        // Open the uploader dialog
        mediaUploader.open();
    });
});
