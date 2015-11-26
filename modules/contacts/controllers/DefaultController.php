<?php

class DefaultController extends Controller {

    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'testLimit' => '1',
            ),
        );
    }

    public function actionIndex() {
        $this->breadcrumbs = array(Yii::t('ContactsModule.default', 'MODULE_NAME'));
        $model = new ContactForm;
        $this->performAjaxValidation($model, 'contact_form');

        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if (Yii::app()->request->isPostRequest && $model->validate()) {

                $model->sendMessage();
                $model->unsetAttributes();
                Yii::app()->user->setFlash('success', Yii::t('ContactsModule.default', 'MESSAGE_SUCCESS'));
                //  $this->addFlashMessage(Yii::t('ContactsModule.default', 'MESSAGE_SUCCESS'));
            } else {
                //$this->addFlashMessage(Yii::t('ContactsModule.default', 'MESSAGE_FAIL'));
                Yii::app()->user->setFlash('error', Yii::t('ContactsModule.default', 'MESSAGE_FAIL'));
            }
        }
        $this->render('index', array('model' => $model, 'config' => $config));
    }

}
