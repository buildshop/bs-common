<?php

/**
 * Модуль стратичных страниц
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.pages
 * @uses BaseModule
 * @link http://cms.corner.com.ua/news/ Пример модуля.
 */
class PagesModule extends WebModule {


    public function init() {
        $this->setImport(array(
            $this->id . '.models.*'
        ));
    }

    public function afterInstall() {
        Yii::app()->database->import($this->id);
        return parent::afterInstall();
    }

    public function afterUninstall() {
        //Удаляем таблицу модуля
        Yii::app()->db->createCommand()->dropTable(Page::model()->tableName());
        Yii::app()->db->createCommand()->dropTable(PageTranslate::model()->tableName());
        return parent::afterUninstall();
    }

    public function getRules() {
        return array(
            'page/<url>' => 'pages/default/index',
        );
    }

    public static function getInfo() {
        return array(
            'name' => Yii::t('PagesModule.default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '1.0',
            'icon' => 'icon-edit',
            'description' => Yii::t('PagesModule.default', 'MODULE_DESC'),
            'url' => Yii::app()->createUrl('/admin/pages/default/index'),
        );
    }

    public static function getAdminMenu() {
        return array(
            'modules' => array(
                'items' => array(
                    array(
                        'label' => Yii::t('PagesModule.default', 'MODULE_NAME'),
                        'url' => array('/admin/pages'),
                        'icon' => 'fa-edit',
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                ),
            ),
        );
    }

    public static  function getAddonsArray() {
        return array(
            'mainButtons' => array(
                array(
                    'label' => Yii::t('PagesModule.default', 'CREATE'),
                    'url' => '/admin/pages/default/create',
                    'icon' => 'fa-edit'
                )
            )
        );
    }

}