<?php

    namespace com\studio\av\horse;

    defined( 'ABSPATH' ) || exit;

    class ContactForm7 extends AVHorse {

        public function __construct() {
            $this->replace_from = '/home/c/cf60409/horselife.ru/public_html/';
            $this->replace_to = site_url().'/';

            add_action('wpcf7_mail_sent', array($this, 'interceptor'), 99, 9 ); // подсасываемся к contact form 7
            //define( 'WPCF7_UPLOADS_TMP_DIR', 'contact-form7-upload' ); // сохранение в каталог

            // т.к. исправить проблему с кодировкой в момент сохранения поста не выходит, добавляемся на обновление поста
            add_action( 'save_post', array($this, 'upd_unicode'), 60, 3 );
            //костыль для формы конюшни
            add_shortcode( 'get_user_id', array($this, 'get_userID') );

            //Отлавливаем событие смены статуса
            add_action(  'transition_post_status',  array($this, 'change_status'), 10, 3 );
        }

        //Сохраняем изображения
        private function sv_img($posted_data, $uploaded_files, $post_id) {
            $dir = $this->replace_from . 'wp-content/plugins/av-horse/uploads/'.$post_id; // Full Path
            $img = '';

            //первое изображение
            if($uploaded_files['file-763'][0]) :

                $url = str_replace( $this->replace_from, $this->replace_to, $uploaded_files['file-763'][0] );
                $type = substr($url, -4, 4);
                $name = time().$type;

                /*
                В PHP есть предопределённая константа DIRECTORY_SEPARATOR, содержащая разделитель пути. Для Windows это «\», для Linux и остальных — «/».
                Так как Windows понимает оба разделителя, достаточно использовать в коде разделитель Linux вместо константы.
                Тем не менее, DIRECTORY_SEPARATOR полезен. Все функции, отдающие путь (вроде realpath), отдают его с специфичными для ОС разделителями. Чтобы разбить такой путь на составляющие как раз удобно использовать константу
                */
                $img = str_replace( $this->replace_from, $this->replace_to, $dir . DIRECTORY_SEPARATOR . $name ); //Пишем путь к изображениям

                is_dir($dir) || @mkdir($dir) || die("Can't Create folder");
                copy($url, $dir . DIRECTORY_SEPARATOR . $name);
                //move_uploaded_file( $url, $dir . DIRECTORY_SEPARATOR . $name );

              //debug
              $this->debug('sv_img - создали директорию, сохранили картинку', $img);
            endif;

            //остальные (id не по порядку)
            for($i = 2; $i <= 5; $i++) {
                if(!$uploaded_files['file-18'.$i][0])
                    continue;

                $url = str_replace('/home/c/cf60409/horselife.ru/public_html/', 'https://horselife.ru/', $uploaded_files['file-18'.$i][0]); // Преобразуем строку в нужный url
                $type = substr($url, -4, 4); // Узнаём тип файла НО, НЕ ДЕЛАЕМ ПРОВЕРКУ
                $name = ( time() + $i ).$type; // задаём название + $i, т.к. обработка происходит слишком быстро

                $img .= '; '.str_replace( $this->replace_from, $this->replace_to, $dir . DIRECTORY_SEPARATOR . $name ); //Пишем путь к изображениям

                is_dir($dir) || @mkdir($dir) || die("Can't Create folder"); //Проверяем каждый раз, на случай, если первого изображения нет
                copy($url, $dir . DIRECTORY_SEPARATOR . $name); //сохраняем
                //move_uploaded_file( $url, $dir . DIRECTORY_SEPARATOR . $name );
            }
            //Обновляем метаполе
            update_post_meta( $post_id, 'gallery_HP', $img );

              //debug
              $this->debug('sv_img - конец', $post_id, $img);
        }


        //Создаём пост конюшни
        private function cr_post($posted_data) {

            //date_default_timezone_set('Europe/Moscow'); //если поставить время по Москву, появляется адпись: Публикация просрочена.
            $newDate = date('Y-m-d H:i:s');

                // Add the content of the form to $post as an array
                $post = array(
                    'post_type'     => 'stables', // Could be: `page` or your CPT
                    'post_parent'   => 0,
                    'post_status'   => 'draft',   // Could be: publish
                    'post_title'    => wp_strip_all_tags($posted_data['stable-name']),
                    'post_content'  => '',
                    'post_excerpt'  =>  '',
                    'ping_status'       => 'closed',
                    'comment_status' => 'open', 
                    //'post_category' => array($data['category']), //
                    //'tags_input'    => array($data['category']),
                    //'tax_input'      => array( 'taxonomy_name' => array( 'term', 'term2', 'term3' ) ),
                    //'tax_input'      => array(
                    //  'roms_section' => $data['category']
                    //), //$data['category']
                    'post_author'   => $posted_data['av_user_id'], //get_current_user_id(),
                    'post_date'     => $newDate,
                    'post_date_gmt' => $newDate,
                    //'get_the_post_thumbnail'=> $anexo,
                    'meta_input' => array(
                        'description_HP'  => $posted_data['opisanie-306'], //название конюшни
                        'route_HP'        => $posted_data['car-307'], //как добраться
                        'route_HP_bus'    => $posted_data['bus-307'], //как добраться
                        'youtube_HP'      => $posted_data['video-648'],
                        'gallery'         => $picture_id, // field value галерея acf
                        '_gallery'        => 'gallery_HP' // field key reverence  галерея acf
                    )
                );
                //wp_insert_post($post);
                $post_id = wp_insert_post($post);

                wp_set_object_terms( $post_id, $posted_data['menu-679'], 'stable-city', true); //$data['category'] AV_CATEGORY

                //acf repeater
                //контакты
                $values = array(
                    'site_HP'   => $posted_data['site-307'], // содержимое попадает в виде ["\u0414\u0430"] iso-8859, кодируем utf8_encode() iconv('ISO-8859-1', "UTF-8//TRANSLIT",
                    'addres_HP' => $posted_data['adress-191'],
                    'gps_HP'    => $posted_data['GPS-307'],
                    'metro_HP'  => $posted_data['metro-307'],
                    'phone_HP'  => $posted_data['tel-307'],
                    'highway_HP'=> $posted_data['shosse-307']
                );
                update_field( 'contact_HP', $values, $post_id );
                //Условия
                $values = array(
                    'sports'        => $posted_data['sport-111'],
                    'rental'    => $posted_data['prokat-544'],
                    'education'     => $posted_data['uroki-544'],
                    'subscription'  => $posted_data['abonement-544'],
                    'price' => $posted_data['cena-475'],
                    'walks'=> $posted_data['progulki-544'],
                    'polygon'=> $posted_data['plac-544'],
                    'arena'=> $posted_data['maneg-544'],
                    'competitions'=> $posted_data['compet-544'],
                    'toilet'=> $posted_data['tualet-544'],
                    'cloakroom'=> $posted_data['razdevalka-544'],
                    'horse_rental'=> $posted_data['ar-544'],
                    'event'=> $posted_data['party-544']
                );
                update_field( 'terms', $values, $post_id );
                //Условия для владельцев лошадей
                $values = array(
                    'rental_stall'  => $posted_data['arden-544'],
                    'price_stall'       => $posted_data['dennik-475'],
                    'rental_barn'       => $posted_data['letnik-544'],
                    'price_barn'        => $posted_data['dennik-475'],
                    'barrel'                => $posted_data['bochka-544'],
                    'horse_wallkers'=> $posted_data['vodilka-544'],
                    'ammunition_horses'=> $posted_data['amun-544'],
                    'winter_wash'       => $posted_data['moyka-544'],
                    'solarium'          => $posted_data['sol-544'],
                    'bereytor'          => $posted_data['ber-544'],
                    'grazing'               => $posted_data['vipas-544']
                );
                update_field( 'terms_owner', $values, $post_id );

              //debug
              //$this->debug('cr_post - конец', $post_id, $posted_data);

                return $post_id; // Возвращаем id только что созданного поста
        }



        //Перехватчик писем
        public function interceptor($contact_form) {
            if($contact_form->title != 'Внесите КСК') // или по id $contant_form->id()
                return;

            $submission = \WPCF7_Submission::get_instance();
            $posted_data = $submission->get_posted_data(); // данные формы
            $uploaded_files = $submission->uploaded_files(); // картинки

            $post_id = $this->cr_post($posted_data);
            $this->sv_img($posted_data, $uploaded_files, $post_id);
            //$this->send_email($posted_data, $post_id); //отправляем письмо автору
        }

        //Смена статуса
        public function change_status($new_status, $old_status, $post) {
          if ($old_status == 'draft' && $post->post_type == 'stables') {
            $recepient = get_user_by('ID', $post->post_author)->data;
            $this->send_email_cs($recepient, $post);
            //$this->debug($recepient);
          }
        }

        //Отправляем письмо автору по смене статуса
        private function send_email_cs($recepient, $post) {
              $subject = 'Ваш емейл был указан при регистрации на сайте horselife.ru';
              $headers  = "From: no-reply@horselife.ru" . PHP_EOL;
              $headers .= "MIME-Version: 1.0" . PHP_EOL;
              $headers .= "Content-Type: text/html;charset=UTF-8" . PHP_EOL;

          $body = 'Здравствуйте, '.$recepient->display_name.'!
              Информация о Вашей конюшне прошла модерацию и опубликована на сайте.
              Редактирование информации о Вашей конюшне доступно по ссылке: <a href="'.get_the_permalink($post->ID).'">'.$post->post_title.'</a>.

              С уважением, 
              редакция HorseLife.ru
              info@HorseLife.ru';

          mail( $recepient->user_email, $subject, $body, $headers );
        }

        //Отправляем письмо автору по добавлению конюшни на сайт
        private function send_email($posted_data, $post_id) {
              $subject = 'Ваш емейл был указан при регистрации на сайте horselife.ru';
              $headers  = "From: no-reply@horselife.ru" . PHP_EOL;
              $headers .= "MIME-Version: 1.0" . PHP_EOL;
              $headers .= "Content-Type: text/html;charset=UTF-8" . PHP_EOL;
          
              if (get_user_by('ID', get_current_user_id())->data->user_email)
                $recepient = get_user_by('ID', get_current_user_id())->data->user_email;
              else
                $recepient = get_user_by('ID', $posted_data['av_user_id'])->data->user_email;

          $body = 'Здравствуйте, '.$posted_data['your-name'].'!
              Информация о Вашей конюшне прошла модерацию и опубликована на сайте.
              Редактирование информации о Вашей конюшне доступно по ссылке: <a href="'.get_the_permalink($post_id).'">'.$posted_data['stable-name'].'</a>.

              С уважением, 
              редакция HorseLife.ru
              info@HorseLife.ru';

          mail( $recepient, $subject, $body, $headers );
        }

        //Он не всегда определяет id пользователя сам, поэтому поможем ему
        public function get_userID() {
            global $current_user;
            return '<input name="av_user_id" type="hidden" value="'.$current_user->data->ID.'" />';
        }

    // Цепляемся за событие обновления поста и исправляем кодировку
    public function upd_unicode($post_ID, $post, $update) {
        if( $update != '1' || $post->post_type != 'stables' && $update != '1' )
            return;

                $values = get_field('terms');
                $values = $this->jdecoder( $values );
                update_field( 'terms', $values, $post->ID );

                $values = get_field('terms_owner');
                $values = $this->jdecoder( $values );
                update_field( 'terms_owner', $values, $post->ID );

    }

        // Декодер Юникода скорее всего нам не понадобится
        private function jdecoder($json_str) {
           $cyr_chars = array (
               '\u0430' => 'а', '\u0410' => 'А',
               '\u0431' => 'б', '\u0411' => 'Б',
               '\u0432' => 'в', '\u0412' => 'В',
               '\u0433' => 'г', '\u0413' => 'Г',
               '\u0434' => 'д', '\u0414' => 'Д',
               '\u0435' => 'е', '\u0415' => 'Е',
               '\u0451' => 'ё', '\u0401' => 'Ё',
               '\u0436' => 'ж', '\u0416' => 'Ж',
               '\u0437' => 'з', '\u0417' => 'З',
               '\u0438' => 'и', '\u0418' => 'И',
               '\u0439' => 'й', '\u0419' => 'Й',
               '\u043a' => 'к', '\u041a' => 'К',
               '\u043b' => 'л', '\u041b' => 'Л',
               '\u043c' => 'м', '\u041c' => 'М',
               '\u043d' => 'н', '\u041d' => 'Н',
               '\u043e' => 'о', '\u041e' => 'О',
               '\u043f' => 'п', '\u041f' => 'П',
               '\u0440' => 'р', '\u0420' => 'Р',
               '\u0441' => 'с', '\u0421' => 'С',
               '\u0442' => 'т', '\u0422' => 'Т',
               '\u0443' => 'у', '\u0423' => 'У',
               '\u0444' => 'ф', '\u0424' => 'Ф',
               '\u0445' => 'х', '\u0425' => 'Х',
               '\u0446' => 'ц', '\u0426' => 'Ц',
               '\u0447' => 'ч', '\u0427' => 'Ч',
               '\u0448' => 'ш', '\u0428' => 'Ш',
               '\u0449' => 'щ', '\u0429' => 'Щ',
               '\u044a' => 'ъ', '\u042a' => 'Ъ',
               '\u044b' => 'ы', '\u042b' => 'Ы',
               '\u044c' => 'ь', '\u042c' => 'Ь',
               '\u044d' => 'э', '\u042d' => 'Э',
               '\u044e' => 'ю', '\u042e' => 'Ю',
               '\u044f' => 'я', '\u042f' => 'Я',

               '\r' => '',
               '\n' => '<br />',
               '\t' => '',
               //Это не относится к юникоду, просто в данном случае это надо удалить)))
               '%20'=> ' ',
               '["' => '',
               '"]' => '',
               '","'=> ', '
           );

           foreach ($cyr_chars as $key => $value) {
               $json_str = str_replace($key, $value, $json_str);
           }
           return $json_str;
        }

    }