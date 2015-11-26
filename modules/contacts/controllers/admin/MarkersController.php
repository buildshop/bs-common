<?php

class MarkersController extends AdminController {

    public function actionIndex() {
        $this->pageName = Yii::t('ContactsModule.default', 'ROUTER');

        $this->breadcrumbs = array(
            Yii::t('ContactsModule.default', 'MODULE_NAME') => array('/admin/contacts'),
            $this->pageName
        );
        $mapsCount = ContactsMaps::model()->count();

        if ($mapsCount < 1)
            $this->topButtons = false;

        $model = new ContactsMarkers;
        $post = $_POST['ContactsMarkers'];
        if (isset($post)) {
            $model->attributes = $post;
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('index', array(
            'model' => $model,
            'mapsCount' => $mapsCount
        ));
    }

    /**
     * Действие редактирование и добавление
     * @param bool $new
     * @throws CHttpException
     */
    public function actionUpdate($new = false) {
        $mapsCount = ContactsMaps::model()->count();

        if ($mapsCount < 1)
            throw new CHttpException(403);


        $model = ($new === true) ? new ContactsMarkers : ContactsMarkers::model()->findByPk($_GET['id']);
        $this->pageName = ($model->isNewRecord) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'UPDATE', 1);
        $oldImage = $model->icon_file;

        $this->breadcrumbs = array(
            Yii::t('ContactsModule.default', 'MODULE_NAME') => array('/admin/contacts'),
            Yii::t('ContactsModule.default', 'MARKERS') => array('/admin/contacts/markers'),
            $this->pageName
        );

        if (isset($_POST['ContactsMarkers'])) {
            $model->attributes = $_POST['ContactsMarkers'];
            if ($model->validate()) {
                $model->uploadFile('icon_file', 'webroot.uploads', $oldImage);
                $model->save();
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array('model' => $model));
    }

}
