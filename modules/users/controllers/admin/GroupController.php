<?php

/**
 * Контроллер групп пользователей (Не реализовано!)
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.users.controllers.admin
 * @uses AdminController
 */
class GroupController extends AdminController {

    // public $topButtons = false;
    public function actionIndex() {
        $this->pageName = Yii::t('app', 'CATEGORIES');
        $this->breadcrumbs = array(Yii::t('app', 'SYSTEM') => array('admin/index'), $this->pageName);
        $model = new UserGroup('search');
        $model->unsetAttributes();  // clear any default values    
        if (isset($_GET['UserGroup'])) {
            $model->attributes = $_GET['UserGroup'];
        }
        $this->render('index', array('model' => $model));
    }

    public function actionUpdate($new = false) {
        
        if($_GET['id']==1)
            throw new CHttpException(403);
        $model = ($new === true) ? new UserGroup : UserGroup::model()->findByPk($_GET['id']);
        $this->pageName = Yii::t('app', 'GROUP');
        $this->breadcrumbs = array(
            $this->pageName => Yii::app()->createUrl('admin/users/'),
            ($new === true) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'UPDATE', 1)
        );

        if (isset($_POST['UserGroup'])) {


          //  print_r($_POST['UserGroup']['access_action']);
          //  die;

            $model->attributes = $_POST['UserGroup'];
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('update', array('model' => $model));
    }

}
