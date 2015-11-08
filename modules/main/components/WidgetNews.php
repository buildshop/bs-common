<?php

Yii::import('zii.widgets.CPortlet');
Yii::import('application.modules.news.models.*');

class WidgetNews extends CPortlet {

    public $count;
    public $type = 'rand';
    protected $_assetsUrl = false;

    public function init() {
       $this->_assetsUrl = Yii::app()->assetManager->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . '../assets');
        $clientScript = Yii::app()->clientScript;
        $clientScript->registerCssFile($this->_assetsUrl . '/css/widget-news.css');
        parent::init();
    }

    public function renderContent() {
        if ($this->type == 'rand') {
            $model = News::model()->rand()->switch()->findAll(array('limit' => $this->count));
        } else {
            $model = News::model()->latest()->switch()->findAll(array('limit' => $this->count));
        }
        $this->render('last', array('model' => $model));
    }

}
