<?php

class CurrencyController extends AdminController {

    public function actionIndex() {
        $model = new ShopCurrency('search');

        if (!empty($_GET['ShopCurrency']))
            $model->attributes = $_GET['ShopCurrency'];

        $dataProvider = $model->search();
        $this->pageName = Yii::t('ShopModule.admin', 'CURRENCY');
        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            $this->pageName
        );
        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Create new currency
     */
    public function actionCreate() {
        $this->actionUpdate(true);
    }

    /**
     * Update currency
     * @param bool $new
     * @throws CHttpException
     */
    public function actionUpdate($new = false) {
        if ($new === true) {
            $model = new ShopCurrency;
            $model->unsetAttributes();
        } else {
            $model = ShopCurrency::model()->findByPk($_GET['id']);
        }
        $isNew = $model->isNewRecord;
        if (!$model)
            throw new CHttpException(404, Yii::t('ShopModule.admin', 'NO_FOUND_CURRENCY'));

        $this->pageName = ($isNew) ? $model->t('IS_NEW', 0) : $model->t('IS_NEW', 1);
        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            Yii::t('ShopModule.admin', 'CURRENCY') => array('/admin/shop/currency'),
            $this->pageName
        );
        if (Yii::app()->request->isPostRequest) {
            $rate = $model->rate;
            // $rate_old = $model->rate_old;
            $model->attributes = $_POST['ShopCurrency'];
            if ($model->rate != $rate)
                $model->rate_old = $rate;
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('update', array('model' => $model));
    }

    /*
      public function actionUpdateProducts() {
      $db = Yii::app()->db;
      $response = $db->createCommand()
      ->select('*')
      ->from('{{shop_product}}')
      ->where('currency_id=:curr_id', array(':curr_id' => 1)) //Только мужскую обувь
      ->queryAll();
      $this->render('update_products', array('response' => $response));
      }
     */

    public function actionUpdateProductPrice() {
        $model = ShopProduct::model()->findByPk($_POST['data']['id']);
        if (isset($model)) {
            $model->price = $model->price * $model->currency->rate / $model->currency->rate_old;
            $model->save();
        }
    }

    public function actionUpdateOld($new = false) {
        if ($new === true) {
            $model = new ShopCurrency;
            $model->unsetAttributes();
        } else {
            $model = ShopCurrency::model()->findByPk($_GET['id']);
        }

        if (!$model)
            throw new CHttpException(404, Yii::t('ShopModule.admin', 'NO_FOUND_CURRENCY'));

        $form = new CMSForm($model->config, $model);
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['ShopCurrency'];
            if ($model->validate()) {
                $model->save();

                //
                $products = ShopProduct::model()->findAllByAttributes(array('currency_id' => $model->id));
                if (isset($products)) {
                    foreach ($products as $product) {
                        $convert = ShopProduct::model()->findByPk($product);
                        $convert->price = $convert->price * $model->rate / $model->rate_old;
                        $convert->save();
                    }
                }
                $this->redirect(array('index'));
            }
        }
        $this->render('update', array(
            'model' => $model,
            'form' => $form,
        ));
    }

    /**
     * Delete currency
     * @param array $id
     */
    public function actionDelete($id = array()) {
        if (Yii::app()->request->isPostRequest) {
            $model = ShopCurrency::model()->findAllByPk($_REQUEST['id']);

            if (!empty($model)) {
                foreach ($model as $m) {
                    if ($m->main)
                        throw new CHttpException(404, Yii::t('ShopModule.admin', 'Ошибка. Удаление главной валюты запрещено.'));
                    if ($m->default)
                        throw new CHttpException(404, Yii::t('ShopModule.admin', 'Ошибка. Удаление валюты по умолчанию запрещено.'));

                    $m->delete();
                }
            }

            if (!Yii::app()->request->isAjaxRequest)
                $this->redirect('index');
        }
    }

}
