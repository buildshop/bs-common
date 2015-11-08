<?php

/**
 * Контроллер статичных страниц
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.pages.controllers
 * @uses Controller
 */
class DefaultController extends Controller {

    public function actionIndex($url) {
        $model = Page::model()
                ->published()
                ->withUrl($url)
                ->find(array(
            'limit' => 1
                ));
        if (!$model)
            throw new CHttpException(404);

        $this->pageTitle = ($model->seo_title) ? $model->seo_title : $model->title;
        $this->pageKeywords = $model->seo_keywords;
        $this->pageDescription = $model->seo_description;

        $this->breadcrumbs = array($model->title);
        $model->saveCounters(array('views' => 1));
        $this->render('view', array(
            'model' => $model,
        ));
    }

}