<?php

class ClickatellSettingsForm extends SMSFormModel {

    public $user;
    public $password;
    public $api_id;
    public $api_type;

    public function rules() {
        return array(
            array('user, password, api_id, api_type', 'type')
        );
    }

    public function getFormConfigArray() {
        return array(
            'type' => 'form',
            'elements' => array(
                'user' => array(
                    'label' => 'Логин',
                    'type' => 'text',
                ),
                'password' => array(
                    'label' => 'Параль',
                    'type' => 'text',
                ),
                'api_id' => array(
                    'label' => 'API ID',
                    'type' => 'text',
              
                ),
                'api_type' => array(
                    'label' => 'Тип API',
                    'type' => 'dropdownlist',
                    'items' => array(
                        'HTTP' => 'HTTP',
                        'REST' => 'REST',
                    )
                ),
            ),
                // 'buttons' => $this->getButtons()
        );
    }

}
