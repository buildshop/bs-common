<?php

/**
 * @package components
 * @uses CFormModel
 */
class FormModel extends CFormModel {

    protected $_attrLabels = array();
    protected $_mid;
    public function getModuleId(){
        return $this->_mid;
    }
    public function attributeLabels() {
        $lang = Yii::app()->language;
        $model = get_class($this);
        $filePath = Yii::getPathOfAlias('mod.' . $this->_mid . '.messages.' . $lang) . DS . $model . '.php';
        foreach ($this->attributes as $attr => $val) {
            $this->_attrLabels[$attr] = $this->t(strtoupper($attr));
        }
        if (!file_exists($filePath)) {
            Yii::app()->user->setFlash('warning', 'Форма не может найти файл переводов: <b>' . $filePath . '</b> ');
        }
        return $this->_attrLabels;
    }

    public function save($message = true) {
        if ($message)
           // Yii::app()->controller->setFlashMessage(Yii::t('core', 'SUCCESS_UPDATE'));
            Yii::app()->user->setFlash('success', Yii::t('app', 'SUCCESS_UPDATE'));
    }

    /*public function validate($message = true, $attributes = null, $clearErrors = true) {
        if (parent::validate($attributes, $clearErrors)) {
            return true;
        } else {
            if ($message)
                Yii::app()->user->setFlash('error','eeeeeeee');
                //Yii::app()->controller->setFlashMessage(Yii::t('core', 'ERROR_VALIDATE'));
            return false;
        }
    }*/

    public function t($message, $params = array()) {
        return Yii::t(ucfirst($this->_mid) . 'Module.' . get_class($this), $message, $params);
    }

}