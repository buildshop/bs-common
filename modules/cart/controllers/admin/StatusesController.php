<?php

/**
 * Admin order statuses
 */
class StatusesController extends AdminController {

    /**
     * Display statuses list
     */
    public function actionIndex() {
        $this->pageName = Yii::t('CartModule.admin', 'STATUSES');

        $this->breadcrumbs = array($this->pageName);
        $model = new OrderStatus('search');
        $model->unsetAttributes();

        if (!empty($_GET['OrderStatus']))
            $model->attributes = $_GET['OrderStatus'];

        $dataProvider = $model->search();

        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Create new status
     */
    public function actionCreate() {
        $this->actionUpdate(true);
    }

    /**
     * Update status
     * @param bool $new
     * @throws CHttpException
     */
    public function actionUpdate($new = false) {
        if ($new === true) {
            $model = new OrderStatus;
            $model->unsetAttributes();
        }
        else
            $model = OrderStatus::model()->findByPk($_GET['id']);

        if (!$model)
            throw new CHttpException(404, Yii::t('CartModule.admin', 'NO_STATUSES'));

        $title = ($model->isNewRecord) ? Yii::t('CartModule.admin', 'CONTROL_STATUSES', 1) :
                Yii::t('CartModule.admin', 'CONTROL_STATUSES', 0);

        $this->breadcrumbs = array(
            Yii::t('CartModule.admin', 'STATUSES') => $this->createUrl('index'),
            ($model->isNewRecord) ? Yii::t('CartModule.admin', 'CONTROL_STATUSES', 1) : Html::encode($model->name),
        );

        $this->pageName = $title;

        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['OrderStatus'];
            if ($model->validate()) {
                $model->save();
                $this->redirect('index');
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Delete status
     * @param array $id
     */
    public function actionDelete($id = array()) {
        if (Yii::app()->request->isPostRequest) {
            $model = OrderStatus::model()->findAllByPk($_REQUEST['id']);

            if (!empty($model)) {
                foreach ($model as $m) {
                    if ($m->countOrders() == 0 && $m->id != 1)
                        $m->delete();
                    else
                        throw new CHttpException(409, Yii::t('CartModule.admin', 'ERR_DELETE_STATUS'));
                }
            }

            if (!Yii::app()->request->isAjaxRequest)
                $this->redirect('index');
        }
    }

    /**
     * Дополнительное меню Контроллера.
     * @return array
     */
    public function getAddonsMenu() {
        return array(
            array(
                'label' => Yii::t('CartModule.admin', 'ORDER',0),
                'url' => Yii::app()->createUrl('/admin/cart'),
                'icon' => 'icon-cart-3',
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
        );
    }

}
