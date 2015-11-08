<?php

/**
 * Display category products
 * TODO: Add default sorting by rating, etc...
 *
 * @property $activeAttributes
 * @property $eavAttributes
 */
class CategoryController extends Controller {

    public function getConfig() {
        return Yii::app()->settings->get('shop');
    }

    /**
     * @var ShopProduct
     */
    public $query;

    /**
     * @var ShopCategory
     */
    public $model;

    /**
     * @var array Eav attributes used in http query
     */
    private $_eavAttributes;

    /**
     * @var array
     */
    public $allowedPageLimit = array();

    /**
     * Current query clone to use in min/max price queries
     * @var CDbCriteria
     */
    public $currentQuery;

    /**
     * @var ActiveDataProvider
     */
    public $provider;

    /**
     * @var string
     */
    private $_minPrice;

    /**
     * @var string
     */
    private $_maxPrice;

    /**
     * Load category model by url
     *
     * @param $action
     * @return bool
     */
    public function beforeAction($action) {
        $this->allowedPageLimit = explode(',', Yii::app()->settings->get('shop', 'per_page'));

        if (Yii::app()->request->getPost('min_price') || Yii::app()->request->getPost('max_price')) {
            $data = array();
            if (Yii::app()->request->getPost('min_price'))
                $data['min_price'] = (int) Yii::app()->request->getPost('min_price');
            if (Yii::app()->request->getPost('max_price'))
                $data['max_price'] = (int) Yii::app()->request->getPost('max_price');

            if ($this->action->id === 'search') {
                $this->redirect(Yii::app()->request->addUrlParam('/shop/category/search', $data));
            } else {
                if (!Yii::app()->request->isAjaxRequest)
                    $this->redirect(Yii::app()->request->addUrlParam('/shop/category/view', $data));
            }
        }

        return true;
    }

    /**
     * Display category products
     */
    public function actionView() {
        $this->model = $this->_loadModel(Yii::app()->request->getQuery('seo_alias'));
        $this->doSearch($this->model, 'view');
    }

    /**
     * Search products
     */
    public function actionSearch() {
        if (Yii::app()->request->isPostRequest)
            $this->redirect(Yii::app()->request->addUrlParam('/shop/category/search', array('q' => Yii::app()->request->getPost('q'))));
        $q = Yii::app()->request->getQuery('q');
        if (!$q) {
            $this->render('search');
        }
        $this->pageTitle = Yii::t('ShopModule.core', 'Поиск');
        $this->breadcrumbs[] = $this->pageTitle;
        $this->doSearch($q, 'search');
    }

    /**
     * Search products
     * @param $data ShopCategory|string
     * @param string $view
     */
    public function doSearch($data, $view) {
        $this->query = new ShopProduct(null);

        $this->query->attachBehaviors($this->query->behaviors());
        $this->query->applyAttributes($this->activeAttributes)->active();

        if ($data instanceof ShopCategory) {
            $this->query->applyCategories($this->model);
        } else {
            $cr = new CDbCriteria;
            $cr->with = array(
                'translate' => array('together' => true),
            );
            $cr->addSearchCondition('translate.name', $data);
            $this->query->getDbCriteria()->mergeWith($cr);
        }

        // Filter by manufacturer
        if (Yii::app()->request->getQuery('manufacturer')) {
            $manufacturers = explode(',', Yii::app()->request->getParam('manufacturer', '')); // ;
            $this->query->applyManufacturers($manufacturers);
        }

        // Create clone of the current query to use later to get min and max prices.
        $this->currentQuery = clone $this->query->getDbCriteria();

        // Filter products by price range if we have min_price or max_price in request
        $this->applyPricesFilter();

        $per_page = $this->allowedPageLimit[0];
        if (isset($_GET['per_page']) && in_array((int) $_GET['per_page'], $this->allowedPageLimit))
            $per_page = (int) $_GET['per_page'];

        $this->provider = new ActiveDataProvider($this->query, array(
                    // Set id to false to not display model name in
                    // sort and page params
                    'id' => false,
                    'pagination' => array(
                        'pageSize' => $per_page,
                    )
                ));

        $this->provider->sort = ShopProduct::getCSort();
        if ($view != 'search') {

            $this->pageKeywords = $this->model->keywords();
            $this->pageDescription = $this->model->description();
            $this->pageTitle = $this->model->title();
// Create breadcrumbs
            $ancestors = $this->model->cache($this->cacheTime)->excludeRoot()->ancestors()->findAll();
            //  $this->breadcrumbs = array(Yii::t('ShopModule.default', 'BC_SHOP') => '/shop');
            foreach ($ancestors as $c)
                $this->breadcrumbs[$c->name] = $c->getViewUrl();

            $this->breadcrumbs[] = $this->model->name;
        }
        if (isset($_GET['view'])) {
            if ($_GET['view'] == 'list') {
                $itemView = '_view_list';
            } elseif ($_GET['view'] == 'table') {
                $itemView = '_view_table';
            } else {
                $itemView = '_view_grid';
            }
        } else {
            $itemView = '_view_grid';
        }
        $this->render($view, array(
            'provider' => $this->provider,
            'itemView' => $itemView
        ));
    }

