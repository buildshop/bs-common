/*
 * функция создания карты
 * принимаемый параметр - ID блока, в котором будет инициализирована карта, string
 */

function map(containerId, options) {
    var ymap = new ymaps.Map(containerId, {
        // Центр карты, по умолчанию Одесса
        center: [options.center.let, options.center.lng],
        // Коэффициент масштабирования
        zoom: options.zoom,
        type: options.type,
        controls: []

    });



    if (!options.drag) {
        ymap.behaviors.disable('drag');
    }
    if (!options.scrollZoom) {
        ymap.behaviors.disable('scrollZoom');
    }

    if (options.zoomControl.enable) {

        var zoomControl = new ymaps.control.ZoomControl({
            options: {
                size: "large",
                position: {
                    top: options.zoomControl.top,
                    left: options.zoomControl.left,
                    right: options.zoomControl.right,
                    bottom: options.zoomControl.bottom
                }
            }
        });
        ymap.controls.add(zoomControl);
    }
    ymap.placemarkset = new ymaps.GeoObjectCollection();
    ymap.geoObjects.add(ymap.placemarkset);
    //ymap.geoObjects.add(new ymaps.GeoObjectCollection());
    ymap.canmark = true;
    return ymap;
}

/**
 * создание метки
 */

function Placemark(params) {
    var placemark = new ymaps.Placemark(
            [params.coordy, params.coordx],
            params.properties,
            params.options
            );
    return placemark;
}

function setCoords(coordY, coordX) {
    $('input#ContactsMarkers_coords').val(coordX + ',' + coordY);

    $.jGrowl('Кординаты устрановны.')
}
function setContentBalloon(value) {
    $('#ContactsMarkers_balloon_content_body').html(value);

    tinymce.activeEditor.setContent(value)
    $.jGrowl('Содержание балуна устрановно.')
}

// Определяем адрес по координатам (обратное геокодирование)
function getAddress(coords, myPlacemark) {

    myPlacemark.properties.set('iconContent', 'поиск...');
    ymaps.geocode(coords).then(function (res) {
        var firstGeoObject = res.geoObjects.get(0);

        myPlacemark.properties
                .set({
                    iconContent: firstGeoObject.properties.get('name'),
                    balloonContent: firstGeoObject.properties.get('text')
                });
    });
}
/**
 * управление яндекс-картой. 
 **/
