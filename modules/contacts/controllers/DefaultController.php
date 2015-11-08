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
        $config = Yii::app()->settings->get('contacts');
        $this->pageKeywords = $config['seo_keywords'];
        $this->pageDescription = $config['seo_description'];
        $this->pageTitle = $config['seo_title'];
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if (Yii::app()->request->isPostRequest && $model->validate()) {
                $model->sendMessage();
                $this->addFlashMessage(Yii::t('ContactsModule.default', 'MESSAGE_SUCCESS'));
            } else {
                $this->addFlashMessage(Yii::t('ContactsModule.default', 'MESSAGE_FAIL'));
            }
        }
        $this->render('index', array('model' => $model, 'config' => $config));
    }

}