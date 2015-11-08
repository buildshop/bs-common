<?php

class BlocksModel extends ActiveRecord {

    protected $_mod = false;
    protected $_allPositions = array();
    const MODULE_ID = 'core';

    public function getPaymentSystemsArray() {
        Yii::import('app.blocks_settings.*');
        $result = array();
        $systems = new BlockSystemManager;
        foreach ($systems->getSystems() as $system) {
            $result[(string) $system->id] = $system->name;
        }
        return $result;
    }

    public function getForm() {
        Yii::app()->controller->widget('ext.tinymce.TinymceWidget');
        return new CMSForm(array(
                    'showErrorSummary' => true,
                    'attributes' => array(
                        'class' => 'form-horizontal',
                        'id' => __CLASS__,
                    ),
                    'elements' => array(
                        'name' => array('type' => 'text'),
                        'content' => array('type' => 'textarea', 'class' => 'editor'),
                        'widget' => array(
                            'type' => 'dropdownlist',
                            'items' => Yii::app()->widgets->getData(),
                            'empty' => Yii::t('app', 'EMPTY_DROPDOWNLIST', 1),
                            'hint' => $this->t('HINT_WIDGET')
                        ),
                        '<div id="payment_configuration"></div>',
                        'modules' => array(
                            'type' => 'checkboxlist',
                            'items' => CMS::getModules()
                        ),
                        'access' => array(
                            'type' => 'dropdownlist',
                            'items' => Yii::app()->access->dataList(),
                            'empty' => Yii::t('app', 'EMPTY_DROPDOWNLIST', 1)
                        ),
                        'position' => array(
                            'type' => 'dropdownlist',
                            'items' => $this->allPositions,
                            'empty' => Yii::t('app', 'EMPTY_DROPDOWNLIST', 1)
                        ),
                        'expire' => array('type' => 'text'),
                        'action' => array(
                            'type' => 'dropdownlist',
                            'empty' => Yii::t('app', 'EMPTY_DROPDOWNLIST', 1),
                            'items' => array('update' => Yii::t('app', 'OFF', 1), 'delete' => Yii::t('app', 'DELETE')),
                            'hint' => $this->t('HINT_ACTION')
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


    public function scopes() {
        return array(
            'enabled' => array(
                'condition' => '`t`.`switch`=1',
                'order' => '`t`.`ordern` DESC'
            )
        );
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{blocks}}';
    }

    public function rules() {
        return array(
            array('name, modules, access, position', 'required'),
            array('switch', 'numerical', 'integerOnly' => true),
            array('content', 'type', 'type' => 'string'),
            array('modules, widget, expire, action', 'length', 'max' => 250),
            array('name, content, position, access, modules', 'safe', 'on' => 'search'),
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('switch', $this->switch);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('position', $this->position, true);
        $criteria->compare('access', $this->access, true);
        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    protected function getAllPositions() {
        return array(
            'left' => $this->t('BLOCK_POS_LEFT'),
            'right' => $this->t('BLOCK_POS_RIGHT'),
            'fly' => $this->t('BLOCK_POS_FLY'),
        );
    }

    public function showPosition($name) {
        return $this->allPositions[$name];
    }

    public function afterFind() {
        if ($this->expire > 0) {
            if ($this->action && $this->expire < time()) {
                if ($this->action == "update") {
                    Yii::app()->db->createCommand()->update('blocks', array('switch' => 0, 'expire' => 0), 'id=:id', array(':id' => (int) $this->id));
                } elseif ($this->action == "delete") {
                    Yii::app()->db->createCommand()->delete('blocks', 'id=:id', array(':id' => (int) $this->id));
                }
            }
        }
        parent::afterFind();
    }

}