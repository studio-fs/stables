<?php

    namespace com\studio\av\horse;

    defined( 'ABSPATH' ) || exit;

    class Filter {
        public function __construct() {
            $this->root = site_url();
            $this->version = '1.0';
            $this->post_type = 'stables';
            
            //Подключаем зависимости
            add_action( 'wp_footer', array($this, 'addiction'));

            //Событие
            add_action('wp_ajax_nopriv_av_filter_data', array($this, 'filter_data'));
            add_action('wp_ajax_av_filter_data', array($this, 'filter_data'));
        }

        public function _html() {
            require_once(__DIR__.'/filter_html.php');
        }

        //Событие
        public function filter_data() {
            //Не будем ходить вокруг да около, а сразу займёмся делом
            $meta = [];
            foreach($_POST['values'] as $value ) :
                //if ($name = $this->crutch($value['name'])) : //Скорее всего сакрального смысла в этой проверке нет
                    $meta[] = array(
                        'key' => $this->crutch($value['name']),
                        'value' => $value['content'],
                        'compare' => 'LIKE' //IN
                    );
                //endif;
            endforeach;
            // собираем значения с input

            foreach($_POST['input'] as $input ) :
                //if ($name = $this->crutch($input['name'])) : //Скорее всего сакрального смысла в этой проверке нет
                    $meta[] = array(
                            'key' => $this->crutch($input['name']),
                            'value' => array($input['price_from'], $input['price_to']),
                            'type' => 'numeric',
                            'compare' => 'BETWEEN'
                    );
                //endif;
            endforeach;

            $taxonomy = [];
        if( $_POST['term_id'] != '' ) {
            $tax_name = get_term( $_POST['term_id'] )->taxonomy;
            $taxonomy = array(
                'taxonomy' => $tax_name,
                'field'    => 'term_id',
                'terms'    => $_POST['term_id']
            );
        }

        if (!empty($taxonomy)) :
            $tax_query = new \WP_Query(array(
                'post_type' => $this->post_type,
                'posts_per_page'=> -1,
                'post_status' => 'publish',
                'order'     => 'DESC',
                'orderby'   => 'DESC', // meta_value_num
                //'meta_key'  => 'wls_tariff_grid_30',
                    'tax_query' => array(
                       $taxonomy,
                    ),
                'meta_query' => array(
                    'relation' => 'OR',
                    $meta,
                )
            ));
        else:
            $tax_query = new \WP_Query(array(
                'post_type' => $this->post_type,
                'posts_per_page'=> -1,
                'post_status' => 'publish',
                'order'     => 'DESC',
                'orderby'   => 'DESC', // meta_value_num
                //'meta_key'  => 'wls_tariff_grid_30',
                'meta_query' => array(
                    'relation' => 'OR',
                    $meta,
                )
            ));
        endif;
            if ($tax_query->have_posts()) : while ($tax_query->have_posts()) : $tax_query->the_post();
                get_template_part( 'template-parts/content', 'stables');
            endwhile; else:
                echo '<div class="aven mt40">Ничего не найдено!</div>';
            endif;

            wp_die();
        }

        //Дабы не нагружать лишними запросами, пишем такой костыль
        private function crutch($name) {
            $repeater1 = 'terms_'; // где terms - это название repeater поля _0_ - номер данного поля
            $repeater2 = 'terms_owner_';
            switch($name) {
                case 'Виды спорта':
                    $ret = $repeater1.'sports';
                break;
                case 'Абонемент':
                    $ret = $repeater1.'subscription';
                break;
                case 'Цена за занятие р/мес':
                    $ret = $repeater1.'price';
                break;
                case 'Прогулки в лес/поле':
                    $ret = $repeater1.'walks';
                break;
                case 'Плац':
                    $ret = $repeater1.'polygon';
                break;
                case 'Крытый манеж':
                    $ret = $repeater1.'arena';
                break;
                case 'Теплый туалет':
                    $ret = $repeater1.'toilet';
                break;
                case 'Раздевалка':
                    $ret = $repeater1.'cloakroom';
                break;
                case 'Аренда лошадей':
                    $ret = $repeater1.'horse_rental';
                break;
                case 'Проведение вечеринок, свадеб':
                    $ret = $repeater1.'event';
                break;
                case 'Аренда денника р/мес':
                    $ret = $repeater2.'rental_stall';
                break;
                case 'Аренда летника р/мес':
                    $ret = $repeater2.'rental_barn';
                break;
                case 'Бочка':
                    $ret = $repeater2.'barrel';
                break;
                case 'Водилка':
                    $ret = $repeater2.'horse_wallkers';
                break;
                case 'Амуничник':
                    $ret = $repeater2.'ammunition_horses';
                break;
                case 'Зимняя мойка':
                    $ret = $repeater2.'winter_wash';
                break;
                case 'Солярий':
                    $ret = $repeater2.'solarium';
                break;
                case 'Берейтор':
                    $ret = $repeater2.'bereytor';
                break;
                case 'Выпас':
                    $ret = $repeater2.'grazing';
                break;
                default :
                    $ret = '';
                break;
            }
            return $ret;
        }


        //Подключение зависимостей
        public function addiction() {
            //wp_register_style( 'ion-rangeSlider', $this->root . 'libs/ion-rangeSlider/css/ion.rangeSlider.min.css', array(), $this->version, 'all' );
            wp_register_style( 'av-filter', $this->root . '/wp-content/plugins/av-horse/assets/css/filter.css', array(), $this->version, 'all' );
            
            //wp_register_script( 'ion-rangeSlider', $this->root . 'libs/ion-rangeSlider/js/ion.rangeSlider.min.js', array( 'jquery' ), $this->version, true );
            wp_register_script( 'blockUI', $this->root . '/wp-content/plugins/av-horse/assets/js/jquery.blockUI.min.js', array( 'jquery' ), $this->version, true );
            wp_register_script( 'av-filter', $this->root . '/wp-content/plugins/av-horse/assets/js/filter.js', array( 'jquery' ), $this->version, true );

            //wp_enqueue_style( 'ion-rangeSlider' );
            wp_enqueue_style( 'av-filter' );
            //wp_enqueue_script( 'ion-rangeSlider' );
            wp_enqueue_script( 'blockUI' );
            wp_enqueue_script( 'av-filter' );
        }

      //Транслитерация
      private function rus2translit($text) {
      // Русский алфавит
        $rus_alphabet = array(
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й',
            'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф',
            'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я',
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й',
            'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф',
            'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', ' '
        );

      // Английская транслитерация
        $rus_alphabet_translit = array(
            'A', 'B', 'V', 'G', 'D', 'E', 'IO', 'ZH', 'Z', 'I', 'I',
            'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F',
            'H', 'C', 'CH', 'SH', 'SH', '`', 'Y', '`', 'E', 'IU', 'IA',
            'a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'i',
            'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f',
            'h', 'c', 'ch', 'sh', 'sh', '`', 'y', '`', 'e', 'iu', 'ia', '_'
        );

        return str_replace($rus_alphabet, $rus_alphabet_translit, $text);
      }
    }