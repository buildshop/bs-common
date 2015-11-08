<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => 'fluid')
));
echo $model->getForm();
Yii::app()->tpl->closeWidget();
$config = Yii::app()->settings->get('contacts');
?>
<?php
$jsonBall = array('markers' => array(
        array(
            'lon' => '46.452570',
            'lat' => '30.731550',
            'address' => 'dasdsadas',
            'cname' => 'CNAME'
        ),
        array(
            'lon' => '46.454130',
            'lat' => '30.730000',
            'address' => 'dasdsadas',
            'cname' => 'CNAME'
        )
    )
);
$offices = ContactsOffice::model()->inMap()->active()->findAll();
$office = array();
foreach ($offices as $val) {

    $office['markers'][] = array(
        'lon' => $val->coordy,
        'lat' => $val->coordx,
        'address' => $val->address,
        'cname' => 'CNAME',

    );
}
?>
<script src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script>
<script type="text/javascript">
    ymaps.ready(init);
    function init () {
        var json = $.parseJSON('<?php echo CJSON::encode($office) ?>');
        var myMap = new ymaps.Map("map", {center: [46.452570,30.731550],zoom: 14});
        myMap.controls.add("zoomControl")
        .add("typeSelector")
        .add("mapTools")
        .add('searchControl');
        
        myMap.events.add('click', function (e) {
            if (!myMap.balloon.isOpen()) {
                var coords = e.get('coordPosition');
                myMap.balloon.open(coords, {
                    contentBody:'<a href="javascript:setCoords('+ coords[0].toPrecision(6) + ','+coords[1].toPrecision(6)+');"><u>Установить кординаты на центр карты?</u></a><p>Координаты: ' + [
                        coords[0].toPrecision(6),
                        coords[1].toPrecision(6)
                    ].join(', ') + '</p>'
                });
            }
            else {
                myMap.balloon.close();
            }
        });
        myGeoObjects = [];
        console.log(json.markers);
        if(json.markers !== undefined){
            for (var i = 0; i < json.markers.length; i++) {

                myPlacemark = new ymaps.Placemark([json.markers[i].lon, json.markers[i].lat], {
                    balloonContentHeader: '<div style="color:#ff0303;font-weight:bold">'+json.markers[i].cname+'</div>',
                    balloonContentBody: '<strong>Адрес:</strong> '+json.markers[i].address+'<br>'
                });             
                myGeoObjects.push(myPlacemark);
            }
        }
        var clusterer = new ymaps.Clusterer();
        clusterer.add(myGeoObjects);
        myMap.geoObjects.add(clusterer);

    }
    function setCoords(coordY,coordX){
        $('input#ContactsOffice_coordx').val(coordX);
        $('input#ContactsOffice_coordy').val(coordY);
        $.jGrowl('Кординаты устрановны.')
    }

</script>

<?php
Yii::app()->tpl->openWidget(array(
    'title' => 'Карта',
));
?>

<div id="map" style="margin:0 auto;width:100%;height:<?php echo $config['yandex_map_height'] ?>px;"></div>
<?php Yii::app()->tpl->closeWidget(); ?>
