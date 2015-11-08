function setCoords(coords){
    $('input#ConfigContactForm_yandex_map_center').val(coords);

    $.jGrowl('Координаты установлены!');
}
function addMarkerCoords(coords){
    $.ajax({
        type:'POST',
        url:'/admin/contacts/default/createMarker',
        data:{coords:coords},
        success:function(data){
            $.jGrowl('Маркер упешно создан');
        }
    });
}
var init = function (cfgs, core) {
    var cfg = $.parseJSON(cfgs);
    var myMap = new ymaps.Map("map", {
        center: [cfg.yandex_map_center],
        zoom: cfg.yandex_map_zoom
    }),
   
    myPlacemark = new ymaps.Placemark([cfg.yandex_map_center], {
        // Чтобы балун и хинт открывались на метке, необходимо задать ей определенные свойства.
        balloonContentHeader: "<?= Yii::app()->settings->get('core','site_name') ?>",
        balloonContentBody: "<div style='margin:10px 0;'><?php echo $config['yandex_map_balloon_content'] ?></div>",
        //balloonContentFooter: "Подвал",
        hintContent: "<?= Yii::app()->settings->get('core','site_name') ?>"
    });
    myMap.controls
    // Кнопка изменения масштаба.
    .add('searchControl')
    .add('zoomControl',{
        left: cfg.yandex_map_zoomControl_left, 
        top: cfg.yandex_map_zoomControl_top
        })
    .add('typeSelector',{
        right: cfg.yandex_map_typeSelector_right, 
        top: cfg.yandex_map_typeSelector_top
        })
    .add('mapTools',{
        left: cfg.yandex_map_mapTools_left, 
        top: cfg.yandex_map_mapTools_top
        })
    //if (cfg.yandex_map_zoomControl) { 
    //            .add('zoomControl', { left: cfg.yandex_map_zoomControl_left, top: cfg.yandex_map_zoomControl_top})
    // } 
    //if (cfg.yandex_map_typeSelector) {
    //           .add('typeSelector', { right: cfg.yandex_map_typeSelector_right, top: cfg.yandex_map_typeSelector_top})
    //} 
    //if (cfg.yandex_map_mapTools) { 
    //           .add('mapTools', { left: cfg.yandex_map_mapTools_left, top: cfg.yandex_map_mapTools_top})
    //}
    myMap.geoObjects.add(myPlacemark);
    myMap.events.add('click', function (e) {
        if (!myMap.balloon.isOpen()) {
            var coords = e.get('coordPosition');
            myMap.balloon.open(coords, {
                contentBody:'<a href="javascript:void(0)" onClick="setCoords(\''+ [coords[0].toPrecision(6),coords[1].toPrecision(6)].join(', ') + '\');"><u>Установить кординаты на центр карты?</u></a><p>Координаты: ' + [
                coords[0].toPrecision(6),
                coords[1].toPrecision(6)
                ].join(', ') + '</p>'
            });
        }
        else {
            myMap.balloon.close();
        }
    });
}

