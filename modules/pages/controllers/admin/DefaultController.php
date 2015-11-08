<?php

/**
 * Контроллер админ-панели статичных страниц
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.pages.controllers.admin
 * @uses AdminController
 */
class DefaultController extends AdminController {

    public function actions() {
        return array(
            'published' => array(
                'class' => 'ext.adminList.actions.PublishedAction',
            ),
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
        );
    }

    /**
     * Display pages list.
     */
    public function actionIndex() {
        $this->pageName = Yii::t('PagesModule.default', 'MODULE_NAME');
        $this->breadcrumbs = array($this->pageName);
        $model = new Page('search');
        if (!empty($_GET['Page']))
            $model->attributes = $_GET['Page'];

        $this->render('index', array(
            'model' => $model,
        ));
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
            $model = new Page;
        } else {
            $model = Page::model()
                    ->cache($this->cacheTime)
                    ->language(Yii::app()->language->active)
                    ->findByPk($_GET['id']);
        }

        $this->breadcrumbs = array(
            Yii::t('PagesModule.default', 'MODULE_NAME') => $this->createUrl('index'),
            ($model->isNewRecord) ? $model->t('PAGE_TITLE', 0) : CHtml::encode($model->title),
        );

        $this->pageName = ($model->isNewRecord) ? $model->t('PAGE_TITLE', 0) : $model->t('PAGE_TITLE', 1);
        if (!$model)
            throw new CHttpException(404);

        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['Page'];
            if ($model->validate()) {
                $model->save();
                if ($model->in_menu == 1) {
                    $isset = MenuModel::model()->findByAttributes(array('url' => '/page/' . $model->seo_alias));
                    if (!isset($isset)) {
                        $menu = new MenuModel;
                        $menu->label = $model->title;
                        $menu->url = '/page/' . $model->seo_alias;
                        if ($menu->validate()) {
                            $menu->save(false,false);
                        }
                    }
                }
                $this->redirect(array('index'));
            }
        }
        $this->render('update', array('model' => $model));
    }

}

