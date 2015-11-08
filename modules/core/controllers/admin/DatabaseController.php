<?php

class DatabaseController extends AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $model = new SettingsDatabaseForm;
        $this->pageName = Yii::t('app', 'DATABASE');
        $this->breadcrumbs = array(Yii::t('app', 'SYSTEM') => array('admin/index'), $this->pageName);
        $post = $_POST['SettingsDatabaseForm'];
        $model->attributes = $post;
        if (isset($post) && $model->backup) {
            Yii::app()->database->export();
            $this->setFlashMessage(Yii::t('app', 'BACKUP_DB_SUCCESS'));
            $this->refresh();
        }
        $backupPath = Yii::getPathOfAlias(Yii::app()->database->backupPath);
        if (file_exists($backupPath)) {
            $fdir = opendir($backupPath);
            $data = array();
            while ($file = readdir($fdir)) {
                if ($file != '.' and $file != '..' and $file != '.htaccess' and $file != 'index.html') {
                    $data[] = array(
                        'filename' => $file,
                        'url' => Html::link(Yii::t('app', 'DELETE'), '/admin/core/database/delete?file=' . $file, array('class' => 'btn btn-danger btn-xs')));
                }
            }
            closedir($fdir);
        } else {

            throw new CHttpException(404, Yii::t('app', 'DIR_NOT_FOUND', array('{dir}' => Yii::app()->database->backupPath)));
        }
        $data_db = new CArrayDataProvider($data, array(
                    'sort' => array(
                        'attributes' => array('filename'),
                        'defaultOrder' => array('filename' => false),
                    ),
                        )
        );
        $this->render('index', array('model' => $model, 'data_db' => $data_db));
    }

    public function actionDelete() {
        $filedel = $_GET['file'];
        if (isset($filedel)) {
            $filePath = Yii::getPathOfAlias(Yii::app()->database->backupPath) . DS . $filedel;
            if (file_exists($filePath)) {
                @unlink($filePath);
                $this->setFlashMessage(Yii::t('app', 'FILE_SUCCESS_DELETE'));
                $this->redirect(array('/admin/core/database'));
            } else {
                $this->setFlashMessage(Yii::t('app', 'ERR_FILE_NOT_FOUND'));
            }
        }
    }

}
