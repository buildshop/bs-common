<?php

class ManufacturerController extends AdminController {

    public function actions() {
        return array(
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
            'order' => array(
                'class' => 'ext.adminList.actions.SortingAction',
            ),
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
        );
    }

    /**
     * Display manufacturers list
     */
    public function actionIndex() {
        $model = new ShopManufacturer('search');

        if (!empty($_GET['ShopManufacturer']))
            $model->attributes = $_GET['ShopManufacturer'];

        $this->pageName = Yii::t('ShopModule.admin', 'BRANDS');

        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            $this->pageName
        );

        $this->topButtons = array(
            array('label' => Yii::t('ShopModule.admin', 'Добавить производителя'),
                'url' => $this->createUrl('create'),
                'htmlOptions' => array('class' => 'btn btn-success')
            )
        );

        $dataProvider = $model->orderByName()->search();


        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Update manufacturer
     * @param bool $new
     * @throws CHttpException
     */
    public function actionUpdate($new = false) {

        if ($new === true) {
            $model = new ShopManufacturer;
        } else {
            $model = ShopManufacturer::model()
                    ->findByPk($_GET['id']);
        }
        $this->topButtons = false;
        if (!$model)
            throw new CHttpException(404, Yii::t('ShopModule.admin', 'NO_FOUND_BRAND'));

        $oldImage = $model->image;

        $this->pageName = ($model->isNewRecord) ? $model::t('PAGE_NAME', 0) : $model::t('PAGE_NAME', 1);

        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            Yii::t('ShopModule.admin', 'BRANDS') => $this->createUrl('index'),
            $this->pageName
        );
        
        if (isset($_POST['ShopManufacturer'])) {
            $model->attributes = $_POST['ShopManufacturer'];

            if ($model->validate()) {
                $model->uploadFile('image', 'webroot.uploads.manufacturer', $oldImage);
                $model->save();
            }
        }
        $this->render('update', array('model' => $model));
    }

}
