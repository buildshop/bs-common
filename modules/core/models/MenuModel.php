<?php

class MenuModel extends ActiveRecord {

    const MODULE_ID = 'core';

    public function getForm() {
        return new CMSForm(array('id' => __CLASS__,
                    'showErrorSummary' => false,
                    'attributes' => array(
                        'class' => 'form-horizontal'
                    ),
                    'elements' => array(
                        'label' => array('type' => 'text'),
                        'url' => array('type' => 'text'),
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
        return '{{menu}}';
    }

    public function rules() {
        return array(
            array('label, url', 'required'),
            array('switch, ordern', 'numerical', 'integerOnly' => true),
            // The following rule is used by search()
            array('label, url', 'safe', 'on' => 'search'),
        );
    }

    public function scopes() {
        return array(
            'enabled' => array(
                'condition' => '`t`.`switch`=1'
            ),
        );
    }

    public function defaultScope() {
        return array(
            'order' => '`t`.`ordern` DESC'
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'label' => Yii::t('core', 'NAME'),
            'url' => Yii::t('core', 'Ссылка'),
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('label', $this->label, true);
        $criteria->compare('url', $this->url, true);


        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}