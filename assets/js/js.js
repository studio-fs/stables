;(function($, window, undefined) {

    "use strict";

if (typeof ymaps !=="undefined" && jQuery('#map').length) {
    ymaps.ready(init);
}

    function init() {
        var myPlacemark,
            myMap = new ymaps.Map('map', {
                center: [55.753994, 37.622093],
                zoom: 10,
                suppressMapOpenBlock: true,
                //controls: []
            }, {
                searchControlProvider: 'yandex#search'
            });

        // Слушаем клик на карте.
        myMap.events.add('click', function (e) {
            var coords = e.get('coords');

            // Если метка уже создана – просто передвигаем ее.
            if (myPlacemark) {
                myPlacemark.geometry.setCoordinates(coords);
            }
            // Если нет – создаем.
            else {
                myPlacemark = createPlacemark(coords);
                myMap.geoObjects.add(myPlacemark);
                // Слушаем событие окончания перетаскивания на метке.
                myPlacemark.events.add('dragend', function () {
                    getAddress(myPlacemark.geometry.getCoordinates());
                });
            }
            //Записываем для дальнейшего использования
            insertGeo( myPlacemark.geometry.getCoordinates() );
            
            getAddress(coords);
        });

        // Создание метки.
        function createPlacemark(coords) {
            return new ymaps.Placemark(coords, {
                iconCaption: 'поиск...'
            }, {
                preset: 'islands#violetDotIconWithCaption',
                draggable: true
            });
        }

        // Определяем адрес по координатам (обратное геокодирование).
        function getAddress(coords) {
            myPlacemark.properties.set('iconCaption', 'поиск...');
            ymaps.geocode(coords).then(function (res) {
                var firstGeoObject = res.geoObjects.get(0);

                myPlacemark.properties
                    .set({
                        // Формируем строку с данными об объекте.
                        iconCaption: [
                            // Название населенного пункта или вышестоящее административно-территориальное образование.
                            firstGeoObject.getLocalities().length ? firstGeoObject.getLocalities() : firstGeoObject.getAdministrativeAreas(),
                            // Получаем путь до топонима, если метод вернул null, запрашиваем наименование здания.
                            firstGeoObject.getThoroughfare() || firstGeoObject.getPremise()
                        ].filter(Boolean).join(', '),
                        // В качестве контента балуна задаем строку с адресом объекта.
                        balloonContent: firstGeoObject.getAddressLine()
                    });
                    //Записываем для дальнейшего использования
                    insertAddress( firstGeoObject.getAddressLine() );
            });

        }


        /*
        * Insert geo
        */
        function insertGeo(coords) {
            //jQuery('.av-map-data span.geo').text(coords);
            jQuery('input[name="GPS-307"]').val(coords);
        }
        /*
        * Insert address
        */
        function insertAddress(address) {
            var addr = address.split(', ');
            // jQuery('.av-map-data span.country').text(addr[0]);
            // jQuery('.av-map-data span.city').text(addr[1]);
            // jQuery('.av-map-data span.street').text(addr[2]);
            // if(!addr[3] || 0 === addr[3].length)
            //     jQuery('.av-map-data span.house').text('');
            // else
            //     jQuery('.av-map-data span.house').text(addr[3]);
            //вставляем в форму связи
            jQuery('input[name="menu-679"]').val(addr[1]);
            jQuery('input[name="adress-191"]').val(address);

        }

    }

})(jQuery, window);