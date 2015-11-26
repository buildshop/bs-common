<?php

class DefaultController extends AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('ContactsModule.default', 'MODULE_NAME');

        $this->breadcrumbs = array($this->pageName);
        $model = new ConfigContactForm;
        $post = $_POST['ConfigContactForm'];
        if (isset($post)) {
            $model->attributes = $post;
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('index', array('model' => $model, 'config' => Yii::app()->settings->get('contacts')));
    }

}
