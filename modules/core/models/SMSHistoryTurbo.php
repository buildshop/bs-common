<?php

class SMSHistoryTurbo extends ActiveRecord {

    const MODULE_ID = 'core';


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{sms_history_turbo}}';
    }

    public function rules() {
        return array(
         array('date_sent', 'safe'),
            array('text', 'type', 'type' => 'string'),

        );
    }
/*
    public function attributeLabels() {
        return array(
            'alias_key' => 'Ключ письма',
            'text' => 'Шаблон письма',
            'switch' => 'Активный',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('alias_key', $this->alias_key, true);
        $criteria->compare('text', $this->text, true);
        $criteria->compare('switch', $this->switch);
        
        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }*/

}