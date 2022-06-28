<?php

    namespace com\studio\av\horse;

    defined( 'ABSPATH' ) || exit;

    class Map {
        
        public function __construct() {
            // ключ api получаем здесь https://developer.tech.yandex.ru
            $this->api_key = '057a2555-fb24-41d0-839c-e9298cef006c';
            $this->version = '1.0';
            $this->root = plugin_dir_url( __FILE__ );
            //Если не соответсвует, идём лесом
            if(is_singular('stables') || is_tax('stable-city') || is_archive('stables') || is_page(8446))
                return;

            //Добавляем шорткод карты
            add_shortcode( 'av_stables_map', array($this, 'map') );
            add_shortcode( 'stable_on_the_map', array($this, 'stable_on_the_map') ); //на single page
            add_shortcode( 'stable_on_the_tax', array($this, 'stable_on_the_tax') ); //на страницу таксономии
            add_shortcode( 'archive_stables', array($this, 'archive_stables') ); //на странице архива

            //подключаем js
            add_action( 'wp_enqueue_scripts', function() {
                wp_register_script( 'ya-map-top', 'https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey='.$this->api_key, array( 'jquery' ), $this->version, false );
                wp_register_script( 'ya-map', 'https://api-maps.yandex.ru/2.1/?lang=ru_RU', array( 'jquery' ), $this->version, false );
                wp_register_script( 'av-stables', site_url() . '/wp-content/plugins/av-horse/assets/js/js.js', array( 'jquery' ), $this->version, true );

                if (get_the_id() == '8446') {
                  wp_enqueue_script( 'ya-map-top' );
                  wp_enqueue_script( 'av-stables' );
                } else {
                  wp_enqueue_script( 'ya-map' );
                }

                

            }, 90, 9);

        }

        //Добавляем карту на страницу заявки на добавление конюшни
        public function map($atts) {
            // белый список параметров и значения по умолчанию
            $atts = shortcode_atts( array(
                'width' => '100%',
                'height' => '400px'
            ), $atts );

            $ret = '<div id="map" style="width:'.$atts['width'].'; height:'.$atts['height'].'"></div>';

            return $ret;
        }

        //Добавляем карту на страницу конюшни
        public function stable_on_the_map($atts) {
            // белый список параметров и значения по умолчанию
            $atts = shortcode_atts( array(
                'width' => '100%',
                'height' => '400px',
                'title' => 'Сивка-Бурка',
                'descr' => 'Описание',
                'phone' => '',
                'geo'       => '50.442829364992825,30.534973321081374'
            ), $atts );

            $ret = '<div id="map" style="width:'.$atts['width'].'; height:'.$atts['height'].'"></div>';
            $ret .= '<script> if (typeof ymaps !=="undefined" && jQuery("#map").length) {
                ymaps.ready(init); 
            }';

            $ret .= "
            function init() {
            
              var i,
                  place,
                  preset,
                  descr = [
                  { balloonContentHeader: '".$atts['title']."',
                    balloonContentBody: '".$atts['descr']."', 
                    balloonContentFooter: '<a href=tel:+".preg_replace("/[^,.0-9]/", '', $atts['phone']).">".$atts['phone']."</a>', 
                    hintContent: '".$atts['title']."',
                    iconContent: '1' }
                  ],
                  pointer = [
                    [".$atts['geo']."]
                  ],
                  preset = [
                    {preset: 'islands#grayCircleIcon'}
                  ],
                  myMap = new ymaps.Map(\"map\", {
                    center:[".$atts['geo']."],
                    zoom: 15.5,
                    controls: [],
                    scrollZoom: 'disable',
                    suppressMapOpenBlock: true
                  });
              
              for(i = 0; i < pointer.length; ++i) {
              
                place = new ymaps.Placemark(pointer[i], descr[i], preset[i]);
                myMap.geoObjects.add(place);
                
              }
              
            }
            </script>";

            return $ret;
        }

        //Добавляем карту на страницу категории
        public function stable_on_the_tax($atts) {
            //Под фильтром - карта всех конюшен этого региона. Так и поступим
            // белый список параметров и значения по умолчанию

            $atts = shortcode_atts( array(
                'width' => '100%',
                'height' => '400px',
                'id'    => ''
            ), $atts );
            //Нет смысла продолжать, если нет id таксономии
            if(!$atts['id'])
                return;

                //Выгребаем посты
                $lastnews = array(
                    'post_type' =>'stables',
                    'numberposts' => -1,
                    'post_status' => 'publish',
                    'order' => 'ASC',
                    'orderby' => 'menu_order',
                    'hide_empty' => 0,
                    'hierarchical' => 1,
                    'exclude' => '',
                    'include' => '',
                    'number' => '',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'stable-city',
                            'field' => 'term_id',
                            'terms' => $atts['id'],
                            'operator' => 'IN'
                        )
                )
                );

                $posts = wp_get_recent_posts($lastnews);
