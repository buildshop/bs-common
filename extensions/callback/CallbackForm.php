<?php

class CallbackForm extends FormModel {

    public $phone;

    public function rules() {
        return array(
            array('phone', 'required'),
            array('phone', 'length', 'max' => 20, 'min' => 7),
            //array('phone', 'length'),
            array(
                'phone',
                'match', 'not' => true, 'pattern' => '/^[-\s0-9-]/i',
                'message' => Yii::t('CallbackWidget.default','CALLBACK_ERR_VALID'),
            ),
        );
    }

    public function attributeLabels() {
        return array(
            'phone' => 'Телефон',
        );
    }

}
