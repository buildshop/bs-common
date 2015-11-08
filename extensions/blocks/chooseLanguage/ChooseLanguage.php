<?php

class ChooseLanguage extends BlockWidget {

    public function getTitle() {
        return 'Выбор языка';
    }

    public function run() {
        $this->render('bootstrap', array('language' => Yii::app()->languageManager));
    }

}