/*                echo '<pre>';
                foreach( $posts as $pst ) :
                    print_r($pst);
                    print_r( get_field('contact_HP', $pst['ID']) );
                endforeach;
                echo '</pre>';*/

            $ret = '<div id="map" style="width:'.$atts['width'].'; height:'.$atts['height'].'"></div>';
            $ret .= '<script> if (typeof ymaps !=="undefined" && jQuery("#map").length) {
                ymaps.ready(init); 
            }';

            $ret .= "
            function init() {
            
              var i,
                  place,
                  preset,
                  descr = [
                  ";

          for($i = 0; $i < count($posts); $i++) :
            //Получаем данные с полей формы
            $contact = get_field('contact_HP', $posts[$i]['ID']);
            
            $ret .= "{ balloonContentHeader: '".$posts[$i]['post_title']."',
                                    balloonContentBody: '".$contact['addres_HP']."', 
                                    balloonContentFooter: '<a href=".get_the_permalink($posts[$i]['ID']).">Подробнее</a>', 
                                    hintContent: '".$posts[$i]['post_title']."',
                                    iconContent: '".($i+1)."' }";
                        $ret .= $i < count($posts) -1 ? ',' : '';
          endfor;
          
          $ret .= "],
                  pointer = [";
                  
                  for($i = 0; $i < count($posts); $i++) :
                    //Получаем данные с полей формы
                    $contact = get_field('contact_HP', $posts[$i]['ID']);
                    $ret .= "[".$contact['gps_HP']."]";
                    $ret .= $i < count($posts) -1 ? ',' : '';
                        endfor;

          $ret .= "],
                  preset = [";

                  for($i = 0; $i < count($posts); $i++) :
                    $ret .= "{preset: 'islands#grayCircleIcon'}";
                    $ret .= $i < count($posts) -1 ? ',' : '';
                  endfor;
          $ret .= "],
                            myMap = new ymaps.Map(\"map\", {
                    center:[".$contact['gps_HP']."],
                    zoom: 8.5,
                    controls: [],
                    scrollZoom: 'enable',
                    suppressMapOpenBlock: true
                  });
              
              for(i = 0; i < pointer.length; ++i) {
              
                place = new ymaps.Placemark(pointer[i], descr[i], preset[i]);
                myMap.geoObjects.add(place);
                
              }
              
            }
            </script>";

            return $ret;
        }

        //Добавляем карту на страницу архива
        public function archive_stables($atts) {
            //Под фильтром - карта всех конюшен этого региона. Так и поступим
            // белый список параметров и значения по умолчанию

            $atts = shortcode_atts( array(
                'width' => '100%',
                'height' => '400px'
            ), $atts );


                //Выгребаем посты
                $lastnews = array(
                    'post_type' =>'stables',
                    'numberposts' => -1,
                    'post_status' => 'publish',
                    'order' => 'ASC',
                    'orderby' => 'menu_order',
                    'hide_empty' => 0,
                    'hierarchical' => 1,
                    'exclude' => '',
                    'include' => '',
                    'number' => '',
                );

                $posts = wp_get_recent_posts($lastnews);

            $ret = '<div id="map" style="width:'.$atts['width'].'; height:'.$atts['height'].'"></div>';
            $ret .= '<script> if (typeof ymaps !=="undefined" && jQuery("#map").length) {
                ymaps.ready(init); 
            }';

            $ret .= "
            function init() {
            
              var i,
                  place,
                  preset,
                  descr = [
                  ";

                  for($i = 0; $i < count($posts); $i++) :
                    //Получаем данные с полей формы
                    $contact = get_field('contact_HP', $posts[$i]['ID']);
                    
                    $ret .= "{ balloonContentHeader: '".$posts[$i]['post_title']."',
                                            balloonContentBody: '".$contact['addres_HP']." <br /> <a href=".get_the_permalink().">Подробнее</a>', 
                                            balloonContentFooter: '".$contact['phone_HP']."', 
                                            hintContent: '".$posts[$i]['post_title']."',
                                            iconContent: '".($i+1)."' }";
                                $ret .= $i < count($posts) -1 ? ',' : '';
                  endfor;
                  
          $ret .= "],
                  pointer = [";
                  
                  for($i = 0; $i < count($posts); $i++) :
                    //Получаем данные с полей формы
                    $contact = get_field('contact_HP', $posts[$i]['ID']);
                    $ret .= "[".$contact['gps_HP']."]";
                    $ret .= $i < count($posts) -1 ? ',' : '';
                        endfor;

          $ret .= "],
                  preset = [";

                  for($i = 0; $i < count($posts); $i++) :
                    $ret .= "{preset: 'islands#grayCircleIcon'}";
                    $ret .= $i < count($posts) -1 ? ',' : '';
                  endfor;
          $ret .= "],
                            myMap = new ymaps.Map(\"map\", {
                    center:[".$contact['gps_HP']."],
                    zoom: 8.5,
                    controls: [],
                    scrollZoom: 'enable',
                    suppressMapOpenBlock: true
                  });
              
              for(i = 0; i < pointer.length; ++i) {
              
                place = new ymaps.Placemark(pointer[i], descr[i], preset[i]);
                myMap.geoObjects.add(place);
                
              }
              
            }
            </script>";

            return $ret;
        }

    }