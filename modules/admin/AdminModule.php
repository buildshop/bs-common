<?php

class AdminModule extends WebModule {

    public static function getInfo() {
        return array(
            'name' => Yii::t('CoreModule.admin', 'CMS'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '1.0',
            'icon' => 'icon-wrench',
            'description' => Yii::t('CoreModule.admin', 'CMS'),
            'url' => '',
        );
    }


}
