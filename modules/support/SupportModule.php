<?php

class SupportModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*',
        ));
    }

    public function afterInstall() {
        return parent::afterInstall();
    }

    public static function getAdminMenu() {
        $c = Yii::app()->controller->module->id;
        return array(
            'support' => array(
                'label' => Yii::t('SupportModule.default', 'MODULE_NAME'),
                'url' => Yii::app()->createUrl('/admin/support'),
                'icon' => 'flaticon-operator-2',
                'active' => ($c == 'support') ? true : false,
            ),
        );
    }

    public static function getInfo() {
        return array(
            'name' => Yii::t('SupportModule.default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '1.0',
            'icon' => 'flaticon-operator-2',
            'description' => Yii::t('SupportModule.default', 'MODULE_DESC'),
            'url' => Yii::app()->createUrl('/admin/support'),
        );
    }

}
