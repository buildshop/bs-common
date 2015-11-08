<?php

/**
 * Контроллер настройки пользователей.
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.users.controllers.admin
 * @uses AdminController
 */
class SettingsController extends AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('app', 'SETTINGS');
        $this->breadcrumbs = array(Yii::t('app', 'SETTINGS'));

        $model = new SettingsUsersForm;
        $this->topButtons = array(
            array('label' => Yii::t('app', 'RESET_SETTINGS'),
                'url' => $this->createUrl('resetSettings', array(
                    'model' => get_class($model),
                )),
                'htmlOptions' => array('class' => 'btn btn-default')
            )
        );

        if (isset($_POST['SettingsUsersForm'])) {
            $model->attributes = $_POST['SettingsUsersForm'];
            if ($model->validate()) {
                $model->save();
                $this->refresh();
            }
        }
        $this->render('index', array('model' => $model));
    }

}
