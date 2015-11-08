<?php

class MapWidget extends CWidget {

    public $option = array();
    protected $data = array();
    protected $defaultOptions = array();

    public function init() {
        parent::init();
        $config = Yii::app()->settings->get('contacts');
        $centerMap = explode(',', $config['yandex_map_center']);
        $this->defaultOptions = array(
            'zoomControl' => true,
            'zoomControlOpt' => array(
                'left' => $config['yandex_map_zoomControl_left'],
                'top' => $config['yandex_map_zoomControl_top']
            ),
            'searchControl' => false,
            'mapTools' => true,
            'mapToolsOpt' => array(
                'left' => $config['yandex_map_mapTools_left'],
                'top' => $config['yandex_map_mapTools_top']
            ),
            'typeSelector' => true,
            'typeSelectorOpt' => array(
                'right' => $config['yandex_map_typeSelector_right'],
                'top' => $config['yandex_map_typeSelector_top']
            ),
            'center' => array(
                $centerMap[0],
                $centerMap[1]
            ),
            'height' => '990px',
            'width' => '100%',
            'zoom' => 14,
            'package' => 'package.full',
            'lang' => 'ru-RU',
            'version' => '2.0'
        );


        $offices = ContactsOffice::model()->inMap()->active()->with('manager')->findAll();
        $office = array();
        foreach ($offices as $val) {
            $office['markers'][] = array(
                'lon' => $val->coordy,
                'lat' => $val->coordx,
                'address' => $val->address,
            );
        }
        $this->data = $office;
        $this->registerClientScript();
    }

    public function run() {
        $options = array_merge($this->defaultOptions, $this->option);
        echo Html::tag('div', array('id' => 'map', 'style' => 'width:' . $options['width'] . ';height:' . $options['height'] . ''));
        echo Html::closeTag('div');
    }

    protected function registerClientScript() {
        $options = array_merge($this->defaultOptions, $this->option);
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile('http://api-maps.yandex.ru/' . $options['version'] . '/?load=' . $options['package'] . '&lang=' . $options['lang'] . '', CClientScript::POS_HEAD);
        $cs->registerScript('yandexmap', "
                 
    ymaps.ready(init);
    function init () {
        var option = $.parseJSON('" . CJSON::encode($options) . "');
            console.log(option);
        var json = $.parseJSON('" . CJSON::encode($this->data) . "');
        var myMap = new ymaps.Map('map', {center: [option.center[0],option.center[1]],zoom: option.zoom});
        if(option.zoomControl) myMap.controls.add('zoomControl', {left: option.zoomControlOpt.left, top: option.zoomControlOpt.top});
        if(option.searchControl) myMap.controls.add('searchControl');
        if(option.typeSelector) myMap.controls.add('typeSelector', {right: option.typeSelectorOpt.right, top: option.typeSelectorOpt.top});
        if(option.mapTools) myMap.controls.add('mapTools', {left: option.mapToolsOpt.left, top: option.mapToolsOpt.top});
console.log(json);
        var managersHtml='';
        myGeoObjects = [];
        if(json.markers){
            for (var i = 0; i < json.markers.length; i++) {
                myPlacemark = new ymaps.Placemark([json.markers[i].lon, json.markers[i].lat], {
                    balloonContentHeader: '<div style=\"color:#ff0303;font-weight:bold\"></div>',
                    balloonContentBody: '<strong>Адрес:</strong> '+json.markers[i].address+'<br>'+managersHtml
                });             
                myGeoObjects.push(myPlacemark);
            }
            }
        var clusterer = new ymaps.Clusterer();
        clusterer.add(myGeoObjects);
        myMap.geoObjects.add(clusterer);

    }

", CClientScript::POS_BEGIN);
    }

}
