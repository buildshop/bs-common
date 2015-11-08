<?php

class DeliveryController extends AdminController {

    public function actions() {
        return array(
            'order' => array(
                'class' => 'ext.adminList.actions.SortingAction',
            ),
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
        );
    }

    public function actionIndex() {
        $model = new ShopDeliveryMethod('search');
        $model->unsetAttributes();

        if (!empty($_GET['ShopDeliveryMethod']))
            $model->attributes = $_GET['ShopDeliveryMethod'];

        $dataProvider = $model->search();
        $this->pageName = Yii::t('CartModule.admin', 'DELIVERY');

        $this->breadcrumbs = array(
            Yii::t('CartModule.default', 'MODULE_NAME') => array('/admin/shop'),
            $this->pageName
        );

        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Create new delivery methods
     */
    public function actionCreate() {
        $this->actionUpdate(true);
    }

    /**
     * Update delivery method
     * @param bool $new
     * @throws CHttpException
     */
    public function actionUpdate($new = false) {
        if ($new === true) {
            $model = new ShopDeliveryMethod;
            $model->unsetAttributes();
        } else {
            $model = ShopDeliveryMethod::model()
                    ->language(Yii::app()->language)
                    ->findByPk($_GET['id']);
        }

        if (!$model)
            throw new CHttpException(404, Yii::t('CartModule.admin', 'NO_FOUND_DELIVERY'));


        $this->pageName = ($model->isNewRecord) ? $model->t('IS_CREATE', 0) : $model->t('IS_CREATE', 1);


        $this->breadcrumbs = array(
            Yii::t('CartModule.default', 'MODULE_NAME') => array('/admin/shop'),
            Yii::t('CartModule.admin', 'DELIVERY') => $this->createUrl('index'),
            $this->pageName
        );



        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['ShopDeliveryMethod'];

            if ($model->validate()) {
                $model->save();
                $this->redirect('index');
            }
        }

        $this->render('update', array('model' => $model));
    }

    /**
     * Delete method
     * @param array $id
     */
    public function actionDelete($id = array()) {
        if (Yii::app()->request->isPostRequest) {
            $model = ShopDeliveryMethod::model()->findAllByPk($_REQUEST['id']);

            if (!empty($model)) {
                foreach ($model as $m) {
                    if ($m->countOrders() == 0)
                        $m->delete();
                    else
                        throw new CHttpException(409, Yii::t('CartModule.admin', 'ERR_DEL_DELIVERY'));
                }
            }

            if (!Yii::app()->request->isAjaxRequest)
                $this->redirect('index');
        }
    }

}
