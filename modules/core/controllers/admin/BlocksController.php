<?php

class BlocksController extends AdminController {

    public function actions() {
        return array(
            'order' => array(
                'class' => 'ext.adminList.actions.SortingAction',
            ),
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
        );
    }

    public function actionConfigurationForm() {
        Yii::import('app.blocks_settings.*');
        $systemId = Yii::app()->request->getQuery('system');
        if (empty($systemId))
            exit;
        $manager = new BlockSystemManager;
        $system = $manager->getSystemClass($systemId);
        if($system){
            echo $system->getConfigurationFormHtml($systemId); 
        }
       
    }

    public function actionIndex() {
        $this->pageName = Yii::t('app', 'BLOCKS');
        $this->breadcrumbs = array(Yii::t('app', 'SYSTEM') => array('admin/index'), $this->pageName);
        $model = new BlocksModel('search');
        $model->unsetAttributes();  // clear any default values    
        if (isset($_GET['BlocksModel'])) {
            $model->attributes = $_GET['BlocksModel'];
        }
        $this->render('index', array('model' => $model));
    }

    public function actionUpdate($new = false) {
        $model = ($new === true) ? new BlocksModel : BlocksModel::model()->findByPk($_GET['id']);
        if (isset($model)) {
            $this->pageName = Yii::t('app', 'BLOCKS');
            $this->breadcrumbs = array(
                $this->pageName => Yii::app()->createUrl('admin/core/blocks'),
                ($new === true) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'UPDATE', 1)
            );

            if (isset($_POST['BlocksModel'])) {
                $model->attributes = $_POST['BlocksModel'];
                if (!empty($model->modules))
                    $model->modules = implode(',', $_POST['BlocksModel']['modules']);
                if ($model->validate()) {
                    if ($_POST['BlocksModel']['expire'] == 0) {
                        $model->expire = 0;
                    } else {
                        $model->expire = time() + ($_POST['BlocksModel']['expire'] * 86400);
                    }

                    if ($model->widget) {
                        Yii::import('app.blocks_settings.*');
                       $manager = new BlockSystemManager;
                        $system = $manager->getSystemClass($model->widget);
                        if($system) {
                            $system->saveSettings($model->widget, $_POST);
                        }else{
                           
                        }
                    }
                    $model->save();
                    //$this->refresh();
                }
            } else {
                
            }
            if (!empty($model->modules)) {
                $modules = explode(',', $model->modules);
                foreach ($modules as $mod) {
                    $mods[] = $mod;
                }
                $model->modules = $mods;
            }

            $this->render('update', array('model' => $model));
        } else {
            throw new CHttpException(404);
        }
    }

}