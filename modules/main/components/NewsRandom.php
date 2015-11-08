<?php
Yii::import('zii.widgets.CPortlet');
Yii::import('application.modules.news.models.*');
class NewsRandom extends CPortlet {

    public $count;

    public function init() {
    $assets = Yii::app()->assetManager->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . '../assets');
    $clientScript = Yii::app()->clientScript;
    $clientScript->registerCssFile($assets .'/widget-widget.css');
        parent::init();
    }

    public function renderContent() {

        $model = News::model()->findAll(array('order' => 'rand()', 'limit' =>$this->count));
        $this->render('last', array('model' => $model));
    }

}
