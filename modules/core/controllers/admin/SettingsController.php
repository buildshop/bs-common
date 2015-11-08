<?php

class SettingsController extends AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $model = new SettingsCoreForm;
        $this->pageName = Yii::t('app', 'SETTINGS');
        $this->breadcrumbs = array(Yii::t('app', 'SYSTEM')=>array('admin/index'),Yii::t('app', 'SETTINGS'));
        $this->topButtons = array(
            array('label' => Yii::t('app', 'RESET_SETTINGS'),
                'url' => $this->createUrl('resetSettings', array(
                    'model' => get_class($model),
                )),
                'htmlOptions' => array('class' => 'btn btn-default')
            )
        );
        
        
        if (isset($_POST['SettingsCoreForm'])) {
            $model->attributes = $_POST['SettingsCoreForm'];
            if ($model->validate()) {
                $model->save();
                $this->refresh();
            }
        }
        $this->render('index', array('model' => $model));
    }

}
