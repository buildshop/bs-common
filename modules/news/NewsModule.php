<?php

class NewsModule extends WebModule {

    public $edit_mode = true;
    public $_addonsArray;

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*'
        ));
    }

    public function afterInstall() {
        Yii::app()->settings->set('news', SettingsNewsForm::defaultSettings());
        Yii::app()->database->import($this->id);
        Yii::app()->widgets->set('module', array(
            'alias_wgt' => 'mod.news.blocks.latast.NewsLatastBlock',
            'name' => 'Новости'
        ));
        return parent::afterInstall();
    }

    public function afterUninstall() {
        //Удаляем таблицу модуля
        Yii::app()->settings->clear('news');
        Yii::app()->db->createCommand()->dropTable(News::model()->tableName());
        Yii::app()->db->createCommand()->dropTable(NewsTranslate::model()->tableName());
        return parent::afterUninstall();
    }

    public function getRules() {
        return array(
            'news/' => 'news/default/index',
            'news/<seo_alias>' => 'news/default/view',
            'news/category/<category>' => 'news/default/index',
            'news/default/upload' => 'news/default/upload',
            'news/<tag:.*?>' => 'news/default/index',
        );
    }

    public static function getInfo() {
        return array(
            'name' => Yii::t('NewsModule.default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '1.0',
            'icon' => 'flaticon-newspaper',
            'description' => Yii::t('NewsModule.default', 'MODULE_DESC'),
            'url' => Yii::app()->createUrl('/news/admin/default/index'),
            'homeUrl' => Yii::app()->createUrl('/news/default/index'),
        );
    }

    public static function getAdminMenu() {
        return array(
            'modules' => array(
                'items' => array(
                    array(
                        'label' => Yii::t('NewsModule.default', 'MODULE_NAME'),
                        'url' => array('/admin/news'),
                        'icon' => 'flaticon-newspaper',
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                ),
            ),
        );
    }

    public static function getAddonsArray() {
        return array(
            'mainButtons' => array(array(
                    'label' => Yii::t('NewsModule.default', 'CREATE'),
                    'url' => '/admin/news/default/create',
                    'icon' => 'flaticon-newspaper'
                ))
        );
    }

}
