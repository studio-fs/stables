;(function($, window, undefined) {

    "use strict";

    jQuery(document).on('click', '.image_inner .to_trash', function() {
        let str = jQuery(this).attr('data-src'),
            data = jQuery('textarea[name="acf[field_624c2a9104026]"]').val();

                data = data.replace('; '+str, '');
                data = data.replace(str, '');
            jQuery('textarea[name="acf[field_624c2a9104026]"]').val(data);
            jQuery(this).parent().remove();
    });

})(jQuery, window);