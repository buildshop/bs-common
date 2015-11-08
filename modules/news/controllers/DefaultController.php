<?php

/**
 * Контроллер статичных страниц
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.pages.controllers
 * @uses Controller
 */
class DefaultController extends Controller {

    public function actionSuggestTags() {
        if (isset($_GET['q']) && ($keyword = trim($_GET['q'])) !== '') {
            $tags = Tag::model()->suggestTags($keyword);
            if ($tags !== array())
                echo implode("\n", $tags);
        }
    }

    public function actionUpload() {

        Yii::log('upload', 'info', 'application');
    }

    public function actionIndex($category = null) {
        $provider = new News('search');
        if (isset($category)) {
            $this->pageName = Yii::t('NewsModule.default', 'MODULE_NAME');
            $this->breadcrumbs = array(
                $this->pageName => $this->module->info['homeUrl'],
                $provider->setCategory($category)->name
            );
        } else {
            $this->pageName = Yii::t('NewsModule.default', 'MODULE_NAME');
            $this->breadcrumbs = array($this->pageName);
        }

        $this->render('index', array(
            'provider' => $provider,
        ));
    }

    public function actionView($seo_alias) {
        $this->pageName = Yii::t('NewsModule.default', 'MODULE_NAME');
        $model = News::model()

                //->language(Yii::app()->language->active->id)
                ->withUrl($seo_alias)
                       ->published()     
                ->find(array('limit' => 1));


        if (!$model)
            throw new CHttpException(404);

        $model->saveCounters(array('views' => 1));

        $this->pageTitle = ($model->seo_title) ? $model->seo_title : $model->title;
        $this->pageKeywords = $model->seo_keywords;
        $this->pageDescription = $model->seo_description;
        $category = $model->setCategory($model->category_id);
        if (isset($category)) {
            $this->breadcrumbs = array(
                $this->pageName => $this->module->info['homeUrl'],
                $category->name => array('/news', 'category' => $category->seo_alias),
                $model->title
            );
        } else {
            $this->breadcrumbs = array(
                $this->pageName => $this->module->info['baseUrl'],
                $model->title
            );
        }

        $this->render('view', array(
            'model' => $model,
        ));
    }

}