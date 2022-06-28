;(function($, window, undefined) {

  "use strict";


    let inputs = document.querySelectorAll('.form-input');
    Array.prototype.forEach.call(inputs, function(input) {
      let label  = input.nextElementSibling,
          labelVal = label.innerHTML;

      input.addEventListener('change', function(e){
        let fileName = '', file_data = '';
        if( this.files && this.files.length > 1 )
          fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
        else
          fileName = e.target.value.split( '\\' ).pop();

        //отправляем на костыль
        //file_data = jQuery('textarea[name="avstables[\'avstables\'][file]"]').val();
        //jQuery('textarea[name="avstables[\'avstables\'][file]"]').val(file_data+'; '+fileName);

        if( fileName )
          label.querySelector( 'span' ).innerHTML = fileName + ' - прикреплён';
        else
          label.innerHTML = labelVal;
        });
    });

    jQuery(document).on('click', '.image_inner .to_trash', function() {
        let str = jQuery(this).attr('data-src'),
            id = jQuery(this).attr('data-id'),
            data = jQuery('textarea[name="avstables[file]"][data-id="'+id+'"]').val();

                data = data.replace('; '+str, '');
                data = data.replace(str, '');
            jQuery('textarea[name="avstables[file]"][data-id="'+id+'"]').val(data);
            jQuery(this).parent().remove();
    });

    //Работа с чекбоксами
    jQuery('input[type=checkbox]:not(.sports)').change(function() {
      if (jQuery(this).is(':checked')) {
        jQuery(this).val('Да');
      } else {
        jQuery(this).val('Нет');
      }
    });

    //Виды спорта
    jQuery('input[type=checkbox].sports').change(function() {
      if (jQuery(this).is(':checked')) {
        let val = jQuery('input[name="avstables[terms][sports]"]').val(),
            ths = jQuery(this).val();
        jQuery('input[name="avstables[terms][sports]"]').val(val+'; '+ths);
      } else {
        let val = jQuery('input[name="avstables[terms][sports]"]').val(),
            ths = jQuery(this).val();

            val = val.replace('; '+ths, '');
            val = val.replace(ths, '');

        jQuery('input[name="avstables[terms][sports]"]').val(val);
      }
    });

})(jQuery, window);