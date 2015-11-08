<?php

class SettingsController extends AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('core', 'SETTINGS');
        $this->breadcrumbs = array(
            Yii::t('NewsModule.default', 'MODULE_NAME') => '/admin/news',
            $this->pageName
        );
        $model = new SettingsNewsForm;
        $this->topButtons = array(
            array('label' => Yii::t('core', 'RESET_SETTINGS'),
                'url' => $this->createUrl('resetSettings', array(
                    'model' => get_class($model),
                )),
                'htmlOptions' => array('class' => 'buttonS bDefault')
            )
        );
        if (isset($_POST['SettingsNewsForm'])) {
            $model->attributes = $_POST['SettingsNewsForm'];
            if ($model->validate()) {
                $model->save();
                $this->refresh();
            }
        }
        $this->render('index', array('model' => $model));
    }

}
