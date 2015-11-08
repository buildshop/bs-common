<?php

class TurbosmsSettingsForm extends SMSFormModel {

    public $login;
    public $password;
    public $sender;

    public function rules() {
        return array(
            array('login, password, sender', 'type')
        );
    }

    public function getFormConfigArray() {
        return array(
            'type' => 'form',
            'elements' => array(
                'login' => array(
                    'label' => 'Логин шлюза',
                    'type' => 'text',
                ),
                'password' => array(
                    'label' => 'Параль шлюза',
                    'type' => 'text',
                ),
                'sender' => array(
                    'label' => 'Подпись',
                    'type' => 'text',
                    'hint' => 'Необходимо добавить подпись в Вашем аккауте на turbosms.ua и дождатся модерации.'
                ),
            ),
           // 'buttons' => $this->getButtons()
        );
    }

}
