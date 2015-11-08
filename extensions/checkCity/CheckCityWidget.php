<?php

class CheckCityWidget extends CWidget {

    protected $assetsPath;
    protected $assetsUrl;

    public static function actions() {
        return array(
            'action' => 'ext.checkCity.actions.CheckCityAction', // ajax/city.action
        );
    }

    public function init() {
        parent::init();
        if ($this->assetsPath === null) {
            $this->assetsPath = dirname(__FILE__) . DS . 'assets';
        }
        if ($this->assetsUrl === null) {
            $this->assetsUrl = Yii::app()->assetManager->publish($this->assetsPath, false, -1, YII_DEBUG);
        }
        $this->registerClientScript();
    }

    public function run() {
        $this->render($this->skin, array());
    }

    protected function registerClientScript() {
        $cs = Yii::app()->clientScript;
        if (is_dir($this->assetsPath)) {
            $cs->registerCoreScript('cookie');
            $cs->registerScriptFile($this->assetsUrl . '/js/city.js', CClientScript::POS_END);
            
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

}