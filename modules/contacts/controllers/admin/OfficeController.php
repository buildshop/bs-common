<?php

class OfficeController extends AdminController {
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
         $this->pageName = Yii::t('ContactsModule.admin','OFFICE',1);
        $model = new ContactsOffice;
        $post = $_POST['ContactsOffice'];
        if (isset($post)) {
            $model->attributes = $post;
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('index', array('model' => $model, 'config' => Yii::app()->settings->get('contacts')));
    }

    public function actionCreate() {
        $this->actionUpdate(true);
    }

    /**
     * Действие редактирование новости
     */
    public function actionUpdate($new = false) {
        $model = ($new === true) ? new ContactsOffice : ContactsOffice::model()->findByPk($_GET['id']);
        $this->pageName = ($model->isNewRecord) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'UPDATE', 1);
        if (isset($_POST['ContactsOffice'])) {
            $model->attributes = $_POST['ContactsOffice'];
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('update', array('model' => $model));
    }

}
