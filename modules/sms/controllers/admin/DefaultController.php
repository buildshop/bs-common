<?php

class DefaultController extends AdminController {



    public $topButtons = false;

    public function actionConfigurationForm() {
        Yii::import('app.sms_settings.*');
        $systemId = Yii::app()->request->getQuery('system');
        if (empty($systemId))
            exit;
        $manager = new SMSSystemManager;
        $system = $manager->getSystemClass($systemId);
        if ($system) {
            echo $system->getConfigurationFormHtml($systemId);
        }
    }

    public function actionIndex() {
        $this->pageName = Yii::t('SmsModule.default', 'MODULE_NAME');
        $this->breadcrumbs = array(
            $this->pageName
        );
        $model = new SettingsSmsForm;
        if (isset($_POST['SettingsSmsForm'])) {
            $model->attributes = $_POST['SettingsSmsForm'];
            if ($model->validate()) {
                $model->save();
            }
        }

        if (isset($_POST['SettingsSmsForm']['service'])) {
            Yii::import('app.sms_settings.*');
            $manager = new SMSSystemManager;
            $system = $manager->getSystemClass($_POST['SettingsSmsForm']['service']);
            if ($system) {
                $system->setSettings($_POST['SettingsSmsForm']['service'], $_POST);
            } else {
                
            }
        }

        $this->render('index', array('model' => $model));
    }

    public function actionSmslist() {
        $this->pageName = Yii::t('SmsModule.default', 'MODULE_NAME');
        $this->breadcrumbs = array(
            $this->pageName
        );
        $model = new TplSMS('TplSMS');
        $model->unsetAttributes();
        if (!empty($_GET['TplSMS']))
            $model->attributes = $_GET['TplSMS'];
        $this->render('smslist', array('model' => $model));
    }
    public function actionUpdate($new = false) {
        if ($new === true) {
            $model = new TplSMS;
            $this->pageName = Yii::t('app', 'CREATE', 1);
        } else {
            $model = TplSMS::model()->findByPk($_GET['id']);
            $this->pageName = Yii::t('app', 'UPDATE', 1);
        }

        if (!$model)
            throw new CHttpException(400);

        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['TplSMS'];
            if ($model->validate()) {
                  
                $model->save();

            }
        }

        $this->render('update', array('model' => $model));
    }
}
