<?php

/**
 * Контроллер пользователей
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.users.controllers.admin
 * @uses AdminController
 */
class DefaultController extends AdminController {

    public function actions() {
        return array(
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
            'getAvatars' => array(
                'class' => 'application.modules.users.actions.AvatarAction',
            ),
            'saveAvatar' => array(
                'class' => 'application.modules.users.actions.SaveAvatarAction',
            ),
        );
    }

    /**
     * Список и фильрации пользователей.
     */
    public function actionIndex() {
        $model = new User('search');
        $model->unsetAttributes();
        if (!empty($_GET['User']))
            $model->attributes = $_GET['User'];
        $this->pageName = Yii::t('UsersModule.default', 'MODULE_NAME');
        $this->render('list', array(
            'model' => $model,
        ));
    }

    /**
     * Создание/редактирование пользователя
     * @param boolean $new
     */
    public function actionUpdate($new = false) {
        if ($new === true) {
            $model = new User;
            $this->pageName = Yii::t('app', 'CREATE', 1);
        } else {
            $model = User::model()->findByPk($_GET['id']);
            $this->pageName = Yii::t('app', 'UPDATE', 1);
        }

        if (!$model)
            throw new CHttpException(400);
        $oldImage = $model->avatar;
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['User'];
            if ($model->validate()) {
                $model->uploadFile('avatar', 'webroot.uploads.users.avatar', $oldImage);
                $model->save();

                if ($new === true)
                    Yii::app()->authManager->assign('Authenticated', $model->id);
                //$this->redirect(array('index'));
            }
        }

        $this->render('update', array('model' => $model));
    }

}
