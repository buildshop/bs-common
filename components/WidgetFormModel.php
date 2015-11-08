<?php

class WidgetFormModel extends CModel {

    public function attributeNames() {
        return array();
    }

    public function getSettings($obj) {
        return Yii::app()->settings->get($obj);
    }

    public function getConfigurationFormHtml($obj) {
        Yii::import('app.widget_config.*');
        $className = basename(Yii::getPathOfAlias($obj));
        $this->attributes = $this->getSettings($className);
        $form = new WidgetForm($this->getFormConfigArray(), $this);
        return $form;
    }

    public function saveSettings($obj, $postData) {
        $this->setSettings($obj, $postData[get_class($this)]);
    }

    public function setSettings($obj, $data) {
        $className = basename(Yii::getPathOfAlias($obj));
        $cache = Yii::app()->cache->get(md5(Yii::app()->cache->keyPrefix.$className));
        if (isset($cache)) {
            Yii::app()->cache->delete($className);
        }
        Yii::app()->settings->set($className, $data);
        

        
    }

}