function mapsApi() {
    /*************  внутренние переменные *************/
    this.maps = new Object; //тут будет хранится объект миникарты
    this.loaded = false;// тут будет хранится флаг загруженности, чтобы каждый раз не проверять
    this.tempcontainer = new Array;//тут будут храниться функции,  которе был вызваны до загрузки АП



    /*************  конструктор и реализация очереди выполнения *************/

    //собственно, конструктор
    this.__constructor = function () {
        obj = this;
        ymaps.ready(function () { //после загрузки АПИ 
            obj.loaded = true;        //ставим флаг что апи загружено
            obj.__initAfterLoad();   //выполняем ранее запошенные функции
        });
    };

    //выполнение функций, запрошенных до загрузки яндекс-апи
    this.__initAfterLoad = function () {
        while (tempEl = this.tempcontainer.shift()) {//извлекаем функции из временного массива
            if (typeof (tempEl) == 'function')
                tempEl();//если там оказалась действиельно функция - выполняем

        }
    }

    /*постановщик очереди
     * принимаемый параметр - функция, которая будет выполнена либо поставлена в очередь
     */
    this.__exec = function (func) {
        if (this.loaded)		  // проверяем загружена ли АПИ
            func();			  //если загружена, выполняем требуемую функцию
        else
            this.tempcontainer.push(func);// если нет - ставим в очередь
    }




    /*************  тела функций *************/

    /*добавление карты
     *принимаемый параметр: ID блока в котором будет прорисована карта. string 
     */
    this.__addMap = function (containerId, options) {
        if (this.maps[containerId] == undefined) {
            this.maps[containerId] = new map(containerId, options);

        }

    }



    /*установка объявления на карты
     * принимаемый параметр: JSON-объект
     * mapid - необязательный параметр, указфывает ID карты к которой применяется функция,
     * если отсутствует то функция применяется ко всем имеющимся
     */
    this.__setMark = function (params, mapid) {
        if (this.maps[mapid] != undefined && this.maps[mapid].canmark) {
            this.maps[mapid].geoObjects.add(new Placemark(params));
        } else {
            for (var prop  in this.maps) { //перебираем карты
                if (this.maps[prop].canmark)
                    this.maps[prop].geoObjects.add(new Placemark(params));
            }
        }
        return this;
    }

    this.__setRouter = function (params, mapid, index) {
        var route;
        index = typeof index !== 'undefined' ? index : 1111;
        if (this.maps[mapid] != undefined) {
            var map = this.maps[mapid];

            ymaps.route([params.coords[1], params.coords[0]], {
                mapStateAutoApply: params.mapStateAutoApply
            }).then(function (router) {

                //route && map.geoObjects.remove(98);
                route = router;
                route.getPaths().options.set({
                    // в балуне выводим только информацию о времени движения с учетом пробок
                    balloonContentBodyLayout: ymaps.templateLayoutFactory.createClass('$[properties.humanJamsTime]'),
                    // можно выставить настройки графики маршруту
                    //strokeColor: '0000ffff',
                    strokeColor: params.color,
                    opacity: params.opacity
                });

                //Находим ИНДЕК, если нашели удаляем.
                if (map.geoObjects.get(index)) {
                    map.geoObjects.remove(index);
                    map.geoObjects.set(index, route);
                }

                // добавляем маршрут на карту
                map.geoObjects.add(route, index);



                var points = route.getWayPoints(),
                        lastPoint = points.getLength() - 1;
                // Задаем стиль метки - иконки будут красного цвета, и
                // их изображения будут растягиваться под контент.
                points.options.set('preset', params.preset);
                // Задаем контент меток в начальной и конечной точках.
                points.get(0).properties.set('iconContent', params.start_icon_content);
                points.get(0).properties.set('balloonContent', params.start_balloon_content_body);
                points.get(lastPoint).properties.set('balloonContent', params.end_balloon_content_body);
                points.get(lastPoint).properties.set('iconContent', params.end_icon_content);





            }, function (error) {
                alert("Возникла ошибка: " + error.message);
            });

        } else {
            for (var prop  in this.maps) { //перебираем карты
                console.log(params);
            }
        }
        return this;
    }

    this.__setMarkCoords = function (mapid) {
        var myPlacemark;
        if (this.maps[mapid] != undefined && this.maps[mapid].canmark) {
            //this.maps[mapid].geoObjects.add(new Placemark(params));
            var that = this;
            var map = this.maps[mapid];

            map.events.add('click', function (e) {
                var coords = e.get('coords');
                var coordy = coords[0].toPrecision(6);
                var coordx = coords[1].toPrecision(6);
                var options = {
                    coordy: coordy,
                    coordx: coordx,
                    properties: {},
                    options: {
                        preset: 'islands#violetStretchyIcon',
                        draggable: false
                    }
                };
                // Если метка уже создана – просто передвигаем ее
                if (myPlacemark) {
                    myPlacemark.geometry.setCoordinates(coords);
                }
                // Если нет – создаем.
                else {
                    myPlacemark = new Placemark(options);
                    map.geoObjects.add(myPlacemark);
                    // Слушаем событие окончания перетаскивания на метке.
                    //  myPlacemark.events.add('dragend', function () {
                    //      getAddress(myPlacemark.geometry.getCoordinates(),map);
                    //  });
                }
                var install_coord_button = new ymaps.control.Button({
                    data: {
                        content: '<i class="flaticon-location" style="font-size:20px;"></i> Установить координаты',
                        title: 'das'
                    },
                    options: {
                        maxWidth: [28, 150, 200],
                        selectOnClick: false
                    }
                });

                var install_balloon_button = new ymaps.control.Button({
                    data: {
                        content: '<i class="flaticon-map-2" style="font-size:20px;"></i> Установить адрес балуна',
                        title: 'das'
                    },
                    options: {
                        maxWidth: [28, 150, 230],
                        selectOnClick: false
                    }
                });

                map.controls.add(install_coord_button, {
                    'float': "left",
                    position: {
                        top: 10,
                        left: 10
                    }
                });
                map.controls.add(install_balloon_button, {
                    'float': "left",
                    position: {
                        top: 10,
                        left: 210
                    }
                });
                install_coord_button.events.add('click', function (e) {
                    setCoords(coordx, coordy);
                });


                myPlacemark.properties.set('iconContent', 'поиск...');
                ymaps.geocode(coords).then(function (res) {
                    var firstGeoObject = res.geoObjects.get(0);
                    //console.log(firstGeoObject.properties.get('description'));
                    myPlacemark.properties
                            .set({
                                iconContent: firstGeoObject.properties.get('name'),
                                balloonContent: firstGeoObject.properties.get('text')
                            });
                    install_balloon_button.events.add('click', function (e) {
                        setContentBalloon(firstGeoObject.properties.get('text'));
                    });
                });



            });

        } else {
            for (var prop  in this.maps) { //перебираем карты
                if (this.maps[prop].canmark)
                    this.maps[prop].geoObjects.add(new Placemark(params));
            }
        }
        return this;
    }

    /*
     * установка массива меток на карту: полуаем JSON  массив объекта.
     */
    this.__setMarks = function (params, mapid) {
        if (mapid != undefined) {
            if (this.maps[mapid] != undefined && this.maps[mapid].canmark) {
                for (var i = 0; i < params.length; i++) {
                    var geoObject = new Placemark(params[i]);
                    this.maps[mapid].geoObjects.add(geoObject);
                }
            }
        } else
            for (var prop  in this.maps) { //перебираем карты
                for (var i = 0; i < params.length; i++) {
                    if (this.maps[prop].canmark)
                        this.maps[prop].geoObjects.add(new Placemark(params[i]));
                }
            }
        return this;
    }


    /* Границы области показа
     * принимаемый параметр: двумерный массив
     * [[min_y,min_x],[max_y,max_x]]
     * где 
     * min_y, min_x широта и долгота левого нижнего угла области показа, float
     * max_y, max_x широта и долгота правого верхнего угла области показа, float
     *mapid - необязательный параметр, указфывает ID карты к которой применяется функция,
     *если отсутствует то функция применяется ко всем имеющимся
     */
    this.__setBounds = function (bounds, mapid) {
        if (mapid != undefined) {
            if (this.maps[mapid] != undefined) {
                this.maps[mapid].setBounds(bounds);
            }
        } else
            for (var prop in this.maps) { //перебираем карты
                this.maps[prop].setBounds(bounds);
            }
        return this;
    }

    /*обновление карты
     *mapid - необязательный параметр, указфывает ID карты к которой применяется функция,
     *если отсутствует то функция применяется ко всем имеющимся
     */
    this.__redraw = function (mapid) {
        if (mapid != undefined) {
            if (this.maps[mapid] != undefined) {
                this.maps[mapid].container.fitToViewport();
            }
        } else
            for (var prop in this.maps) { //перебираем карты
                this.maps[prop].container.fitToViewport();
            }
        return this;
    }



    this.__setCenterMap = function (coords, mapid) {
        if (mapid != undefined) {
            if (this.maps[mapid] != undefined) {
                this.maps[mapid].setCenter(coords);
            }
        } else
            for (var prop in this.maps) { //перебираем карты
                this.maps[prop].setCenter(coords);
            }
        return this;
    }


    this.__setZoomMap = function (zoom, mapid) {
        if (mapid != undefined) {
            if (this.maps[mapid] != undefined) {
                this.maps[mapid].setZoom(zoom, {});
            }
        } else
            for (var prop in this.maps) { //перебираем карты
                this.maps[prop].setZoom(zoom);
            }
        return this;
    }

    /*установка марки и центрирование карты по марке
     *марка не содержит полноценного балуна, как при setMark(),
     * принимаемый параметр: JSON объект
     *mapid - необязательный параметр, указфывает ID карты к которой применяется функция,
     *если отсутствует то функция применяется ко всем имеющимся
     */
    this.__setCenteredMark = function (mark, mapid) {
        if (mapid != undefined) {
            if (this.maps[mapid] != undefined && this.maps[mapid].canmark) {
                //для этой марки вызываем другую функуию создания метки
                this.maps[mapid].geoObjects.add(new Placemark(mark));
                this.maps[mapid].setCenter([mark.geoY, mark.geoX], 10, {
                    checkZoomRange: true
                });

            }
        } else
            for (var prop  in this.maps) { //перебираем карты
                if (this.maps[prop].canmark) {
                    //для этой марки вызываем другую функуию создания метки
                    this.maps[prop].geoObjects.add(new Placemark(mark));
                    this.maps[prop].setCenter([mark.geoY, mark.geoX], 10, {
                        checkZoomRange: true
                    });
                }
            }
        return this;
    }



    /*************  интерфейсы функций *************/

    //добавление карты
    this.addMap = function (containerId, options) {
        obj = this;
        this.__exec(function () {
            obj.__addMap(containerId, options);

            if (options.auto_show_routers) {
                $.each(options.routes, function (key, params) {
                    obj.__setRouter(params, containerId, key);

                });
            }
        });
        return this;
    };


    //установка объявления на карты
    this.setMark = function (params, mapid) {
        obj = this;
        this.__exec(function () {
            obj.__setMark(params, mapid)
        });
        return this;
    }


    this.setRouter = function (params, mapid, index) {
        obj = this;
        this.__exec(function () {
            obj.__setRouter(params, mapid, index)
        });
        return this;
    }


    //установка объявления на карты
    this.setMarkCoords = function (mapid) {
        obj = this;
        this.__exec(function () {
            obj.__setMarkCoords(mapid)
        });
        return this;
    }
    //установка объявлений на карты (получаемый массив JSON  объектов)
    this.setMarks = function (params, mapid) {
        obj = this;
        this.__exec(function () {
            obj.__setMarks(params, mapid)
        });
        return this;
    }
    /* уставливаем центр карты по кординатом*/
    this.setZoomMap = function (zoom, mapid) {
        obj = this;
        this.__exec(function () {
            obj.__setZoomMap(zoom, mapid)
        });
        return this;
    }

    this.setCenterMap = function (coords, mapid) {
        obj = this;
        this.__exec(function () {
            obj.__setCenterMap(coords, mapid)
        });
        return this;
    }

    //Границы области показа
    this.setBounds = function (bounds, mapid) {
        obj = this;
        this.__exec(function () {
            obj.__setBounds(bounds, mapid)
        });
        return this;
    }

    //обновление карты
    this.redraw = function (mapid) {
        obj = this;
        this.__exec(function () {
            obj.__redraw(mapid)
        });
        return this;
    }

    //установка марки и центрирование карты по марке
    this.setCenteredMark = function (mark, mapid) {
        obj = this;
        this.__exec(function () {
            obj.__setCenteredMark(mark, mapid)
        });
        return this;
    }


    //проверка существования карты
    this.hasMap = function (mapid) {
        result = false;
        if (this.maps[mapid] != undefined)
            result = true;
        return result;
    }

    /*************  вызов конструктора и возврат объекта *************/
    this.__constructor();//вызов конструктора
    return this;
}



api = new mapsApi();