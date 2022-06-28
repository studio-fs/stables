<?php

    namespace com\studio\av\horse;

    defined( 'ABSPATH' ) || exit;

    require_once(__DIR__ .'/metabox_front.php');

    class MetaBox {
        public function __construct( $arr = array() ) {

            $this->version = '1.0';
            $this->site_url = site_url().'/wp-content/plugins/av-horse/';

            add_action('add_meta_boxes', array($this, 'add_box') ); //добавляем метабокс на страницу
            //в данном случае, нам это не надо
            //add_action( 'save_post', array($this, 'save') ); // Сохраняем данные, когда пост сохраняется

            $this->arr = $arr;

            add_filter( 'av_show_on', array( $this, 'add_for_id' ), 99, 9 );
            add_filter( 'av_show_on', array( $this, 'add_for_page_template' ), 99, 9 );
            //Подключаем js
            add_action( 'admin_enqueue_scripts', function($hook) {
                wp_enqueue_style('admin-metabox', $this->site_url . 'assets/css/metabox.css', array(), $this->version);
                wp_register_script( 'admin-metabox', $this->site_url.'assets/js/metabox.js', array( 'jquery' ), $this->version, false );
                if ($hook == 'post.php') {
                    wp_enqueue_style( 'admin-metabox' );
                    wp_enqueue_script( 'admin-metabox' );
                }
            }, 10 );
        }

        public function add_box() {
            $this->arr['context'] = empty($this->arr['context']) ? 'normal' : $this->arr['context'];
            $this->arr['priority'] = empty($this->arr['priority']) ? 'high' : $this->arr['priority'];
            $this->arr['show_on'] = empty( $this->arr['show_on'] ) ? array('key' => false, 'value' => false) : $this->arr['show_on'];
            
            if( apply_filters( 'av_show_on', true, $this->arr ) )
                add_meta_box( $this->arr['id'], $this->arr['name'], array($this, 'box_callback'), $this->arr['post_type'] );
        }

        public function box_callback($post, $meta) {
            $screens = $meta['args'];

            // Используем nonce для верификации
            wp_nonce_field( plugin_basename(__FILE__), 'CustomMeta_noncename' );

            // значение поля
            $ret = '';
            foreach($this->arr['fields'] as $fields) :
                // Поля формы для введения данных
                $ret .= '
                <table class="form-table av_metabox" style="border-bottom: 1px solid #E9E9E9">
                <tbody>
                '.$this->meta_view($fields, $post).'
                </tbody>
                </table>';
            endforeach;
            echo $ret;
        }

        public function save($post_id) {

            // проверяем nonce нашей страницы, потому что save_post может быть вызван с другого места.
            if ( ! wp_verify_nonce( $_POST['CustomMeta_noncename'], plugin_basename(__FILE__) ) )
                return;

            // если это автосохранение ничего не делаем
            if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
                return;

            // проверяем права юзера
            if( ! current_user_can( 'edit_post', $post_id ) )
                return;

            // Все ОК. Теперь, нужно найти и сохранить данные
            // Очищаем значение поля input.
            // Это нам не надо, т.к. поле обрабатывается acf
    /*        foreach($this->arr['fields'] as $fields) :
                //$my_data = sanitize_text_field( $_POST[$fields['id']] ); //очистка конечно хорошо, но делать её мы не будем

                // Обновляем данные в базе данных.
                update_post_meta( $post_id, $fields['id'], $_POST[$fields['id']] );
            endforeach;*/
        }

        private function meta_view($fields, $post) {
            $value = get_post_meta( $post->ID, $fields['id'], true ) ? get_post_meta( $post->ID, $fields['id'], true ) : $fields['std'];

            switch($fields['type']) {
                case 'text':
                $ret = '<tr>
                <th style="width:18%">
                <label for="'.$fields['id'].'">'.$fields['name'].'</label>
                </th>
                <td>';
                //'<input type="text" name="'.$fields['id'].'" id="'.$fields['id'].'" value="'. $value .'" style="width: 100%; padding: 5px;">';
                
                $images = explode('; ', $value);
                foreach($images as $image)
                    if($image != "" || !empty($image) || $image != null) :

                    $ret .= '<div class="image_inner">
                        <img src="'.$image.'" alt="" />
                        <span class="to_trash" data-src="'.$image.'"><img src="'.$this->site_url.'assets/trash.svg" alt=""></span>
                        </div>';

                    endif;

                $ret .= '<p class="av_metabox_description">'.$fields['desc'].'</p>';
                $ret .= '</td>
                </tr>';
                break;
                case 'text_small':
                $ret='<tr>
                <th style="width:18%">
                <label for="'.$fields['id'].'">'.$fields['name'].'</label>
                </th>
                <td>
                <input type="text" name="'.$fields['id'].'" id="'.$fields['id'].'" value="'. $value .'" style="width: 50%; min-width:200px; padding: 5px;">
                <p class="av_metabox_description">'.$fields['desc'].'</p>
                </td>
                </tr>';
                break;
                case 'textarea':
                $ret='<tr>
                <th style="width:18%">
                <label for="'.$fields['id'].'">'.$fields['name'].'</label>
                </th>
                <td>
                <textarea cols="40" rows="5" type="text" name="'.$fields['id'].'" id="'.$fields['id'].'" style="width: 100%">'.$value.'</textarea>
                <p class="av_metabox_description">'.$fields['desc'].'</p>
                </td>
                </tr>';
                break;
                case 'checkbox':
                $isChecked = $value ? ' checked="checked"' : '';
                $ret='<tr>
                <th style="width:18%">
                <label for="'.$fields['id'].'">'.$fields['name'].'</label>
                </th>
                <td>
                <input type="checkbox" name="'.$fields['id'].'" id="'.$fields['id'].'" '. $isChecked .' />
                <p class="av_metabox_description">'.$fields['desc'].'</p>
                </td>
                </tr>';
                break;
                case 'screenshot':
                $ret='<tr>
                <th style="width:18%">
                <label for="'.$fields['id'].'">'.$fields['name'].'</label>
                </th>
                <td>
                <img src="'.esc_url($value).'" alt="" title="'.$fields['name'].'" style="max-width: 320px">
                <p class="av_metabox_description">'.$fields['desc'].'</p>
                </td>
                </tr>';
                break;
            }
            return $ret;
        }

        public function add_for_id( $display, $meta_box ) {
            if ( 'id' !== $meta_box['show_on']['key'] )
                return $display;

            // If we're showing it based on ID, get the current ID                  
            if( isset( $_GET['post'] ) ) $post_id = $_GET['post'];
            elseif( isset( $_POST['post_ID'] ) ) $post_id = $_POST['post_ID'];
            if( !isset( $post_id ) )
                return false;
            
            // If value isn't an array, turn it into one    
            $meta_box['show_on']['value'] = !is_array( $meta_box['show_on']['value'] ) ? array( $meta_box['show_on']['value'] ) : $meta_box['show_on']['value'];
            
            // If current page id is in the included array, display the metabox

            if ( in_array( $post_id, $meta_box['show_on']['value'] ) )
                return true;
            else
                return false;
        }
        // Add for Page Template
        public function add_for_page_template( $display, $meta_box ) {

            if( 'page-template' !== $meta_box['show_on']['key'] )
                return $display;

            // Get the current ID
            if( isset( $_GET['post'] ) ) $post_id = $_GET['post'];
            elseif( isset( $_POST['post_ID'] ) ) $post_id = $_POST['post_ID'];
            if( !( isset( $post_id ) || is_page() ) ) return false;

            // Get current template
            $current_template = get_post_meta( $post_id, '_wp_page_template', true );
            
            // If value isn't an array, turn it into one    
            $meta_box['show_on']['value'] = !is_array( $meta_box['show_on']['value'] ) ? array( $meta_box['show_on']['value'] ) : $meta_box['show_on']['value'];

            // See if there's a match
            if( in_array( $current_template, $meta_box['show_on']['value'] ) )
                return true;
            else
                return false;
        }
    }