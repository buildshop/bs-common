<?php

/**
 * @package components
 * @uses CFormModel
 */
Yii::import('app.traits.ImageUrl');
Yii::import('app.traits.ModelTranslate');

class FormModel extends CFormModel {

    use ImageUrl,
        ModelTranslate;

    protected $_attrLabels = array();

    const MODULE_ID = null;

    public function getModuleId() {
        return static::MODULE_ID;
    }

    public function attributeLabels() {
        $lang = Yii::app()->language;
        $model = get_class($this);
        $filePath = Yii::getPathOfAlias('mod.' . static::MODULE_ID . '.messages.' . $lang) . DS . $model . '.php';
        foreach ($this->attributes as $attr => $val) {
            $this->_attrLabels[$attr] = self::t(strtoupper($attr));
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

    /* public function validate($message = true, $attributes = null, $clearErrors = true) {
      if (parent::validate($attributes, $clearErrors)) {
      return true;
      } else {
      if ($message)
      Yii::app()->user->setFlash('error','eeeeeeee');
      //Yii::app()->controller->setFlashMessage(Yii::t('core', 'ERROR_VALIDATE'));
      return false;
      }
      } */
}