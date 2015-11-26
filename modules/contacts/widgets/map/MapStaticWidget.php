<?php

Yii::import('mod.contacts.widgets.map.CMapWidget');

class MapStaticWidget extends CMapWidget {

    public $pk;

    const MAP_VERION = '2.1';

    public function run() {
        $cs = Yii::app()->clientScript;
        $map = ContactsMaps::model()->findByPk($this->pk);
        if ($map) {


            $this->setId(__CLASS__ . $map->id);

            $options = CMap::mergeArray($this->getOptions($map), $this->options);
            $this->renderMap($this->id, $options);

            $cs->registerScript($this->id, "
    var markers = " . CJSON::encode($this->getMapMarkers($map)) . ";
    var mapID = '" . $this->id . "';
    var mapOptions = " . CJavaScript::encode($options) . ";
    api.addMap(mapID,mapOptions);
    var min_x=999;
    var max_x=0;
    var min_y=999;
    var max_y=0;

   $.each(markers,function(i,marker){
        api.setMark(marker,'#'+mapID);
        if(min_x > marker.coordx) min_x = marker.coordx;
        if(min_y > marker.coordy) min_y = marker.coordy;
        if(max_x < marker.coordx) max_x = marker.coordx;
        if(max_y < marker.coordy) max_y = marker.coordy;
    });
    



    if(markers.length > 1){
        api.setBounds([[min_y,min_x],[max_y,max_x]],mapID);
        api.setZoomMap(mapOptions.zoom,mapID);
    }
    ", CClientScript::POS_READY);
        }
    }

}
