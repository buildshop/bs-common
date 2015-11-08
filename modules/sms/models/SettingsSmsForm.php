<?php

class SettingsSmsForm extends FormModel {

    protected $_mid = 'sms';
    public $service;

    public static function defaultSettings() {
        return array(
            'service' => null,
        );
    }

    public function getForm() {
        return new CMSForm(array(
                    'attributes' => array(
                        'class' => 'form-horizontal',
                        'id' => __CLASS__,
                    ),
                    'showErrorSummary' => true,
                    'elements' => array(
                        'service' => array(
                            'type' => 'dropdownlist',
                            'items' => $this->getServices()
                        ),
                        '<div id="sms_configuration"></div>'
                    ),
                    'buttons' => array(
                        'submit' => array(
                            'type' => 'submit',
                            'class' => 'btn btn-success',
                            'label' => Yii::t('app', 'SAVE')
                        )
                    )
                        ), $this);


    }

    public function init() {
        $this->attributes = Yii::app()->settings->get('sms');
    }

    public function getServices() {
        return array(
            'turbosms.Turbosms' => 'turbosms.ua',
            'clickatell.Clickatell' => 'clickatell.com',
        );
    }

    public function rules() {
        return array(
            array('service', 'type', 'type' => 'string'),
        );
    }

    public function save($message = true) {
        Yii::app()->settings->set('sms', $this->attributes);
        parent::save($message);
    }

}
