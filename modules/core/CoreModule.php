<?php

class CoreModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
        ));
    }

    public static function getAdminMenu() {
        $c = Yii::app()->controller->id;

        return array(
            'system' => array(
                //  'visible'=>  Yii::app()->user->isSuperuser,
                'items' => array(
                    array(
                        'label' => Yii::t('app', 'LANGUAGES'),
                        'url' => Yii::app()->createUrl('core/admin/languages'),
                        'icon' => 'flaticon-lang',
                        'active' => ($c == 'admin/languages') ? true : false,
                        'visible' => true,
                    ),
                    array(
                        'label' => Yii::t('app', 'SETTINGS'),
                        'url' => Yii::app()->createUrl('core/admin/settings'),
                        'icon' => 'flaticon-settings',
                        'active' => ($c == 'admin/settings') ? true : false,
                        'visible' => true,
                    ),
                    array(
                        'label' => Yii::t('app', 'CATEGORIES'),
                        'url' => Yii::app()->createUrl('core/admin/categories'),
                        'icon' => 'flaticon-folder-open',
                        'active' => ($c == 'admin/categories') ? true : false,
                        'visible' => true
                    ),
                    array(
                        'label' => Yii::t('app', 'ENGINE_MENU'),
                        'url' => Yii::app()->createUrl('core/admin/menu'),
                        'icon' => 'flaticon-menu',
                        'active' => ($c == 'admin/menu') ? true : false,
                        'visible' => true
                    ),
                    array(
                        'label' => Yii::t('app', 'WIDGETS'),
                        'url' => Yii::app()->createUrl('core/admin/widgets'),
                        'icon' => 'icon-compass',
                        'active' => ($c == 'admin/widgets') ? true : false,
                        'visible' => false
                    ),
                    array(
                        'label' => Yii::t('app', 'BLOCKS'),
                        'url' => Yii::app()->createUrl('core/admin/blocks'),
                        'icon' => 'flaticon-cubes',
                        'active' => ($c == 'admin/blocks') ? true : false,
                        'visible' => true
                    ),
                    array(
                        'label' => Yii::t('app', 'TEMPLATE'),
                        'url' => Yii::app()->createUrl('core/admin/template'),
                        'active' => ($c == 'admin/template') ? true : false,
                        'icon' => 'flaticon-brush',
                    ),
                    array(
                        'label' => Yii::t('app', 'DATABASE'),
                        'url' => Yii::app()->createUrl('core/admin/database'),
                        'icon' => 'flaticon-database',
                        'active' => ($c == 'admin/database') ? true : false,
                        'visible' => true
                    ),
                    array(
                        'label' => Yii::t('app', 'SECURITY'),
                        'url' => Yii::app()->createUrl('core/admin/security'),
                        'icon' => 'flaticon-security',
                        'active' => ($c == 'admin/security') ? true : false,
                        'visible' => true
                    ),
                    array(
                        'label' => Yii::t('app', 'SVC'),
                        'url' => Yii::app()->createUrl('core/admin/service'),
                        'icon' => 'flaticon-office',
                        'active' => ($c == 'admin/service') ? true : false,
                        'visible' => true
                    ),
                ),
            )
        );
    }

    public function getAdminSidebarMenu() {
        return $this->adminMenu['system']['items'];
    }

    public static function getInfo() {
        return array(
            'name' => Yii::t('app', 'SYSTEM'),
            'author' => 'andrew.panix@gmail.com',
            'version' => 1.0,
            'icon' => 'icon-wrench',
            'description' => Yii::t('app', 'SYSTEM'),
            'url' => '',
        );
    }

}