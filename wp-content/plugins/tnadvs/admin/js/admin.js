(function( $ ) {
    'use strict';
    /**
     * Display the media uploader.
     *
     * @since 1.0.0
     */
    function adv_render_media_uploader( $elem, form ) {
        var file_frame, attachment;

        // If an instance of file_frame already exists, then we can open it rather than creating a new instance
        if ( file_frame ) {
            file_frame.open();
            return;
        };

        // Use the wp.media library to define the settings of the media uploader
        file_frame = wp.media.frames.file_frame = wp.media({
            frame: 'post',
            state: 'insert',
            multiple: false
        });

        // Setup an event handler for what to do when a media has been selected
        file_frame.on( 'insert', function() {
            // Read the JSON data returned from the media uploader
            attachment = file_frame.state().get('selection').first().toJSON();

            // First, make sure that we have the URL of the media to display
            if (0 > $.trim(attachment.url.length)) {
                return;
            }
            $('#hdimg').val(attachment.url);
            $('.attachment-post-thumbnail').attr("src",attachment.url);
        });

        // Now display the actual file_frame
        file_frame.open();
    };
    $(function() {

        // Common: Upload Media
        $('#setimgadv').on('click', function (e) {
            e.preventDefault();
            adv_render_media_uploader($(this), 'default');
        });
       
    });

})( jQuery );


