<?php

class SettingsSecurityForm extends FormModel {

    const MODULE_ID = 'core';
    public $backup_db;
    public $backup_time;
    public $backup_time_cache;

    public function getForm() {
        return new CMSForm(array('id' => __CLASS__,
                    'showErrorSummary' => false,
                    'attributes' => array(
                        'class' => 'form-horizontal'
                    ),
                    'elements' => array(
                        'backup_db' => array('type' => 'checkbox'),
                        'backup_time' => array('type' => 'text', 'value' => $this->backup_time / 60),
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
        $this->attributes = Yii::app()->settings->get('security');
        parent::init();
    }

    public function rules() {
        return array(
            array('backup_db, backup_time, backup_time_cache', 'required'),
            array('backup_db, backup_time, backup_time_cache', 'numerical', 'integerOnly' => true),
        );
    }

    public function save($message = true) {
        Yii::app()->settings->set('security', $this->attributes);
        parent::save($message);
    }

}