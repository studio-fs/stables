;(function($, window, undefined) {

  "use strict";

  jQuery(document).on('click', '.av-filter_button', function() {
    //Убираем active у других кнопок родителя
    jQuery(this).parent().find('.av-filter_button').removeClass('active');
    //Добавляем active
    jQuery(this).toggleClass('active');
  });

  //Событие сброса фильтра
  jQuery(document).on('click', '.av-filter_button_s.reset', function() {
    //Снимаем check со всех кнопок
    jQuery('.fs-filter_body .av-filter_button').each(function() {
        jQuery(this).removeClass('active');
    });
    //Инициализируем отправку данных
    send();
  });

  //Событие отправки
  jQuery(document).on('click', '.av-filter_button_s.search', function() {
    let term = jQuery('.fs-filter_body').attr('data-term');
    send( select(), term, selectInput() );
  });

  //Событие маленького фильтра
  jQuery(document).on('click', '.fs-filter_body.small .av-filter_button', function() {
    setTimeout(function() {
      let term = jQuery('.fs-filter_body').attr('data-term');
      send( select(), term, selectInput() );
    },500);
  });

  //Событие маленького фильтра input
  jQuery(document).on('change', '.fs-filter_body.small .av-filter_input', function() {
    setTimeout(function() {
      let term = jQuery('.fs-filter_body').attr('data-term');
      send( select(), term, selectInput() );
    },2500);
  });

  //Кнопка переключения в полноразмерный фильтр
  jQuery(document).on('click', '.av-filter_button_s.show_more', function() {
    jQuery('div.fs-filter_body').toggleClass('small');
  });

  function select() {
    let arr = [], i = 0, input = [];
    jQuery('.fs-filter_body .av-filter_button.active').each(function() {
        arr[i++] = {
                    'name': jQuery(this).parent().attr('data-name'),
                    'content': jQuery(this).attr('data-content')
                };
    });

    return arr;
  }

  //input
  function selectInput() {
    let arr = [], i = 0;
    jQuery('.fs-filter_body .av-filter_input[name="price_from"]').each(function() {
        if(jQuery(this).val() != '') {
            arr[i++] = {
                        'name': jQuery(this).parent().attr('data-name'),
                        'price_from': jQuery(this).val(),
                        'price_to': jQuery(this).next().val()
                    };
        }
    });
    console.log(arr);
    return arr;
  }

  function send(arr = null, term = null, input = null) {
    //ajax
    if(!ajaxurl) var ajaxurl = '/wp-admin/admin-ajax.php';

    jQuery.ajax({
        url: ajaxurl,
        method: 'POST',
        data:{
            'action' :'av_filter_data',
            'values' : arr,//jQuery(selectedItems).serialize()
            'term_id': term,
            'input' : input
        },
        beforeSend: function() {
            jQuery('.fs-filter_body').block({
                message: null,
                overlayCSS: { background: '#fff',
                backgroundSize:'36px 36px', opacity: .7
                }
            });
        },
        success:function(result){
            //console.log(result);
            jQuery('.archive__layout1').fadeIn(1000).delay(1000).html(result);
            jQuery('.fs-filter_body').unblock();
        },
        error:function(error){
            console.log('error', error);
        }
    }); // end ajax
  }

})(jQuery, window);