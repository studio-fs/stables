<?php

    defined( 'ABSPATH' ) || exit;

// Проверка таксономии
$termin = isset(get_queried_object()->term_id) ? get_queried_object()->term_id : '' ?>

<div class="fs-filter_body small mb20" data-term="<?= $termin ?>">
    <div class="grid">
        <span>Виды спорта</span>
        <p data-name="Виды спорта">
            <span class="av-filter_button" data-content="Выездка">Выездка</span>
            <span class="av-filter_button" data-content="Троеборье">Троеборье</span>
            <span class="av-filter_button" data-content="Драйвинг">Драйвинг</span>
            <span class="av-filter_button" data-content="Конный туризм">Конный туризм</span>
            <span class="av-filter_button" data-content="Вольтижировка">Вольтижировка</span>
            <span class="av-filter_button" data-content="Конкур">Конкур</span>
            <span class="av-filter_button" data-content="Любительский спорт">Любительский спорт</span>
            <span class="av-filter_button" data-content="Вестерн">Вестерн</span>
            <span class="av-filter_button" data-content="НХ">НХ</span>
            <span class="av-filter_button" data-content="Разведение">Разведение</span>
            <span class="av-filter_button" data-content="Постановка трюков">Постановка трюков</span>
        </p>
    </div>

    <div class="grid">
        <span>Цена за занятие</span>
        <div class="special_small">
            <p data-name="Цена за занятие р/мес">
                <input type="text" name="price_from" value="" placeholder="От" class="av-filter_input" />
                <input type="text" name="price_to" value="" placeholder="До" class="av-filter_input" />
                <span class="hidden">Аренда денника р/мес</span>
            </p>
            <p data-name="Аренда денника р/мес" class="hidden">
                <input type="text" name="price_from" value="" placeholder="От" class="av-filter_input" />
                <input type="text" name="price_to" value="" placeholder="До" class="av-filter_input" />
                <span class="av-filter_button_s show_more">Ещё фильтры</span>
            </p>
        </div>
    </div>

    <div class="grid">
        <span>Абонемент</span>
        <p data-name="Абонемент">
            <span class="av-filter_button" data-content="Да">Да</span>
            <span class="av-filter_button" data-content="Нет">Нет</span>
        </p>
    </div>

    <div class="grid">
        <span>Прогулки в лес/поле</span>
        <p data-name="Прогулки в лес/поле">
            <span class="av-filter_button" data-content="Да">Да</span>
            <span class="av-filter_button" data-content="Нет">Нет</span>
        </p>
    </div>

    <div class="grid">
        <span>Плац</span>
        <p data-name="Плац">
            <span class="av-filter_button" data-content="Да">Да</span>
            <span class="av-filter_button" data-content="Нет">Нет</span>
        </p>
    </div>

    <div class="grid">
        <span>Крытый манеж</span>
        <p data-name="Крытый манеж">
            <span class="av-filter_button" data-content="Да">Да</span>
            <span class="av-filter_button" data-content="Нет">Нет</span>
        </p>
    </div>

    <div class="grid">
        <span>Теплый туалет</span>
        <p data-name="Теплый туалет">
            <span class="av-filter_button" data-content="Да">Да</span>
            <span class="av-filter_button" data-content="Нет">Нет</span>
        </p>
    </div>

    <div class="grid">
        <span>Раздевалка</span>
        <p data-name="Раздевалка">
            <span class="av-filter_button" data-content="Да">Да</span>
            <span class="av-filter_button" data-content="Нет">Нет</span>
        </p>
    </div>

    <div class="grid">
        <span>Аренда лошадей</span>
        <p data-name="Аренда лошадей">
            <span class="av-filter_button" data-content="Да">Да</span>
            <span class="av-filter_button" data-content="Нет">Нет</span>
        </p>
    </div>

    <div class="grid">
        <span>Проведение вечеринок, свадеб</span>
        <p data-name="Проведение вечеринок, свадеб">
            <span class="av-filter_button" data-content="Да">Да</span>
            <span class="av-filter_button" data-content="Нет">Нет</span>
        </p>
    </div>

    <div class="grid">
        <span>Аренда денника р/мес</span>
        <p data-name="Аренда денника р/мес">
            <input type="text" name="price_from" value="" placeholder="От" class="av-filter_input" />
            <input type="text" name="price_to" value="" placeholder="До" class="av-filter_input" />
        </p>
    </div>

    <div class="grid">
        <span>Аренда летника р/мес</span>
        <p data-name="Аренда летника р/мес">
            <input type="text" name="price_from" value="" placeholder="От" class="av-filter_input" />
            <input type="text" name="price_to" value="" placeholder="До" class="av-filter_input" />
        </p>
    </div>

    <div class="grid">
        <span>Бочка</span>
        <p data-name="Бочка">
            <span class="av-filter_button" data-content="Да">Да</span>
            <span class="av-filter_button" data-content="Нет">Нет</span>
        </p>
    </div>

    <div class="grid">
        <span>Водилка</span>
        <p data-name="Водилка">
            <span class="av-filter_button" data-content="Да">Да</span>
            <span class="av-filter_button" data-content="Нет">Нет</span>
        </p>
    </div>

    <div class="grid">
        <span>Амуничник</span>
        <p data-name="Амуничник">
            <span class="av-filter_button" data-content="Да">Да</span>
            <span class="av-filter_button" data-content="Нет">Нет</span>
        </p>
    </div>

    <div class="grid">
        <span>Зимняя мойка</span>
        <p data-name="Зимняя мойка">
            <span class="av-filter_button" data-content="Да">Да</span>
            <span class="av-filter_button" data-content="Нет">Нет</span>
        </p>
    </div>
    
    <div class="grid">
        <span>Солярий</span>
        <p data-name="Солярий">
            <span class="av-filter_button" data-content="Да">Да</span>
            <span class="av-filter_button" data-content="Нет">Нет</span>
        </p>
    </div>
    
    <div class="grid">
        <span>Берейтор</span>
        <p data-name="Берейтор">
            <span class="av-filter_button" data-content="Да">Да</span>
            <span class="av-filter_button" data-content="Нет">Нет</span>
        </p>
    </div>
    
    <div class="grid">
        <span>Выпас</span>
        <p data-name="Выпас">
            <span class="av-filter_button" data-content="Да">Да</span>
            <span class="av-filter_button" data-content="Нет">Нет</span>
        </p>
    </div>
    
    <div class="grid grid-last">
        <span class="av-filter_button_s reset">Сбросить фильтры</span>
        <span class="av-filter_button_s search">Показать объекты</span>
        <span class="av-filter_button_s show_more">Ещё фильтры</span>
    </div>

</div>