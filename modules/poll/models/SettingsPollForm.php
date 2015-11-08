<?php

class SettingsPollForm extends FormModel {

    protected $_mid = 'poll';
    public $is_force;
    public $ip_restrict;

    public function init() {
        $this->attributes = Yii::app()->settings->get('poll');
    }

    public static function defaultSettings() {
        return array(
            'is_force' => 1,
            'ip_restrict' => 0
        );
    }
    public function getForm() {
        return new CMSForm(array('id' => __CLASS__,
                    'showErrorSummary' => true,
                    'elements' => array(
                        'is_force' => array('type' => 'checkbox'),
                        'ip_restrict' => array('type' => 'checkbox'),
                    ),
                    'buttons' => array(
                        'submit' => array(
                            'type' => 'submit',
                            'class' => 'buttonS bGreen',
                            'label' => Yii::t('core', 'SAVE')
                        )
                    )
                        ), $this);
    }

    public function rules() {
        return array(
            array('is_force, ip_restrict', 'required'),
        );
    }

    public function save($message = true) {
        Yii::app()->settings->set('poll', $this->attributes);
        parent::save($message);
    }

}
