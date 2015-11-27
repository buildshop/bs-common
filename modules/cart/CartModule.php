<?php

class CartModule extends WebModule {

    public $tpl_keys = array(
        '%ORDER_ID%',
        '%ORDER_KEY%',
        '%ORDER_DELIVERY_NAME%',
        '%ORDER_PAYMENT_NAME%',
        '%TOTAL_PRICE%',
        '%USER_NAME%',
        '%USER_PHONE%',
        '%USER_EMAIL%',
        '%USER_ADDRESS%',
        '%USER_COMMENT%',
        '%CURRENT_CURRENCY%',
        '%FOR_PAYMENY%',
        '%LIST%',
        '%LINK_TO_ORDER%',
    );

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
            $this->id . '.components.payment.*'
        ));
        if (!Yii::app()->settings->get('cart')) {
            Yii::app()->settings->set('cart', SettingsCartForm::defaultSettings());
        }
        // self::registerAssets();
    }

    public function afterInstall() {
        if (Yii::app()->hasModule('shop')) {
            Yii::app()->settings->set($this->id, SettingsCartForm::defaultSettings());
            Yii::app()->database->import($this->id);
            // Yii::app()->intallComponent('cart', 'mod.cart.components.Cart');
            return parent::afterInstall();
        } else {
            Yii::app()->controller->addFlashMessage('Ошибка, Модуль интернет-магазин не устрановлен.');
            return false;
        }
    }

    public function afterUninstall() {
        Yii::app()->settings->clear($this->id);
        //Yii::app()->unintallComponent('cart');
        $db = Yii::app()->db;
        $tablesArray = array(
            Order::model()->tableName(),
            OrderHistory::model()->tableName(),
            OrderProduct::model()->tableName(),
            OrderStatus::model()->tableName(),
            OrderProductHistroy::model()->tableName(),
            ShopPaymentMethod::model()->tableName(),
            ShopPaymentMethodTranslate::model()->tableName(),
            ShopDeliveryMethod::model()->tableName(),
            ShopDeliveryMethodTranslate::model()->tableName(),
            ShopDeliveryPayment::model()->tableName(),
            ProductNotifications::model()->tableName(),
        );
        foreach ($tablesArray as $table) {
            $db->createCommand()->dropTable($table);
        }
        return parent::afterInstall();
    }

    public static function getAddonsArray() {
        return array(
            'mainButtons' => array(
                array(
                    'label' => Yii::t('CartModule.admin', 'ORDER', 0),
                    'url' => '/admin/cart',
                    'icon' => 'icon-cart-3',
                    'count' => Order::model()->new()->count()
                )
            )
        );
    }

    public function getRules() {
        return array(
            'cart' => 'cart/default/index',
            'cart/add' => 'cart/default/add',
            'cart/remove/<id:(\d+)>' => 'cart/default/remove',
            'cart/clear' => 'cart/default/clear',
            'cart/renderSmallCart' => 'cart/default/renderSmallCart',
            'cart/payment' => 'cart/default/payment',
            'cart/recount' => 'cart/default/recount',
            'cart/view/<secret_key>' => 'cart/default/view',
            'cart/processPayment/*' => 'cart/payment/process',
            'notify' => array('cart/notify/index'),
        );
    }

    public static function getAdminMenu() {
        $c = Yii::app()->controller->id;
        $m = Yii::app()->controller->module->id;
        return array(
            'cart' => array(
                'label' => Yii::t('CartModule.admin', 'ORDER', 0),
               // 'url' => '#', //array('/admin/cart')
                'active' => ($m == 'cart') ? true : false,
                'icon' => 'flaticon-cart',
                'itemOptions' => array('class' => 'circle-orders'),
                'visible' => Yii::app()->user->isSuperuser,
                'items' => array(
                    array(
                        'label' => Yii::t('CartModule.admin', 'STATS'),
                        'url' => Yii::app()->createUrl('/admin/cart/statistics'),
                        'icon' => 'flaticon-stats',
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                    array(
                        'label' => Yii::t('CartModule.admin', 'STATUSES'),
                        'url' => Yii::app()->createUrl('/admin/cart/statuses'),
                        'icon' => 'icon-stats',
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                    /*  array(
                      'label' => Yii::t('CartModule.admin', 'HISTORY'),
                      'url' => Yii::app()->createUrl('/admin/cart/history'),
                      'icon' => 'flaticon-history',
                      'visible' => Yii::app()->user->isSuperuser
                      ), */
                    array(
                        'label' => Yii::t('CartModule.admin', 'DELIVERY'),
                        'url' => Yii::app()->createUrl('cart/admin/delivery'),
                        'active' => ($c == 'admin/delivery') ? true : false,
                        'icon' => 'flaticon-delivery',
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                    array(
                        'label' => Yii::t('CartModule.admin', 'PAYMENTS'),
                        'url' => Yii::app()->createUrl('cart/admin/paymentMethod'),
                        'active' => ($c == 'admin/paymentMethod') ? true : false,
                        'icon' => 'flaticon-purse',
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                    array(
                        'label' => Yii::t('CartModule.admin', 'NOTIFIER'),
                        'url' => Yii::app()->createUrl('cart/admin/notify'),
                        'active' => ($c == 'admin/notify') ? true : false,
                        'icon' => 'flaticon-mail',
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                    array(
                        'label' => Yii::t('app', 'SETTINGS'),
                        'url' => Yii::app()->createUrl('/admin/cart/settings'),
                        'icon' => 'flaticon-settings',
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                )
            ),
            'shop' => array(
                'items' => array(
                    array(
                        'label' => Yii::t('CartModule.admin', 'DELIVERY'),
                        'url' => Yii::app()->createUrl('cart/admin/delivery'),
                        'active' => ($c == 'admin/delivery') ? true : false,
                        'icon' => 'flaticon-delivery',
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                    array(
                        'label' => Yii::t('CartModule.admin', 'PAYMENTS'),
                        'url' => Yii::app()->createUrl('cart/admin/paymentMethod'),
                        'active' => ($c == 'admin/paymentMethod') ? true : false,
                        'icon' => 'flaticon-purse',
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                    array(
                        'label' => Yii::t('CartModule.admin', 'NOTIFIER'),
                        'url' => Yii::app()->createUrl('cart/admin/notify'),
                        'active' => ($c == 'admin/notify') ? true : false,
                        'icon' => 'flaticon-mail',
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                )
            )
        );
    }

    public function getAdminSidebarMenu() {
        Yii::import('ext.mbmenu.AdminMenu');
        $mod = new AdminMenu;
        $items = $mod->findMenu('shop');
        return $items['items'];
    }

    public static function getInfo() {
        return array(
            'name' => Yii::t('CartModule.default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '1.0 PRO',
            'icon' => 'flaticon-cart',
            'description' => Yii::t('CartModule.default', 'MODULE_DESC'),
            'url' => Yii::app()->createUrl('/admin/cart/default/index'),
        );
    }

    public static function registerAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
        $cs = Yii::app()->clientScript;
        if (is_dir($assets)) {
            $cs->registerScriptFile($baseUrl . '/cart.js', CClientScript::POS_HEAD);
            //$cs->registerCssFile($baseUrl . '/style.css');
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

}
