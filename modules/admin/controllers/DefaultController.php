<?php

Yii::import('mod.shop.models.orders.*');

class DefaultController extends AdminController {

    private $_items;
    public $topButtons = false;

    public function getAddonsMenu() {
        return array(
            array(
                'label' => Yii::t('core', 'SETTINGS'),
                'url' => 'javascript:void(0)',
                'icon' => 'icon-settings',
                'visible' => true,
                'itemsHtmlOptions' => array('style' => 'width:220px'),
                'items' => array(
                    array(
                        'label' => Yii::t('core', 'DEKSTOP_CREATE'),
                        'url' => $this->createUrl('dekstop/create'),
                        'icon' => 'icon-plus',
                        'visible' => true,
                    ),
                    array(
                        'label' => Yii::t('core', 'DEKSTOP_SETTINGS'),
                        'url' => $this->createUrl('dekstop/update', array('id' => $_GET['d'])),
                        'icon' => 'icon-settings',
                        'visible' => true,
                    ),
                    array(
                        'label' => Yii::t('core', 'DEKSTOP_CREATE_WIDGET'),
                        'url' => $this->createUrl('dekstop/createWidget', array('id' => $_GET['d'])),
                        'linkOptions' => array('id' => 'createWidget'),
                        'icon' => 'icon-plus',
                        'visible' => true,
                    )
                )
            ),
        );
    }

    public function actionIndex() {
        $this->clearCache();
        $this->clearAssets();
        $found = $this->findAddons();
        $items = CMap::mergeArray($this->_items, $found);
        $this->pageName = Yii::t('CoreModule.admin', 'CMS');
        $this->breadcrumbs = array($this->pageName);

        $this->render('index', array(
            //'ordersDataProvider'=>$this->getOrders(),
            'AddonsItems' => $items
        ));
    }

    protected function findAddons() {
        $result = array();
        $modules = Yii::app()->getModules();
        foreach ($modules as $mid=>$module) {
            $class = Yii::app()->getModule($mid);
            if (method_exists($class, 'getAddonsArray')) {
                $arr = $class::getAddonsArray();
                $result = CMap::mergeArray($result, $arr['mainButtons']);
            }
        }
        return $result;
    }

    /**
     * Get latest orders
     *
     * @return ActiveDataProvider
     */
    public function getOrders() {
        Yii::import('mod.cart.models.Order');
        $cr = new CDbCriteria;
        $orders = Order::model()->search();
        $orders->setPagination(array('pageSize' => 10));
        $orders->setCriteria($cr);
        return $orders;
    }

    /**
     * Get orders date_create today
     *
     * @return ActiveDataProvider
     */
    public function getTodayOrders() {
        Yii::import('mod.cart.models.Order');
        $cr = new CDbCriteria;
        $cr->addCondition('date_create >= "' . date('Y-m-d 00:00:00') . '"');
        $dataProvider = Order::model()->search();
        $dataProvider->setCriteria($cr);
        return $dataProvider;
    }

    /**
     * Sum orders total price
     *
     * @return string
     */
    public function getOrdersTotalPrice() {
        $total = 0;
        foreach ($this->getTodayOrders()->getData() as $order)
            $total += $order->full_price;
        return ShopProduct::formatPrice($total);
    }

    public function clearCache() {
        if (isset($_POST['clear_cache'])) {
            $cache = Yii::app()->getComponent('cache');
            if ($cache instanceof CFileCache) {
                FileSystem::fs('protected/runtime/cache', Yii::getPathOfAlias('webroot'))->cleardir();
            } elseif ($cache instanceof CDbCache) {
                //..tuncate db cachex
            }
            $this->setFlashMessage(Yii::t('CoreModule.admin', 'SUCCESS_CLR_CACHE'));
        }
    }

    // Old no used
    public function ___________clearCache() {
        if (isset($_POST['cache_id'])) {
            Yii::app()->cache->delete($_POST['cache_id']);
            $this->setFlashMessage(Yii::t('CoreModule.admin', 'SUCCESS_CLR_CACHE'));
            //$this->refresh();
        }
    }

    public function clearAssets() {
        if (isset($_POST['clear_assets'])) {
            FileSystem::fs('assets', Yii::getPathOfAlias('webroot'))->cleardir();
            $this->setFlashMessage(Yii::t('CoreModule.admin', 'SUCCESS_CLR_ASSETS'));
            //$this->refresh();
        }
    }

}
