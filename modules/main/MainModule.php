<?php

class MainModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*'
        ));
    }


    public function getRules() {
        return array(
            'layout/<layout:(demo-blocks-layout|ui)>' => 'main/index/test',
        );
    }
    public static function getInfo() {
        return array(
            'name' => 'Главная страница',
            'author' => 'andrew.panix@gmail.com',
            'version' => '0.1',
            'icon' => 'icon-home',
            'url' => Yii::app()->createUrl('/main/admin/default/index'),
            'description' => 'Модуль главной страницы сайта.',
        );
    }



}