    /**
     * @return array of attributes used in http query and available in category
     */
    public function getActiveAttributes() {
        $data = array();

        foreach (array_keys($_GET) as $key) {
            if (array_key_exists($key, $this->eavAttributes)) {
                if ((boolean) $this->eavAttributes[$key]->select_many === true)
                    $data[$key] = explode(',', $_GET[$key]); // ;
                else
                    $data[$key] = array($_GET[$key]);
            }
        }

        return $data;
    }

    /**
     * @return array of available attributes in category
     */
    public function getEavAttributes() {
        if (is_array($this->_eavAttributes))
            return $this->_eavAttributes;

        // Find category types
        $model = new ShopProduct(null);
        $criteria = $model
                ->cache($this->cacheTime)
                ->applyCategories($this->model)
                ->active()
                ->getDbCriteria();

        unset($model);

        $builder = new CDbCommandBuilder(Yii::app()->db->getSchema());

        $criteria->select = 'type_id';
        $criteria->group = 'type_id';
        $criteria->distinct = true;
        $typesUsed = $builder->createFindCommand(ShopProduct::model()->tableName(), $criteria)->queryColumn();

        // Find attributes by type
        $criteria = new CDbCriteria;
        $criteria->addInCondition('types.type_id', $typesUsed);
        $query = ShopAttribute::model()
                ->cache($this->cacheTime)
                ->useInFilter()
                ->with(array('types', 'options'))
                ->findAll($criteria);

        $this->_eavAttributes = array();
        foreach ($query as $attr)
            $this->_eavAttributes[$attr->name] = $attr;
        return $this->_eavAttributes;
    }

    /**
     * @return string min price
     */
    public function getMinPrice() {
        if ($this->_minPrice !== null)
            return $this->_minPrice;
        $this->_minPrice = $this->aggregatePrice('MIN');
        return $this->_minPrice;
    }

    /**
     * @return string max price
     */
    public function getMaxPrice() {
        $this->_maxPrice = $this->aggregatePrice('MAX');
        return $this->_maxPrice;
    }

    /**
     * @param string $function
     * @return mixed
     */
    public function aggregatePrice($function = 'MIN') {
        $query = clone $this->currentQuery;
        $query->select = $function . '(t.price) as aggregation_price';
        $query->limit = 1;
        $query->order = ($function === 'MIN') ? 't.price' : 't.price DESC';
        $result = ShopProduct::model()->cache($this->cacheTime);
        $result->getDbCriteria()->mergeWith($query);
        $r = $result->find();
        if ($r) {
            return $r->aggregation_price;
        }
        return null;
    }

    public function applyPricesFilter() {
        $minPrice = Yii::app()->request->getQuery('min_price');
        $maxPrice = Yii::app()->request->getQuery('max_price');

        $cm = Yii::app()->currency;
        if ($cm->active->id !== $cm->main->id && ($minPrice > 0 || $maxPrice > 0)) {
            $minPrice = $cm->activeToMain($minPrice);
            $maxPrice = $cm->activeToMain($maxPrice);
        }

        if ($minPrice > 0)
            $this->query->applyMinPrice($minPrice);
        if ($maxPrice > 0)
            $this->query->applyMaxPrice($maxPrice);
    }

    /**
     * Load category by url
     * @param $url
     * @return ShopCategory
     * @throws CHttpException
     */
    public function _loadModel($url) {
        // Find category
        $model = ShopCategory::model()
                ->cache($this->cacheTime)
                ->excludeRoot()
                ->withFullPath($url)
                ->find();

        if (!$model)
            throw new CHttpException(404, Yii::t('ShopModule.default', 'NOFIND_CATEGORY'));

        return $model;
    }

}
