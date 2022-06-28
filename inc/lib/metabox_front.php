<?php

  defined( 'ABSPATH' ) || exit;

      $array = array(
        'id' => 'av_stables_gallery',
        'name' => 'Изображения конюшни',
        'post_type' => array( 'stables' ),
        'context'    => 'normal',
        'priority'   => 'high',
        'fields' => array(
          array(
            'name' => esc_html__('Удалите','ballov'),
            'desc' => esc_html__('Если Вам не нравится изображение, Вы можете его удалить','ballov'),
            'id'   => 'gallery_HP',
            'std'  => '',
            'type' => 'text'
          )
        )
      );
      //Вызов
      if(is_admin())
        new com\studio\av\horse\MetaBox( $array );
