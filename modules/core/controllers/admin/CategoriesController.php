<?php

class CategoriesController extends AdminController {

    public function actionIndex() {
        $this->pageName = Yii::t('app', 'CATEGORIES');
        $this->breadcrumbs = array(Yii::t('app', 'SYSTEM')=>array('admin/index'),$this->pageName);
        $model = new CategoriesModel('search');
        $model->unsetAttributes();  // clear any default values    
        if (isset($_GET['CategoriesModel'])) {
            $model->attributes = $_GET['CategoriesModel'];
        }
        $this->render('index', array('model' => $model));
    }


    public function actionUpdate($new = false) {
        $model = ($new === true) ? new CategoriesModel : CategoriesModel::model()->findByPk($_GET['id']);
        $this->pageName = Yii::t('app', 'CATEGORIES');
        $this->breadcrumbs = array(
            $this->pageName => Yii::app()->createUrl('admin/core/categories'),
            ($new === true) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'UPDATE', 1)
        );

        if (isset($_POST['CategoriesModel'])) {
            $model->attributes = $_POST['CategoriesModel'];
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('update', array('model' => $model));
    }

}