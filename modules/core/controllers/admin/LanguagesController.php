<?php

/**
 * Manage system languages
 * @package core.systemLanguages
 */
class LanguagesController extends AdminController {

    public function actions() {
        return array(
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
        );
    }

    public function actionIndex() {
        $this->pageName = Yii::t('app', 'LANGUAGES');
        $this->breadcrumbs = array(Yii::t('app', 'SYSTEM') => array('admin/index'), $this->pageName);

        $this->topButtons = array(array(
                'label' => Yii::t('app', 'CREATE_LANG'),
                'url' => Yii::app()->createUrl('admin/core/languages/create'),
                'htmlOptions' => array('class' => 'btn btn-success')
                ));
        $model = new LanguageModel('search');

        if (isset($_GET['LanguageModel'])) {
            $model->attributes = $_GET['LanguageModel'];
        }
        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($new = false) {
        $this->topButtons = false;
        $model = ($new === true) ? new LanguageModel : LanguageModel::model()->findByPk($_GET['id']);
        $this->breadcrumbs = array(
            Yii::t('app', 'LANGUAGES') => $this->createUrl('index'),
            ($model->isNewRecord) ? Yii::t('app', 'CREATED_LANG', 0) : CHtml::encode($model->name),
        );
        $this->pageName = ($model->isNewRecord) ? Yii::t('app', 'CREATED_LANG', 0) : Yii::t('app', 'CREATED_LANG', 1);
        if (!$model)
            throw new CHttpException(404, Yii::t('app', 'LANG_NOFIND'));

        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['LanguageModel'];
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionAjaxOnlineTranslate() {
        $this->onlineTranslate($_POST['lang'], $_POST['text']);
    }

    public function onlineTranslate($lang = array('ru', 'en'), $text) {
        $t = new yandexTranslate;
        $response = $t->translate(array($lang[0], $lang[1]), $text);
        header('Content-Type: application/json');
        echo CJSON::encode($response['text']);
    }

    public function actionOnline() {
        $this->pageName = 'Онлайн переводчик';
        $this->render('online');
    }

    public function getAddonsMenu() {
        return array(
            array(
                'label' => Yii::t('admin', 'Онлайн переводчик'),
                'url' => Yii::app()->createUrl('/admin/core/languages/online'),
                'icon' => 'icon-spell-check',
                'visible' => Yii::app()->user->isSuperuser
            ),
            array(
                'label' => Yii::t('app', 'TRANSLATES'),
                'url' => Yii::app()->createUrl('/admin/core/translates'),
                'icon' => 'icon-spell-check',
                'visible' => Yii::app()->user->isSuperuser
            ),
        );
    }

}