<?php

class SettingsExchange1cForm extends FormModel {

    protected $_mid = 'exchange1c';
    public $ip;
    public $password;

    public function getForm() {
        return new CMSForm(array('id' => __CLASS__,
                    'showErrorSummary' => true,
                    'attributes' => array(
                        'class' => 'form-horizontal'
                    ),
                    'elements' => array(
                        'ip' => array(
                            'type' => 'text',
                            'hint' => 'Укажите IP сервера 1C  с которого разрешено принимать подключения'
                        ),
                        'password' => array(
                            'type' => 'text',
                            'hint' => 'Сcылка импорта: /exchange1c/{password}'
                        ),
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
        $this->attributes = Yii::app()->settings->get('exchange1c');
    }

    public function rules() {
        return array(
            array('ip, password', 'required'),
        );
    }

    public function save($message = true) {
        Yii::app()->settings->set('exchange1c', $this->attributes);
        parent::save($message);
    }

}
