<?php

class WidgetsController extends AdminController {

    public function actions() {
        return array(
            'order' => array(
                'class' => 'ext.adminList.actions.SortingAction',
            ),
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
        );
    }

    public function actionIndex() {
        $model = new WidgetsModel('search');
        $this->pageName = Yii::t('core', 'ENGINE_MENU');
        $this->breadcrumbs = array(Yii::t('core', 'SYSTEM')=>array('admin/index'),$this->pageName);

        $model->unsetAttributes();  // clear any default values    
        if (isset($_GET['WidgetsModel'])) {
            $model->attributes = $_GET['WidgetsModel'];
        }
        $this->render('index', array('model' => $model));
    }

    public function actionCreate() {
        $this->actionUpdate(true);
    }

    public function actionUpdate($new = false) {
        $model = ($new === true) ? new WidgetsModel : WidgetsModel::model()->findByPk($_GET['id']);
        if (isset($model)) {
            $this->pageName = Yii::t('core', 'ENGINE_MENU');
            $this->breadcrumbs = array(
                $this->pageName => Yii::app()->createUrl('admin/core/widgets'),
                ($new === true) ? Yii::t('core', 'CREATE', 1) : Yii::t('core', 'UPDATE', 1)
            );
            if (isset($_POST['WidgetsModel'])) {
                $model->attributes = $_POST['WidgetsModel'];
                if ($model->validate()) {
                    $model->save();
                    $this->redirect(array('index'));
                }
            }
            $this->render('update', array('model' => $model));
        } else {
            throw new CHttpException(404);
        }
    }

}