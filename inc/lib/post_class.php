<?php

    namespace com\studio\av\horse;

    defined( 'ABSPATH' ) || exit;

    class post {
        public function __construct() {
            // Регистрируем пост тайп
            add_action('init', array($this, 'registration') );
        }

        //регистрация пост тайпa
        public function registration() {
            register_post_type('stables', array(
                'labels'             => array(
                'name'               => 'Конюшни', // Основное название типа записи
                'singular_name'      => 'Конюшня', // отдельное название записи типа Book
                'add_new'            => 'Добавить конюшню',
                'add_new_item'       => 'Добавить новую',
                'edit_item'          => 'Редактировать конюшню',
                'new_item'           => 'Новая конюшня',
                'view_item'          => 'Посмотреть конюшню',
                'search_items'       => 'Найти конюшню',
                'not_found'          => 'конюшень не найдено',
                'not_found_in_trash' => 'Конюшень не найдено',
                'parent_item_colon'  => '',
                'menu_name'          => 'Конюшни'
              ),
                'public'             => true, // не публичный пост тайп
                'publicly_queryable' => true, // не публичный пост тайп
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true, // не публичный пост тайп
                'rewrite'            => true,
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => 20,
                'menu_icon'          => 'dashicons-buddicons-activity',
                'dashicons-admin-post'=> 'dashicons-buddicons-activity',
                'supports'           => array('title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats')
              ) );

            register_taxonomy('stable-city', array('stables'), [
                'label'             => 'Город', // определяется параметром $labels->name
                'labels'            => array(
                'name'              => 'Городов',
                'singular_name'     => 'Города',
                'search_items'      => 'Искать Город',
                'all_items'         => 'Все Города',
                'parent_item'       => 'Родит. раздел',
                'parent_item_colon' => 'Родит. раздел',
                'edit_item'         => 'Ред. Город',
                'update_item'       => 'Обновить Город',
                'add_new_item'      => 'Добавить Город',
                'new_item_name'     => 'Новый Город',
                'menu_name'         => 'Города конюшень',
            ),
            'description'           => 'Здесь указываются города конюшень', // описание таксономии
            'public'                => true,
            'show_in_nav_menus'     => false, // равен аргументу public
            'show_ui'               => true, // равен аргументу public
            'show_tagcloud'         => false, // равен аргументу show_ui
            'hierarchical'          => true,
            'rewrite'               => array('slug'=>'stables', 'hierarchical'=>false, 'with_front'=>false, 'feed'=>false ), //Перезаписывает слаг со stable-city на stables
            'show_admin_column'     => true, // Позволить или нет авто-создание колонки таксономии в таблице ассоциированного типа записи. (с версии 3.5)
        ]);
        }
    }