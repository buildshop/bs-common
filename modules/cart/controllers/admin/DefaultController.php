<?php

class DefaultController extends AdminController {

    /**
     * Display orders methods list
     */
    public function actionIndex() {
        $this->pageName = Yii::t('CartModule.admin', 'ORDER', 0);
        $this->breadcrumbs = array($this->pageName);
        $model = new Order('search');

        if (!empty($_GET['Order']))
            $model->attributes = $_GET['Order'];

        $dataProvider = $model->search();

        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Create new order
     */
    public function actionCreate() {
        $this->actionUpdate(true);
    }

    /**
     * Update order
     * @param bool $new
     * @throws CHttpException
     */
    public function actionUpdate($new = false) {
        Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl . '/admin/orders.update.js', CClientScript::POS_END);

        if ($new === true) {
            $model = new Order;
            $model->unsetAttributes();
        }
        else
            $model = $this->_loadModel($_GET['id']);

        if (!$model->isNewRecord) {

            $update = Yii::t('CartModule.admin', 'UPDATE_ORDER', array(
                        '{order_id}' => CHtml::encode($model->id)
                    ));
        }

        $this->pageName = ($model->isNewRecord) ? Yii::t('CartModule.admin', 'Создание заказа') : Yii::t('CartModule.admin', 'ORDER', 0);

        $this->breadcrumbs = array(
            Yii::t('CartModule.admin', 'ORDER', 0) => $this->createUrl('index'),
            ($model->isNewRecord) ? Yii::t('CartModule.admin', 'Создание заказа') : $update,
        );
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['Order'];

            if ($model->validate()) {
                $model->save();

                // Update quantities
                if (sizeof(Yii::app()->request->getPost('quantity', array())))
                    $model->setProductQuantities(Yii::app()->request->getPost('quantity'));

                $model->updateDeliveryPrice();
                $model->updateTotalPrice();




                if ($model->isNewRecord === false)
                    $template[] = 'delete';




// register all delivery methods to recalculate prices
                Yii::app()->clientScript->registerScript('deliveryMetohds', strtr('var deliveryMethods = {data};', array(
                            '{data}' => CJavaScript::jsonEncode($deliveryMethods)
                        )), CClientScript::POS_END);
                ///  if ($new) {
                //      $this->setFlashMessage(Yii::t('core', 'Теперь Вы можете добавить товары.'));
                //}
                $this->redirect(array('update', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'deliveryMethods' => ShopDeliveryMethod::model()->applyTranslateCriteria()->orderByName()->findAll(),
            'paymentMethods' => ShopPaymentMethod::model()->findAll(),
            'statuses' => OrderStatus::model()->orderByPosition()->findAll(),
            'model' => $model,
        ));
    }

    /**
     * Display gridview with list of products to add to order
     */
    public function actionAddProductList() {
        $order_id = Yii::app()->request->getQuery('id');
        $model = $this->_loadModel($order_id);
        $dataProvider = new ShopProduct('search');

        if (isset($_GET['ShopProduct']))
            $dataProvider->attributes = $_GET['ShopProduct'];

        $this->renderPartial('_addProduct', array(
            'dataProvider' => $dataProvider,
            'order_id' => $order_id,
            'model' => $model,
        ));
    }

    /**
     * Add product to order
     * @throws CHttpException
     */
    public function actionAddProduct() {
        if (Yii::app()->request->isPostRequest) {
            $order = $this->_loadModel($_POST['order_id']);
            $product = ShopProduct::model()->findByPk($_POST['product_id']);

            if (!$product)
                throw new CHttpException(404, Yii::t('CartModule.admin', 'Ошибка. Продукт не найден.'));

            $order->addProduct($product, $_POST['quantity'], $_POST['price']);
        }
    }

    /**
     * Render ordered products after new product added.
     * @param $order_id
     */
    public function actionRenderOrderedProducts($order_id) {
        $this->renderPartial('_orderedProducts', array(
            'model' => $this->_loadModel($order_id)
        ));
    }

    /**
     * Get ordered products in json format.
     * Result is displayed in the orders list.
     */
    public function actionJsonOrderedProducts() {
        $model = $this->_loadModel(Yii::app()->request->getQuery('id'));
        $data = array();

        foreach ($model->getOrderedProducts()->getData() as $product) {
            $data[] = array(
                'name' => $product->renderFullName,
                'quantity' => $product->quantity,
                'price' => ShopProduct::formatPrice($product->price),
            );
        }

        echo CJSON::encode($data);
    }

    /**
     * Load order model
     * @param $id
     * @return Order
     * @throws CHttpException
     */
    protected function _loadModel($id) {
        $model = Order::model()->findByPk($id);

        if (!$model)
            $this->error404();

        return $model;
    }

    /**
     * Delete order
     * @param array $id
     */
    public function actionDelete($id = array()) {
        $model = Order::model()->findAllByPk($_REQUEST['id']);

        if (!empty($model)) {
            foreach ($model as $m)
                $m->delete();
        }
        $this->sendEmailFormDelete($model);
        if (!Yii::app()->request->isAjaxRequest)
            $this->redirect('index');
    }

    private function sendEmailFormDelete($model) {
        //TODO: отправка уведомление пользователя о удаление заказа
    }

    /**
     * Delete product from order
     */
    public function actionDeleteProduct() {
        $order = Order::model()->findByPk(Yii::app()->request->getPost('order_id'));

        if (!$order)
            $this->error404();

        $order->deleteProduct(Yii::app()->request->getPost('id'));
    }

    /**
     * Render order history tab
     */
    public function actionHistory() {
        $id = Yii::app()->request->getQuery('id');
        $model = Order::model()->findByPk($id);

        if (!$model)
            $this->error404();

        $this->render('_history', array(
            'model' => $model
        ));
    }

    /**
     * @throws CHttpException
     */
    public function error404() {
        throw new CHttpException(404, Yii::t('CartModule.admin', 'NOT_FOUND_ORDER'));
    }

    /**
     * Дополнительное меню Контроллера.
     * @return array
     */
    public function getAddonsMenu() {
        return array(
            array(
                'label' => Yii::t('CartModule.admin', 'STATUSES'),
                'url' => Yii::app()->createUrl('/admin/cart/statuses'),
                'icon' => 'icon-plus',
                'visible' => Yii::app()->user->isSuperuser
            ),
            array(
                'label' => Yii::t('CartModule.admin', 'STATS'),
                'url' => Yii::app()->createUrl('/admin/cart/statistics'),
                'icon' => 'icon-stats',
                'visible' => Yii::app()->user->isSuperuser
            ),
            array(
                'label' => Yii::t('CartModule.admin', 'HISTORY'),
                'url' => Yii::app()->createUrl('/admin/cart/history'),
                'icon' => 'icon-checkmark',
                'visible' => Yii::app()->user->isSuperuser
            ),
            array(
                'label' => Yii::t('core', 'SETTINGS'),
                'url' => Yii::app()->createUrl('/admin/cart/settings'),
                'icon' => 'icon-settings',
                'visible' => Yii::app()->user->isSuperuser
            ),
        );
    }

}
