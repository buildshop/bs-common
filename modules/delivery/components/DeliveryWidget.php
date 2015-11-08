<?php

class DeliveryWidget extends BaseWidget {
  protected $_assetsUrl = false;
    public $post = array();
    

    public function init() {
        parent::init(__FILE__);
        $this->_assetsUrl = Yii::app()->assetManager->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . '../assets');
        $clientScript = Yii::app()->clientScript;
        $clientScript->registerCssFile($this->_assetsUrl . '/css/widget-delivery.css');
    }

    public function run() {
        Yii::import('application.modules.delivery.models.DeliveryForm');
        $model = new DeliveryForm();
        $this->render('delivery', array('model' => $model));
    }
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'delivery-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }


}

?>
