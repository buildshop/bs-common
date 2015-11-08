<?php

class UserBlockSettingsForm extends BModel {

    public $show_online;

    public function rules() {
        return array(
            array('show_online', 'type')
        );
    }

    public function getFormConfigArray() {
        return array(
            'type' => 'form',
            'elements' => array(
                'htmlstyle' => array(
                    'label' => Yii::t('NewsLatastSettingsForm', 'Показвать кто онлайн'),
                    'type' => 'text',
                ),
            )
        );
    }

}
