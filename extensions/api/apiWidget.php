<?php

class apiWidget extends CWidget {

    public $api = array();

    public function init() {
        $defaultOptions = array(
            'license' => Yii::app()->settings->get('core','license_key'),
            'engine_v' => Yii::app()->version,
            'lang' => Yii::app()->language,
        );




        $param = array();
        $t=array();
        foreach ($this->api as $key=>$name) {
            $t=array(
                'widget' => $name,
                'action' => $name,
            );
            $param[$key]=CMap::mergeArray($t, $defaultOptions);
        }


        Yii::app()->getClientScript()->registerScript('api', "
params = window.params || [];
var objs = " . CJSON::encode($param) . ";
    $.each(objs,function(i,row){
    console.log(row);
        params.push(row);

    });
//params.push(" . CJSON::encode(CMap::mergeArray($param, $defaultOptions)) . ");


(function() {
    var mc = document.createElement('script');
    mc.type = 'text/javascript';
    mc.async = true;
    mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cms.corner.com.ua/api.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(mc, s.nextSibling);
})();

", CClientScript::POS_BEGIN);
    }

}