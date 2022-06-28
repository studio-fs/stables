<?php

    namespace com\studio\av\horse;

    defined( 'ABSPATH' ) || exit;

    class UserAuthor {

        public function __construct() {
            $this->replace_from = '/home/c/cf60409/horselife.ru/public_html/';
            $this->replace_to = 'https://horselife.ru/';
            $this->version = '1.0';
        }

        public function search() {
            global $current_user;

            $tax_query = new \WP_Query(array(
                'post_type'     => 'stables',
                'posts_per_page'=> -1,
                'author'        => $current_user->data->ID,
                'post_status'   => 'publish',
                'order'         => 'DESC',
                'orderby'       => 'DESC'
            ));
            //$ret = '';
            if ($tax_query->have_posts()) : while ($tax_query->have_posts()) : $tax_query->the_post(); ?>
                <h2 class="mt80"><?= get_the_title() ?></h2>
                <?php $ID = get_the_id(); // action="<?php the_permalink() ?>
                <form method="post" enctype="multipart/form-data" class="mb40" data-id="<?= $ID ?>">
                    <input type="hidden" name="avstables[post_id]" value="<?php echo get_the_id() ?>">

                    <?php if (isset($_POST['error']) && isset($_POST['error'])) : ?>
                    <p class="error"><?= $_POST['error'] ?></p>
                    <?php endif;
                    if (isset($_POST['avstables']))
                        $this->savePost($_POST['avstables']) ?>

                    <p>Как называется Ваш конный клуб*</p>
                    <input class="av-form-control" type="text" name="avstables[Name]" placeholder="Укажите название конюшни" required="required" value="<?php echo get_the_title() ?>">
                    <p>Краткое описание Вашей конюшни*</p>
                    <textarea class="av-form-control" rows="7" name="avstables[description_HP]" placeholder="Укажите описание Вашей конюшни" required="required" ><?php echo strip_tags( get_field('description_HP') ) ?></textarea>

                    <?php $photos = explode('; ', get_field('gallery_HP'));

                    for($i = 0; $i < count($photos); $i++) : ?>
                        <div class="image_inner">
                            <?= isset($photos[$i]) ? '<img src="'.$photos[$i].'" alt="" />' : '' ?>
                            <span class="to_trash" data-src="<?= $photos[$i] ?>" data-id="<?= $ID ?>"><img src="<?= site_url() ?>/wp-content/plugins/av-horse/assets/trash.svg" alt=""></span>
                        </div>
                        
                    <?php endfor; ?>

                    <p class="mt40">
                    <input name="file[file]" type="file" multiple="true" class="form-input" value="" accept="application/jpg,application/jpeg,application/png" data-multiple-caption="{count} файлов выбрано" id="file-<?= $ID ?>" />
                    <label for="file-<?= $ID ?>" class="file"><b><i class="fa fa-camera" aria-hidden="true"></i></b> <span>Добавить фотографию</span></label></p>

                    <textarea class="av-form-control" rows="1" name="avstables[file]" data-id="<?= $ID ?>"><?= get_field('gallery_HP') ?></textarea>

                    <?php
                    $ret = '';
                    while( have_rows('contact_HP') ) : the_row();
                        $site_HP = get_sub_field('site_HP');
                        $addres_HP = get_sub_field('addres_HP');
                        $gps_HP = get_sub_field('gps_HP');
                        $metro_HP = get_sub_field('metro_HP');
                        $phone_HP = get_sub_field('phone_HP');
                        $highway_HP = get_sub_field('highway_HP');

                        //$isChecked = $subscription ? ' checked="checked"' : '';
                        $val = $site_HP ? $site_HP : '';
                        $ret .= '<p>Сайт</p>
                        <p><input type="text" name="avstables[contact_HP][site_HP]" id="checkbox-avstables-contact_HP-id-1" value="'. $val .'" /></p>';

                        //$isChecked = $subscription ? ' checked="checked"' : '';
                        /*$val = $site_HP ? $site_HP : '';
                        $ret .= '<p>Адрес</p>
                        <p><input type="text" name="avstables[contact_HP][addres_HP]" id="checkbox-avstables-addres_HP-id-1" value="'. $val .'" /></p>';*/

                        //$isChecked = $subscription ? ' checked="checked"' : '';
                        /*$val = $gps_HP ? $gps_HP : '';
                        $ret .= '<p>GPS координаты</p>
                        <p><input type="text" name="avstables[contact_HP][gps_HP]" id="checkbox-avstables-gps_HP-id-1" value="'. $val .'" /></p>';*/

                        //$isChecked = $subscription ? ' checked="checked"' : '';
                        $val = $metro_HP ? $metro_HP : '';
                        $ret .= '<p>Метро</p>
                        <p><input type="text" name="avstables[contact_HP][metro_HP]" id="checkbox-avstables-metro_HP-id-1" value="'. $val .'" /></p>';

                        //$isChecked = $subscription ? ' checked="checked"' : '';
                        $val = $phone_HP ? $phone_HP : '';
                        $ret .= '<p>Телефон</p>
                        <p><input type="text" name="avstables[contact_HP][phone_HP]" id="checkbox-avstables-phone_HP-id-1" value="'. $val .'" /></p>';

                        //$isChecked = $subscription ? ' checked="checked"' : '';
                        $val = $highway_HP ? $highway_HP : '';
                        $ret .= '<p>Шоссе</p>
                        <p><input type="text" name="avstables[contact_HP][highway_HP]" id="checkbox-avstables-highway_HP-id-1" value="'. $val .'" /></p>';

                    endwhile;
                    echo $ret ?>
                    <p>Как добраться</p>
                    <textarea class="av-form-control" rows="7" name="avstables[route_HP]" placeholder="Укажите как добраться" required="required" ><?php echo strip_tags( get_field('route_HP') ) ?></textarea>

                    <?php 
                    $ret = '';
                    while( have_rows('terms') ) : the_row();

                        $sports = get_sub_field('sports');
                        $rental = get_sub_field('rental');
                        $education = get_sub_field('education');
                        $subscription = get_sub_field('subscription');
                        $price = get_sub_field('price');
                        $walks = get_sub_field('walks');
                        $polygon = get_sub_field('polygon');
                        $arena = get_sub_field('arena');
                        $competitions = get_sub_field('competitions');
                        $toilet = get_sub_field('toilet');
                        $cloakroom = get_sub_field('cloakroom');
                        $horse_rental = get_sub_field('horse_rental');
                        $event = get_sub_field('event');

                        //$isChecked = $sports != 'Нет' ? ' checked="checked"' : '';
                        $val = $sports;
                        $ret .= '<p><label for="checkbox-avstables-terms-id-1">Виды спорта*</label></p>
                        <input type="text" name="avstables[terms][sports]" id="checkbox-avstables-terms-id-1" value="'. $val .'" />';
                            //Виды спорта
                            $ret .= '<p>&nbsp;<input type="checkbox" class="sports" name="avstables[terms][sports][1]" value="Выездка" />
                            <label for="checkbox-avstables-termssports-id-1">Выездка</label><br class="show-mobile">';

                            $ret .= '&nbsp;<input type="checkbox" class="sports" name="avstables[terms][sports][2]" value="Конкур" />
                            <label for="checkbox-avstables-termssports-id-1">Конкур</label><br class="show-mobile">';

                            $ret .= '&nbsp;<input type="checkbox" class="sports" name="avstables[terms][sports][3]" value="Любительский спорт" />
                            <label for="checkbox-avstables-termssports-id-1">Любительский спорт</label><br class="show-mobile">';

                            $ret .= '&nbsp;<input type="checkbox" class="sports" name="avstables[terms][sports][4]" value="Драйвинг" />
                            <label for="checkbox-avstables-termssports-id-1">Драйвинг</label><br class="show-mobile">';

                            $ret .= '&nbsp;<input type="checkbox" class="sports" name="avstables[terms][sports][5]" value="Вестерн" />
                            <label for="checkbox-avstables-termssports-id-1">Вестерн</label><br class="show-mobile">';

                            $ret .= '&nbsp;<input type="checkbox" class="sports" name="avstables[terms][sports][6]" value="Вольтижировка" />
                            <label for="checkbox-avstables-termssports-id-1">Вольтижировка</label><br class="show-mobile">';

                            $ret .= '&nbsp;<input type="checkbox" class="sports" name="avstables[terms][sports][7]" value="НХ" />
                            <label for="checkbox-avstables-termssports-id-1">НХ</label><br class="show-mobile">';
                            
                            $ret .= '&nbsp;<input type="checkbox" class="sports" name="avstables[terms][sports][8]" value="Разведение" />
                            <label for="checkbox-avstables-termssports-id-1">Разведение</label><br class="show-mobile">';
                            
                            $ret .= '&nbsp;<input type="checkbox" class="sports" name="avstables[terms][sports][9]" value="Постановка трюков" />
                            <label for="checkbox-avstables-termssports-id-1">Постановка трюков</label><br class="show-mobile">';
                            
                            $ret .= '&nbsp;<input type="checkbox" class="sports" name="avstables[terms][sports][10]" value="Конный туризм" />
                            <label for="checkbox-avstables-termssports-id-1">Конный туризм</label><br class="show-mobile">';
                            
                            $ret .= '&nbsp;<input type="checkbox" class="sports" name="avstables[terms][sports][11]" value="Троеборье" />
                            <label for="checkbox-avstables-termssports-id-1">Троеборье</label></p>';



                        $isChecked = $rental != 'Нет' ? ' checked="checked"' : '';
                        $val = $rental != 'Нет' ? $rental : '';
                        $ret .= '<p><input type="checkbox" name="avstables[terms][rental]" id="checkbox-avstables-terms-id-2" value="'. $val .'" '. $isChecked .' />
                        <label for="checkbox-avstables-terms-id-2">Прокат*</label></p>';

                        $isChecked = $education != 'Нет' ? ' checked="checked"' : '';
                        $val = $education != 'Нет' ? $education : '';
                        $ret .= '<p><input type="checkbox" name="avstables[terms][education]" id="checkbox-avstables-terms-id-3" value="'. $val .'" '. $isChecked .' />
                        <label for="checkbox-avstables-terms-id-3">Обучение верховой езде взрослых и детей*</label></p>';

                        $isChecked = $subscription ? ' checked="checked"' : '';
                        $val = $subscription ? $subscription : '';
                        $ret .= '<p><input type="checkbox" name="avstables[terms][subscription]" id="checkbox-avstables-terms-id-4" value="'. $val .'" '. $isChecked .' />
                        <label for="checkbox-avstables-terms-id-4">Абонемент на занятия</label></p>';

                        //$isChecked = $price ? ' checked="checked"' : '';
                        $val = $price ? $price : '';
                        $ret .= '<p>Цена на разовое занятие (руб.)</p>
                        <p><input type="number" name="avstables[terms][price]" id="checkbox-avstables-terms-id-5" value="'. $val .'" /></p>';

                        $isChecked = $walks == 'Heт' ? '' :' checked="checked"';
                        $val = $walks != 'Нет' ? $walks : '';
                        $ret .= '<p><input type="checkbox" name="avstables[terms][walks]" id="checkbox-avstables-terms-id-6" value="'. $val .'" '. $isChecked .' />
                        <label for="checkbox-avstables-terms-id-6">Прогулки в лес/поле*</label></p>';

                        $isChecked = $polygon != 'Нет' ? ' checked="checked"' : '';
                        $val = $polygon != 'Нет' ? $polygon : '';
                        $ret .= '<p><input type="checkbox" name="avstables[terms][polygon]" id="checkbox-avstables-terms-id-7" value="'. $val .'" '. $isChecked .' />
                        <label for="checkbox-avstables-terms-id-7">Плац*</label></p>';

                        $isChecked = $arena != 'Нет' ? ' checked="checked"' : '';
                        $val = $arena != 'Нет' ? $arena : '';
                        $ret .= '<p><input type="checkbox" name="avstables[terms][arena]" id="checkbox-avstables-terms-id-8" value="'. $val .'" '. $isChecked .' />
                        <label for="checkbox-avstables-terms-id-8">Крытый манеж</label></p>';

                        $isChecked = $competitions != 'Нет' ? ' checked="checked"' : '';
                        $val = $competitions != 'Нет' ? $competitions : '';
                        $ret .= '<p><input type="checkbox" name="avstables[terms][competitions]" id="checkbox-avstables-terms-id-9" value="'. $val .'" '. $isChecked .' />
                        <label for="checkbox-avstables-terms-id-9">Участие в соревнованиях</label></p>';

                        $isChecked = $toilet != 'Нет' ? ' checked="checked"' : '';
                        $val = $toilet != 'Нет' ? $toilet : '';
                        $ret .= '<p><input type="checkbox" name="avstables[terms][toilet]" id="checkbox-avstables-terms-id-10" value="'. $val .'" '. $isChecked .' /> <label for="checkbox-avstables-terms-id-10">Теплый туалет</label></p>';

                        $isChecked = $cloakroom != 'Нет' ? ' checked="checked"' : '';
                        $val = $cloakroom != 'Нет' ? $cloakroom : '';
                        $ret .= '<p><input type="checkbox" name="avstables[terms][cloakroom]" id="checkbox-avstables-terms-id-11" value="'. $val .'" '. $isChecked .' />
                        <label for="checkbox-avstables-terms-id-11">Раздевалка</label></p>';

                        $isChecked = $horse_rental != 'Нет' ? ' checked="checked"' : '';
                        $val = $horse_rental != 'Нет' ? $horse_rental : '';
                        $ret .= '<p><input type="checkbox" name="avstables[terms][horse_rental]" id="checkbox-avstables-terms-id-12" value="'. $val .'" '. $isChecked .' />
                        <label for="checkbox-avstables-terms-id-12">Аренда лошадей</label></p>';

                        $isChecked = $event != 'Нет' ? ' checked="checked"' : '';
                        $val = $event != 'Нет' ? $event : '';
                        $ret .= '<p><input type="checkbox" name="avstables[terms][event]" id="checkbox-avstables-terms-id-13" value="'. $val .'" '. $isChecked .' />
                        <label for="checkbox-avstables-terms-id-13">Проведение вечеринок, свадеб, мероприятий</label></p>';

                    endwhile;
                    echo $ret;
                    unset($sports, $rental, $education, $subscription, $price, $walks, $polygon, $arena, $competitions, $toilet, $cloakroom, $horse_rental, $event);

                    $ret = '';
                    while( have_rows('terms_owner') ) : the_row();
                        $rental_stall = get_sub_field('rental_stall');
                        $price_stall = get_sub_field('price_stall');
                        $rental_barn = get_sub_field('rental_barn');
                        $price_barn = get_sub_field('price_barn');
                        $barrel = get_sub_field('barrel');
                        $horse_wallkers = get_sub_field('horse_wallkers');
                        $ammunition_horses = get_sub_field('ammunition_horses');
                        $winter_wash = get_sub_field('winter_wash');
                        $solarium = get_sub_field('solarium');
                        $bereytor = get_sub_field('bereytor');
                        $grazing = get_sub_field('grazing');

                        $isChecked = $rental_stall != 'Нет' ? ' checked="checked"' : '';
                        $val = $rental_stall != 'Нет' ? $rental_stall : '';
                        $ret .= '<p><input type="checkbox" name="avstables[terms_owner][rental_stall]" id="checkbox-avstables-terms_owner-id-1" value="'. $val .'" '. $isChecked .' />
                        <label for="checkbox-avstables-terms-id-12">Аренда денников</label></p>';

                        //$isChecked = $price ? ' checked="checked"' : '';
                        $val = $price_stall ? $price_stall : '';
                        $ret .= '<p>Цена аренды денника в мес</p>
                        <p><input type="text" name="avstables[terms_owner][price_stall]" id="checkbox-avstables-terms_owner-id-2" value="'. $val .'" /></p>';

                        $isChecked = $rental_barn != 'Нет' ? ' checked="checked"' : '';
                        $val = $rental_barn != 'Нет' ? $rental_barn : '';
                        $ret .= '<p><input type="checkbox" name="avstables[terms_owner][rental_barn]" id="checkbox-avstables-terms_owner-id-3" value="'. $val .'" '. $isChecked .' />
                        <label for="checkbox-avstables-terms-id-12">Аренда летника</label></p>';

                        //$isChecked = $price ? ' checked="checked"' : '';
                        $val = $price_barn ? $price_barn : '';
                        $ret .= '<p>Цена аренды летника в мес</p>
                        <p><input type="text" name="avstables[terms_owner][price_barn]" id="checkbox-avstables-terms_owner-id-4" value="'. $val .'" /></p>';

                        $isChecked = $barrel != 'Нет' ? ' checked="checked"' : '';
                        $val = $barrel != 'Нет' ? $barrel : '';
                        $ret .= '<p><input type="checkbox" name="avstables[terms_owner][barrel]" id="checkbox-avstables-terms_owner-id-5" value="'. $val .'" '. $isChecked .' />
                        <label for="checkbox-avstables-terms-id-12">Бочка</label></p>';

                        $isChecked = $horse_wallkers != 'Нет' ? ' checked="checked"' : '';
                        $val = $horse_wallkers != 'Нет' ? $horse_wallkers : '';
                        $ret .= '<p><input type="checkbox" name="avstables[terms_owner][horse_wallkers]" id="checkbox-avstables-terms_owner-id-6" value="'. $val .'" '. $isChecked .' />
                        <label for="checkbox-avstables-terms-id-12">Водилка</label></p>';

                        $isChecked = $ammunition_horses != 'Нет' ? ' checked="checked"' : '';
                        $val = $ammunition_horses != 'Нет' ? $ammunition_horses : '';
                        $ret .= '<p><input type="checkbox" name="avstables[terms_owner][ammunition_horses]" id="checkbox-avstables-terms_owner-id-7" value="'. $val .'" '. $isChecked .' />
                        <label for="checkbox-avstables-terms-id-12">Амуничник</label></p>';

                        $isChecked = $winter_wash != 'Нет' ? ' checked="checked"' : '';
                        $val = $winter_wash != 'Нет' ? $winter_wash : '';
                        $ret .= '<p><input type="checkbox" name="avstables[terms_owner][winter_wash]" id="checkbox-avstables-terms_owner-id-8" value="'. $val .'" '. $isChecked .' />
                        <label for="checkbox-avstables-terms-id-12">Зимняя мойка</label></p>';

                        $isChecked = $solarium != 'Нет' ? ' checked="checked"' : '';
                        $val = $solarium != 'Нет' ? $solarium : '';
                        $ret .= '<p><input type="checkbox" name="avstables[terms_owner][solarium]" id="checkbox-avstables-terms_owner-id-9" value="'. $val .'" '. $isChecked .' />
                        <label for="checkbox-avstables-terms-id-12">Солярий</label></p>';

                        $isChecked = $bereytor != 'Нет' ? ' checked="checked"' : '';
                        $val = $bereytor != 'Нет' ? $bereytor : '';
                        $ret .= '<p><input type="checkbox" name="avstables[terms_owner][bereytor]" id="checkbox-avstables-terms_owner-id-10" value="'. $val .'" '. $isChecked .' />
                        <label for="checkbox-avstables-terms-id-12">Берейтор</label></p>';

                        $isChecked = $grazing != 'Нет' ? ' checked="checked"' : '';
                        $val = $grazing != 'Нет' ? $grazing : '';
                        $ret .= '<p><input type="checkbox" name="avstables[terms_owner][grazing]" id="checkbox-avstables-terms_owner-id-10" value="'. $val .'" '. $isChecked .' />
                        <label for="checkbox-avstables-terms-id-12">Выпас</label></p>';

                    endwhile;
                    echo $ret;
                    unset($rental_stall, $price_stall, $rental_barn, $price_barn, $barrel, $horse_wallkers, $ammunition_horses, $winter_wash, $solarium, $bereytor, $grazing, $ret) ?>

                    <p>Ссылка на ютуб</p>
                    <p><input type="text" name="avstables[youtube_HP]" value="<?= get_field('youtube_HP') ?>" /></p>

                    <button type="submit">Обновить сведения о конюшне</button>
                    <?php wp_nonce_field() ?>
                </form>

                <?php
                if (isset($_POST['avstables']['post_id'])) {
                    ?>
                    <script>
                        ;(function($, window, undefined) {

                          "use strict";
                            
                            setTimeout(function() {
                                jQuery( "#dialog" ).dialog();
                            },3500);

                        })(jQuery, window);
                    </script>
                    <div id="dialog" title="Обновлено">
                      <p>Сведения о конюшне обновились</p>
                    </div>
                    <?php
                }
                ?>

           <?php endwhile; else:
            echo '<h2>Вы ещё не добавили конюшню</h2>';
           endif;
        }

        private function savePost($post) {

            if (!$post['post_id']) {
                $_POST['error'] = 'Запись не может быть обновлена. Обновите страницу и попробуйте ещё раз';
                return;
            }

                // Add the content of the form to $post as an array
                $posted_data = array(
                    'ID'            => $post['post_id'],
                    'post_parent'   => 0,
                    'post_status'   => 'publish',
                    'post_title'    => $post['Name'],
                    'post_content'  => '',
                    'post_excerpt'  =>  '',
                    'ping_status'   => 'closed',
                    //'get_the_post_thumbnail'=> $anexo,
                    'meta_input' => array(
                        'description_HP'    => $post['description_HP'], //название конюшни
                        'route_HP'          => $post['route_HP'], //как добраться
                        'youtube_HP'        => $post['youtube_HP'],
                        'gallery_HP'           => $post['file'], // field value галерея acf
                        //'_gallery'          => 'gallery_HP' // field key reverence  галерея acf
                    )
                );
                wp_update_post($posted_data);

                //acf repeater
                //контакты
                $values = array(
                    'site_HP'   => $post['contact_HP']['site_HP'], // содержимое попадает в виде ["\u0414\u0430"] iso-8859, кодируем utf8_encode() iconv('ISO-8859-1', "UTF-8//TRANSLIT",
                    'addres_HP' => $post['contact_HP']['addres_HP'],
                    'gps_HP'    => $post['contact_HP']['gps_HP'],
                    'metro_HP'  => $post['contact_HP']['metro_HP'],
                    'phone_HP'  => $post['contact_HP']['phone_HP'],
                    'highway_HP'=> $post['contact_HP']['highway_HP']
                );
                update_field( 'contact_HP', $values, $post['post_id'] );
                //Условия
                $values = array(
                    'sports'        => $post['terms']['sports'],
                    'rental'        => $post['terms']['rental'],
                    'education'     => $post['terms']['education'],
                    'subscription'  => $post['terms']['subscription'],
                    'price'         => $post['terms']['price'],
                    'walks'         => $post['terms']['walks'],
                    'polygon'       => $post['terms']['polygon'],
                    'arena'         => $post['terms']['arena'],
                    'competitions'  => $post['terms']['competitions'],
                    'toilet'        => $post['terms']['toilet'],
                    'cloakroom'     => $post['terms']['cloakroom'],
                    'horse_rental'  => $post['terms']['horse_rental'],
                    'event'         => $post['terms']['event']
                );
                update_field( 'terms', $values, $post['post_id'] );
                //Условия для владельцев лошадей
                $values = array(
                    'rental_stall'      => $post['terms_owner']['rental_stall'],
                    'price_stall'       => $post['terms_owner']['price_stall'],
                    'rental_barn'       => $post['terms_owner']['rental_barn'],
                    'price_barn'        => $post['terms_owner']['price_barn'],
                    'barrel'            => $post['terms_owner']['barrel'],
                    'horse_wallkers'    => $post['terms_owner']['horse_wallkers'],
                    'ammunition_horses' => $post['terms_owner']['ammunition_horses'],
                    'winter_wash'       => $post['terms_owner']['winter_wash'],
                    'solarium'          => $post['terms_owner']['solarium'],
                    'bereytor'          => $post['terms_owner']['bereytor'],
                    'grazing'           => $post['terms_owner']['grazing']
                );
                update_field( 'terms_owner', $values, $post['post_id'] );

                //Обработка новых загруженных изображений
                if (!empty($_FILES['file']['tmp_name']['file']))
                    $this->sv_img($post['post_id'], $_FILES);
            
                $_POST['error'] = 'Запись успешно обновлена';
                return $_POST['error'];

        }

        //Сохраняем изображения
        private function sv_img($post_id, $files) {
            $dir = WP_CONTENT_DIR.'/plugins/av-horse/uploads/'.$post_id; // Full Path
            $finalPath = site_url().'/wp-content/plugins/av-horse/uploads/'.$post_id.'/';
            $img = '';
            $tmp = $_FILES['file']['tmp_name']['file'];

                $url = $_FILES['file']['name']['file'];
                $type = substr($url, -4, 4);
                $name = time().$type;
                /*
                В PHP есть предопределённая константа DIRECTORY_SEPARATOR, содержащая разделитель пути. Для Windows это «\», для Linux и остальных — «/».
                Так как Windows понимает оба разделителя, достаточно использовать в коде разделитель Linux вместо константы.
                Тем не менее, DIRECTORY_SEPARATOR полезен. Все функции, отдающие путь (вроде realpath), отдают его с специфичными для ОС разделителями. Чтобы разбить такой путь на составляющие как раз удобно использовать константу
                */
                $img = $dir . DIRECTORY_SEPARATOR . $name; //Пишем путь к изображениям

                is_dir($dir) || @mkdir($dir) || die("Can't Create folder");
                move_uploaded_file( $tmp, $dir.'/'.$name );
                //copy($url, $dir . DIRECTORY_SEPARATOR . $name);


            //Обновляем метаполе
            $photos = get_post_meta($post_id, 'gallery_HP', true);
            update_post_meta( $post_id, 'gallery_HP', $photos.'; '.$finalPath.$name );
        }

    }