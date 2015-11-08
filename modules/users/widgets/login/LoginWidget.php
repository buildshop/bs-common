<?php

class LoginWidget extends CWidget {
    public $icon_register;
    public $icon_login;
    public $username;
    public function init() {
        $this->username = !empty(Yii::app()->user->email) ? Yii::app()->user->email : Yii::app()->user->username;
        parent::init();
        $this->registerScripts();
    }

    protected function registerScripts() {

        $assets = Yii::app()->assetManager->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets', false, -1, YII_DEBUG);
        $cs = Yii::app()->clientScript;
        $cs->registerCssFile($assets . '/css/login.css');
        if(Yii::app()->user->isGuest) $cs->registerScriptFile($assets . '/js/login.js?' . time());

    }

    public function run() {
        $this->render('login');
    }

}

?>
