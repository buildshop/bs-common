<?php

/**
 * Display product view page.
 */
class ProductController extends Controller {

    public function getConfig() {
        return Yii::app()->settings->get('shop');
    }

    /**
     * @var ShopProduct
     */
    public $model;

    /**
     * Display product
     * @param string $url product url
     */
    public function actionView($seo_alias) {
        $this->_loadModel($seo_alias);

        $this->registerSessionViews($this->model->id);



        Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl . '/product.view.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl . '/product.view.configurations.js', CClientScript::POS_END);


        if ($this->model->mainCategory) {
            $ancestors = $this->model->mainCategory->excludeRoot()->ancestors()->findAll();
            // $this->breadcrumbs = array(Yii::t('ShopModule.default', 'BC_SHOP') => '/shop');
            foreach ($ancestors as $c) {
                $this->breadcrumbs[$c->name] = $c->getViewUrl();
            }
            // 
            // Do not add root category to breadcrumbs
            if ($this->model->mainCategory->id != 1) {
                //$bc[$this->model->mainCategory->name]=$this->model->mainCategory->getViewUrl();

                $this->breadcrumbs[$this->model->mainCategory->name] = $this->model->mainCategory->getViewUrl();
            }
            $this->breadcrumbs[] = $this->model->name;
        }

        $this->pageKeywords = $this->model->keywords();
        $this->pageDescription = $this->model->description();
        $this->pageTitle = $this->model->title();
        $this->render('view', array('model' => $this->model));
    }

    public function registerSessionViews($id = null) {
        if ($id) {
            $session = Yii::app()->session;
            $date = new DateTime('now', new DateTimeZone($this->timezone));
            $now = strtotime($date->format('Y-m-d H:i:s'));
            $session->timeout = $now + (int) Yii::app()->settings->get('core', 'session_time');
            $views = $session->get('views');
            if (empty($views)) {
                $session['views'] = array();
            }
            if (isset($views)) {
                if (!in_array($id, $views)) {
                    array_push($_SESSION['views'], $id);
                }
            }
        }
    }

    /**
     * Load ShopProduct model by url
     * @param $url
     * @return ShopProduct
     * @throws CHttpException
     */
    protected function _loadModel($seo_alias) {
        $this->model = ShopProduct::model()
                ->active()
                ->withUrl($seo_alias)
                ->find();

        if (!$this->model)
            throw new CHttpException(404, Yii::t('ShopModule.default', 'ERROR_PRODUCT_NOTFOUND'));

        $this->model->saveCounters(array('views' => 1));
        return $this->model;
    }

    /**
     * Get data to render dropdowns for configurable product.
     * Used on product view.
     * array(
     *      'attributes' // Array of ShopAttribute models used for configurations
     *      'prices'     // Key/value array with configurations prices array(product_id=>price)
     *      'data'       // Array to render dropdowns. array(color=>array('Green'=>'1/3/5/', 'Silver'=>'7/'))
     * )
     * @todo Optimize. Cache queries.
     * @return array
     */
    public function getConfigurableData() {
        $attributeModels = ShopAttribute::model()->cache($this->cacheTime)->findAllByPk($this->model->configurable_attributes);
        $models = ShopProduct::model()->cache($this->cacheTime)->findAllByPk($this->model->configurations);

        $data = array();
        $prices = array();
        foreach ($attributeModels as $attr) {
            foreach ($models as $m) {
                $prices[$m->id] = $m->price;
                if (!isset($data[$attr->name]))
                    $data[$attr->name] = array('---' => '0');

                $method = 'eav_' . $attr->name;
                $value = $m->$method;

                if (!isset($data[$attr->name][$value]))
                    $data[$attr->name][$value] = '';

                $data[$attr->name][$value] .= $m->id . '/';
            }
        }

        return array(
            'attributes' => $attributeModels,
            'prices' => $prices,
            'data' => $data,
        );
    }

}