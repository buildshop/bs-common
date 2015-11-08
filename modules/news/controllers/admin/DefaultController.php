<?php

/**
 * Контроллер админ-панели статичных страниц
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.news.controllers.admin
 * @uses AdminController
 */
class DefaultController extends AdminController {

    public function actions() {
        return array(
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
            'order' => array(
                'class' => 'ext.adminList.actions.SortingAction',
            ),
        );
    }

    public function actionIndex() {
        $this->pageName = Yii::t('NewsModule.default', 'MODULE_NAME');
        $this->breadcrumbs = array($this->pageName);
        $model = new News('search');
        $model->unsetAttributes();
        if (!empty($_GET['News']))
            $model->attributes = $_GET['News'];

        $this->render('index', array('model' => $model));
    }

    public function actionCreate() {
        $this->actionUpdate(true);
    }

    /**
     * Create or update new page
     * @param boolean $new
     */
    public function actionUpdate($new = false) {
        if ($new === true) {
            $model = new News;
        } else {
            $model = News::model()
                    ->language(Yii::app()->language->active)
                    ->findByPk($_GET['id']);
        }

        $this->breadcrumbs = array(
            Yii::t('NewsModule.default', 'MODULE_NAME') => $this->createUrl('index'),
            ($model->isNewRecord) ? $model->t('PAGE_TITLE', 0) : CHtml::encode($model->title),
        );

        $this->pageName = ($model->isNewRecord) ? $model->t('PAGE_TITLE', 0) : $model->t('PAGE_TITLE', 1);
        if (!$model)
            throw new CHttpException(404);

        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['News'];
            if ($model->validate()) {
                $model->save();
                if(!$this->edit_mode) $this->redirect(array('index'));
                    
            }
        }
        $this->render('update', array('model' => $model));
    }

    public function getAddonsMenu() {
        return array(
            array(
                'label' => Yii::t('core', 'SETTINGS'),
                'url' => Yii::app()->createUrl('/admin/news/settings/index'),
                'icon' => 'icon-settings',
                'visible' => Yii::app()->user->isSuperuser
            ),
        );
    }

}

