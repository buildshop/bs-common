<?php

Yii::import('mod.users.forms.RemindPasswordForm');

/**
 * Контроллер востановление паролья и активации пользователей.
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.users.controllers
 * @uses Controller
 */
class RemindController extends Controller {

    /**
     * @param CAction $action
     * @return bool
     */
    public function beforeAction($action) {
        // Allow only gues access
        if (Yii::app()->user->isGuest)
            return true;
        else
            $this->redirect('/');
    }

    public function actionIndex() {
        $model = new RemindPasswordForm;
        
        $this->pageName = Yii::t('UsersModule.default', 'REMIN_PASS');
        $this->pageTitle = $this->pageName;
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['RemindPasswordForm'];
            if ($model->validate()) {
                $model->sendRecoveryMessage();
                Yii::app()->user->setFlash('success', Yii::t('UsersModule.core', 'На вашу почту отправлены инструкции по активации нового пароля.'));
               // $this->addFlashMessage(Yii::t('UsersModule.core', 'На вашу почту отправлены инструкции по активации нового пароля.'));
                $this->refresh();
            }
        }

        $this->render('index', array(
            'model' => $model
        ));
    }

    /**
     * @param $key
     */
    public function actionActivatePassword($key) {
        if (User::activeNewPassword($key) === true) {
            $this->addFlashMessage(Yii::t('UsersModule.core', 'Ваш новый пароль успешно активирован.'));
            $this->redirect(array('/users/login/login'));
        } else {
            $this->addFlashMessage(Yii::t('UsersModule.core', 'Ошибка активации пароля.'));
            $this->redirect(array('/users/remind'));
        }
    }

}
