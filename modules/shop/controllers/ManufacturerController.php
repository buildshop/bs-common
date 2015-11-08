<?php

class ManufacturerController extends Controller {

    /**
     * @var ShopManufacturer
     */
    public $model;

    /**
     * @var array
     */
    public $allowedPageLimit;

    /**
     * Sets page limits
     *
     * @return bool
     */
    public function beforeAction($action) {
        $this->allowedPageLimit = explode(',', Yii::app()->settings->get('shop', 'per_page'));
        return true;
    }

    /**
     * Display products by manufacturer
     *
     * @param $seo_alias
     * @throws CHttpException
     */
    public function actionIndex($seo_alias) {
        $this->model = ShopManufacturer::model()->findByAttributes(array('seo_alias' => $seo_alias));

        if (!$this->model)
            throw new CHttpException(404, Yii::t('ShopModule.admin', 'NO_FOUND_BRAND'));

        $this->pageTitle = ($this->model->seo_title) ? $this->model->seo_title : $this->model->name;
        $this->pageKeywords = $this->model->seo_keywords;
        $this->pageDescription = $this->model->seo_description;
        $query = new ShopProduct(null);
        $query->attachBehaviors($query->behaviors());
        $query->active();
        $query->applyManufacturers($this->model->id);

        $provider = new ActiveDataProvider($query, array(
                    'id' => false,
                    'pagination' => array(
                        'pageSize' => $this->allowedPageLimit[0],
                    )
                ));

        $this->render('index', array(
            'provider' => $provider,
        ));
    }

}
