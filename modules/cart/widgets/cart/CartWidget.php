<?php

Yii::import('mod.shop.models.ShopProduct');

class CartWidget extends Widget {

    public $registerFile = array(
            // 'cartWidget.css',
            //'cartWidget.js',
    );

    public function init() {
        $this->assetsPath = dirname(__FILE__) . '/assets';
        parent::init();
    }

    public function run() {
        $cart = Yii::app()->cart;
        $currency = Yii::app()->currency->active;
        $items = $cart->getDataWithModels();
        $total = ShopProduct::formatPrice(Yii::app()->currency->convert($cart->getTotalPrice()));


        $this->render($this->skin, array(
            'count' => $cart->countItems(),
            'currency' => $currency,
            'total' => $total,
            'items' => $items
        ));
    }

}
