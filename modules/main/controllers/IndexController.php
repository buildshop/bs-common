<?php
  //  Yii::import('shop.models.ShopProduct');
   //     Yii::import('shop.ShopModule');
class IndexController extends Controller {

    
    /*
public function actionToster() {
        $mk = microtime(true);
        for ($i = 0; $i < 10000; $i++) {
            $this->test1();
        }
        echo microtime(true) - $mk, '<br/>';


}

public function test1(){

        $address = new ShopProduct(null);
        $address->name = 3423423;

}*/
    
    
    
    
    
    
    
    public $layout = '//layouts/main';

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
     * Display start page
     */
    public function _loadModel($id) {
        // Find category
        if (Yii::app()->hasModule('shop')) {
            $model = ShopCategory::model()
                    ->excludeRoot()
                    // ->withFullPath($url)
                    ->findByPk($id);

            if (!$model)
                throw new CHttpException(404, Yii::t('ShopModule.default', 'NOFIND_CATEGORY'));

            return $model;
        }
    }

    public function actionIndex() {
        if($_GET['test']){
            header('Content-Type: application/json');
            echo CJSON::encode(array('gagag'=>2));
            die;
        }
        $this->breadcrumbs = array(Yii::t('zii', 'Home'));
        $this->render('index', array());
    }

    public function actionTest($layout) {
        $this->breadcrumbs = array(Yii::t('zii', 'Home'));
        if ($layout) {
            $this->layout = $layout;
        } else {
            throw new CHttpException(404);
        }
        $this->render('index', array());
    }


}