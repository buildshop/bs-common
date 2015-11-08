<?php

class SecurityController extends AdminController {

    public function actionIndex() {
        $this->topButtons = false;
        $model = new SettingsSecurityForm;
        $this->pageName = Yii::t('app', 'SECURITY');
        $this->breadcrumbs = array(Yii::t('app', 'SYSTEM') => array('admin/index'), $this->pageName);
        $this->clearLog();
        if (isset($_POST['SettingsSecurityForm'])) {
            $post = $_POST['SettingsSecurityForm'];
            $model->attributes = $post;
            $model->backup_time_cache = time() - $post['backup_time'] * 60;
            if ($model->validate()) {
                $model->backup_time = $post['backup_time'] * 60;
                $model->save();
                $this->refresh();
            }
        }

        $this->render('index', array('model' => $model));
    }

    private function clearLog() {
        if (isset($_GET['clear'])) {
            $this->log_file_path = Yii::app()->getRuntimePath() . DS . 'application.log';
            if (file_exists($this->log_file_path)) {
                unlink($this->log_file_path);
                Yii::app()->user->setFlash('success','Логи успешно очищены.');
            }
        }
    }

    public function actionBanlist() {
        $model = new BannedIPModel('search');
        $this->pageName = Yii::t('app', 'BANNED_IP');
        $this->breadcrumbs = array(
            Yii::t('app', 'SYSTEM') => array('admin/index'),
            Yii::t('app', 'SECURITY') => array('admin/security'),
            $this->pageName
        );

        $model->unsetAttributes();  // clear any default values    
        if (isset($_GET['BannedIPModel'])) {
            $model->attributes = $_GET['BannedIPModel'];
        }
        $this->render('banlist', array('model' => $model));
    }

    public function actionCreate() {
        $this->actionUpdate(true);
    }

    public function actionUpdate($new = false) {
        $model = ($new === true) ? new BannedIPModel : BannedIPModel::model()->findByPk($_GET['id']);
        if (isset($model)) {
            $this->pageName = Yii::t('app', 'BANNED_IP');
            $this->breadcrumbs = array(
                $this->pageName => Yii::app()->createUrl('admin/core/security'),
                ($new === true) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'UPDATE', 1)
            );
            if (isset($_POST['BannedIPModel'])) {
                $model->attributes = $_POST['BannedIPModel'];
                if ($model->validate()) {
                    $model->save();
                    $this->redirect(array('banlist'));
                }
            }
            $this->render('update', array('model' => $model));
        } else {
            throw new CHttpException(404);
        }
    }

    public function getAddonsMenu() {
        return array(
            array(
                'label' => Yii::t('app', 'BANNED_IP'),
                'url' => Yii::app()->createUrl('/admin/core/security/banlist'),
                'icon' => 'icon-lock',
                'visible' => Yii::app()->user->isSuperuser
            ),
            array(
                'label' => Yii::t('app', 'LOGS'),
                'url' => Yii::app()->createUrl('/admin/core/security/logs'),
                'icon' => 'icon-list',
                'visible' => Yii::app()->user->isSuperuser
            ),
        );
    }

    public function actionLogs() {
        $this->render('logs', array('model' => $model));
    }

}
