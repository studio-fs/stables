<?php
    namespace com\studio\av\horse;

    defined( 'ABSPATH' ) || exit;

    require_once(__DIR__.'/lib/post_class.php'); // регистрируем пост тайп
    new Post();

    require_once(__DIR__.'/lib/map_class.php'); // работаем с картами
    new Map();

    require_once(__DIR__.'/lib/contactform7_class.php'); // обработка формы
    new ContactForm7();

    require_once(__DIR__.'/lib/metabox_class.php'); // метабоксы, для визуального просмотра фотографий в админке
    new MetaBox();

    require_once(__DIR__.'/lib/filter_class.php'); // фильтр
    new Filter();

    require_once(__DIR__.'/lib/userauthor_class.php'); // страница редактирования своей конюшни



    class AVHorse {
        public function debug(...$data) {
            $ret = '';
            $ret .='----------------------'.PHP_EOL;
            $ret .= 'Текущее время: '.date('d.m.Y H:i:s').PHP_EOL;
            $i = 0;
            foreach ($data as $d) :
                $ret .= ($i++).' - '.print_r($d, true).PHP_EOL;
            endforeach;

            $fp = fopen(__DIR__.'/debug.log', 'a+'); //- а+ добавляет новые данные после существующих
            fwrite(
              $fp, 
              'data: ' . $ret
              //'data: '.json_encode($ret, JSON_UNESCAPED_UNICODE)
          );
            fclose($fp);
        }
    }