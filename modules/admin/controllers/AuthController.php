<?php

Yii::import('mod.users.forms.UserLoginForm');

class AuthController extends Controller {

    public $layout = 'login';

    public function allowedActions() {
        return 'index, logout';
    }

    public function actionIndex() {
        if (!Yii::app()->user->isGuest)
            $this->redirect('/admin');

        $model = new UserLoginForm('adminAuth');
        $this->performAjaxValidation($model);
        if (isset($_POST['UserLoginForm'])) {
            $model->attributes = $_POST['UserLoginForm'];
            if ($model->validate()) {
                $duration = ($model->rememberMe) ? (int)Yii::app()->settings->get('core', 'cookie_time') : 0;
                Yii::app()->user->login($model->getIdentity(), $duration);
                //Yii::app()->user->renewAuthTimeout($duration);
                $this->setFlashMessage(Yii::t('app', 'WELCOME', array('{USER_NAME}' => Yii::app()->user->getName())));
                $this->redirect($this->createUrl('/admin'));
            }
        }
        $this->render('auth', array('model' => $model));
    }

    /**
     * Logout user
     */
    public function actionLogout() {
        if (Yii::app()->user->isGuest)
            throw new CHttpException(405, Yii::t('core', 'ERR_NOAUTH'));
        Yii::app()->user->logout();
        Yii::app()->request->redirect($this->createUrl('/admin'));
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
