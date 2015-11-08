<?php

class DiscountsModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
        ));
    }

    public function afterInstall() {
        if (Yii::app()->hasModule('shop')) {
            Yii::app()->database->import($this->id);
            return parent::afterInstall();
        } else {
            Yii::app()->controller->addFlashMessage('Ошибка, Модуль интернет-магазин не устрановлен.');
            return false;
        }
    }

    public function afterUninstall() {
        $db = Yii::app()->db;
        $tablesArray = array(
            ShopDiscount::model()->tableName(),
            $db->tablePrefix . 'shop_discount_category',
            $db->tablePrefix . 'shop_discount_manufacturer'
        );
        foreach ($tablesArray as $table) {
            $db->createCommand()->dropTable($table);
        }
        GridColumns::model()->deleteAll("grid_id='shopdiscount-grid'");
        return parent::afterUninstall();
    }

    public static function getAdminMenu() {
        $c = Yii::app()->controller->module->id;
        return array(
            'shop' => array(
                'items' => array(
                    array(
                        'label' => Yii::t('DiscountsModule.default', 'MODULE_NAME'),
                        'url' => Yii::app()->createUrl('/admin/discounts'),
                        'active' => ($c == 'discounts') ? true : false,
                        'icon' => 'flaticon-discount'
                    ),
                ),
            ),
        );
    }

    public function getAdminSidebarMenu() {
        Yii::import('ext.mbmenu.AdminMenu');
        $mod = new AdminMenu;
        $items=$mod->findMenu('shop');
        return $items['items'];
    }

    public static function getInfo() {
        return array(
            'name' => Yii::t('DiscountsModule.default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '1.0',
            'icon' => 'flaticon-discount',
            'description' => Yii::t('DiscountsModule.default', 'MODULE_DESC'),
        );
    }

}
