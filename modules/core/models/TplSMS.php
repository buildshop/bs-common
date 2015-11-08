<?php

class TplSMS extends ActiveRecord {

    const MODULE_ID = 'core';

    public function getForm() {
        return new CMSForm(array(
                    'attributes' => array(
                        'class' => 'form-horizontal',
                        'id' => __CLASS__,
                    ),
                    'showErrorSummary' => true,
                    'elements' => array(
                        'alias_key' => array(
                            'type' => ($this->isNewRecord) ? 'text' : 'none',
                        ),
                        'text' => array('type' => 'textarea'),
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
        return '{{tpl_sms}}';
    }

    public function rules() {
        return array(
            array('alias_key, text', 'required'),
        );
    }

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
    }

}