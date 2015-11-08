<?php

class WidgetsModel extends ActiveRecord {

    const MODULE_ID = 'core';

    public function getForm() {
        return new CMSForm(array(
                    'id' => __CLASS__,
                    'showErrorSummary' => false,
                    'elements' => array(
                        'name' => array('type' => 'text'),
                        'alias_wgt' => array('type' => 'text'),
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

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{widgets}}';
    }

    public function rules() {
        return array(
            array('name, alias_wgt', 'required'),
            array('name, alias_wgt', 'safe', 'on' => 'search'),
        );
    }
    public function relations() {
        return array(
            'is' => array(self::HAS_MANY, 'DekstopWidgets', 'widget_id'),
        );
    }
    public function scopes() {
        return array(
            'backend' => array(
                'condition' => 'type="admin"',
            ),

        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('alias_wgt', $this->alias_wgt, true);
        $criteria->compare('name', $this->name, true);
        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}