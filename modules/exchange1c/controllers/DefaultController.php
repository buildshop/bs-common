<?php
Yii::import('mod.shop.ShopModule');
class DefaultController extends Controller {

    public function actionIndex() {
        $request = Yii::app()->request;
        if ($request->getQuery('password') != Yii::app()->settings->get('exchange1c', 'password'))
            exit('ERR_WRONG_PASS');

        if ($request->userHostAddress != Yii::app()->settings->get('exchange1c', 'ip'))
            exit('ERR_WRONG_IP');

        if ($request->getQuery('type') && $request->getQuery('mode'))
            C1ProductsImport::processRequest($request->getQuery('type'), $request->getQuery('mode'));
    }

}
