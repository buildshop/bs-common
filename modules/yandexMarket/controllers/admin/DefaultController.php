<?php

class DefaultController extends AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('YandexMarketModule.default','MODULE_NAME');
        
        $this->breadcrumbs = array(
            Yii::t('YandexMarketModule.default', 'MODULE_NAME') => array('/admin/shop'),
            $this->pageName
        );

        $model = new SettingsYandexMarketForm;
        if (isset($_POST['SettingsYandexMarketForm'])) {
            $model->attributes = $_POST['SettingsYandexMarketForm'];
            if ($model->validate()) {
                $model->save();
                $this->refresh();
            }
        }
        $this->render('index', array('model' => $model));
    }

}
