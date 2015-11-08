<?php

class DefaultController extends AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('ContactsModule.default', 'MODULE_NAME');
        $model = new ConfigContactForm;
        $post = $_POST['ConfigContactForm'];
        
        
                $this->topButtons = array(
            array('label' => Yii::t('core', 'RESET_SETTINGS'),
                'url' => $this->createUrl('resetSettings', array(
                    'model' => get_class($model),
                )),
                'htmlOptions' => array('class' => 'buttonS bDefault')
            )
        );
                
        if (isset($post)) {
            $model->attributes = $post;
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('index', array('model' => $model, 'config' => Yii::app()->settings->get('contacts')));
    }

}
