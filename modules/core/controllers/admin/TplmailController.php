<?php

class TplmailController extends AdminController {



    public function actionIndex() {
        $this->pageName = Yii::t('app', 'TplMail');
        $this->breadcrumbs = array(Yii::t('app', 'SYSTEM') => array('admin/index'), $this->pageName);

      /*  $this->topButtons = array(array(
                'label' => Yii::t('app', 'CREATE_LANG'),
                'url' => Yii::app()->createUrl('admin/core/languages/create'),
                'htmlOptions' => array('class' => 'btn btn-success')
                ));*/
        $model = new TplMail('search');

        if (isset($_GET['TplMail'])) {
            $model->attributes = $_GET['TplMail'];
        }
        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($new = false) {
        $model = ($new === true) ? new TplMail : TplMail::model()->findByPk($_GET['id']);
        if (isset($model)) {
            $this->pageName = Yii::t('app', 'TPLMAIL');
            $this->breadcrumbs = array(
                $this->pageName => Yii::app()->createUrl('admin/core/tplmail'),
                ($new === true) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'UPDATE', 1)
            );

            if (isset($_POST['TplMail'])) {
                $model->attributes = $_POST['TplMail'];
                if ($model->validate()) {
                    $model->save();
                }
            }
            $this->render('update', array('model' => $model));
        } else {
            throw new CHttpException(404);
        }
    }

}