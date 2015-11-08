<?php

/**
 * used Yii::app()->contact->list return array mangers;
 * used Yii::app()->contact->list[0] return manager row;
 * used Yii::app()->contact->list[0]->email return manager email;
 */
class ContactComponent extends CComponent {

    protected $list;
    protected $_model;

    public function init() {
        Yii::import('mod.contacts.models.*');
        $this->_model = ContactsManagers::model()->findAll();
    }

    public function getList() {
        return $this->_model;
    }

}

?>
