<?php

class SMSFormModel extends CModel {

    public function attributeNames() {
        return array();
    }

    public function getSettings($serviceName) {
        return Yii::app()->settings->get($serviceName);
    }

    public function getConfigurationFormHtml($serviceName) {
        Yii::import('app.sms_settings.*');
        $this->attributes = $this->getSettings($serviceName);
        $form = new SMSForm($this->getFormConfigArray(), $this);
        return $form;
    }

    public function setSettings($serviceName, $data) {
        Yii::app()->settings->set($serviceName, $data[get_class($this)]);
    }

    protected function getButtons() {
        return array(
            'submit' => array(
                'type' => 'submit',
                'class' => 'btn btn-success',
                'label' => Yii::t('app', 'SAVE')
            )
        );
    }

}