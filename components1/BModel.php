<?php

class BModel extends CModel {

    public function attributeNames() {
        return array();
    }

    public function getSettings($paymentMethodId) {
        return Yii::app()->settings->get($paymentMethodId);
    }

    public function getConfigurationFormHtml($paymentMethodId) {
        Yii::import('app.blocks_settings.*');
        $this->attributes = $this->getSettings($paymentMethodId);
        $form = new BlockForm($this->getFormConfigArray(), $this);
        return $form;
    }

    public function saveSettings($paymentMethodId, $postData) {
        $this->setSettings($paymentMethodId, $postData[get_class($this)]);
    }

    public function setSettings($paymentMethodId, $data) {
        Yii::app()->settings->set($paymentMethodId, $data);
    }

}