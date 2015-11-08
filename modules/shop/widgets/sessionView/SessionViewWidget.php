<?php

/**
 * 
 * @package widgets.modules.shop
 * @uses CWidget
 */
class SessionViewWidget extends CWidget {


    public function run() {
        $session = Yii::app()->session->get('views');
        if (!empty($session)) {
            $list = ShopProduct::model()->findAllByPk(array_values($session));
        } else {
            $list = array();
        }
        $this->render($this->skin, array('list' => $list,'session'=>$session));
    }

}
