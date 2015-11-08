<?php

class BannedIPModel extends ActiveRecord {

    const MODULE_ID = 'core';

    public function getForm() {
        Yii::app()->controller->widget('ext.tinymce.TinymceWidget');
        return new CMSForm(array('id' => __CLASS__,
                    'showErrorSummary' => true,
                    'attributes' => array(
                        'class' => 'form-horizontal'
                    ),
                    'elements' => array(
                        'ip_address' => array(
                            'type' => 'text',
                            'hint' => $this->t('HINT_IP_ADDRESS')
                        ),
                        'reason' => array('type' => 'textarea', 'class' => 'editor'),
                        'time' => array(
                            'type' => 'dropdownlist',
                            'items' => self::bannedTime(),
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

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{banned_ip}}';
    }

    public function rules() {
        return array(
            array('ip_address, time', 'required'),
            array('user_id', 'numerical', 'integerOnly' => true),
            array('ip_address, reason', 'type', 'type' => 'string'),
            array('ip_address', 'length', 'max' => 50),
            array('ip_address, time, date_create', 'safe', 'on' => 'search'),
        );
    }

    public function beforeSave() {
        $this->timetime = time() + $this->time;
        return parent::beforeSave();
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('reason', $this->reason, true);
        $criteria->compare('ip_address', $this->ip_address, true);
        $criteria->compare('date_create', $this->date_create, true);
        $criteria->compare('time', $this->time, true);
        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public static function bannedTime() {
        return array(
            3600 => 'На час',
            86400 => 'На день',
            604800 => 'На неделю',
            2628000 => 'На месяц',
            15768000 => 'На пол года',
            31536000 => 'На год',
            0 => 'На всегда',
        );
    }

    public function getBanTime($t) {
        $times = self::bannedTime();
        return $times[$t];
    }

}