<?php


/**
 * Compare products controller
 */
class DefaultController extends Controller {

    /**
     * @var CompareProductsComponent
     */
    public $model;

    public function beforeAction($action) {
        $this->model = new CompareProductsComponent;
        return true;
    }

    /**
     * @var array
     */
    protected $attributes = array();

    /**
     * Render index view
     */
    public function actionIndex() {
        $this->pageTitle = Yii::t('ShopModule.core', 'Сравнение продуктов');
        $this->render('index');
    }

    /**
     * Add product to compare list
     * @param $id ShopProduct id
     */
    public function actionAdd($id) {
        $this->model->add($id);
        $message = Yii::t('ShopModule.core', 'Продукт успешно добавлен в список сравнения.');
        $this->addFlashMessage($message);
        if (!Yii::app()->request->isAjaxRequest){
            $this->redirect($this->createUrl('index'));
        }else{
            echo CJSON::encode(array(
                'message'=>$message,
                'count'=>$this->model->count()
                ));
        }
    }

    /**
     * Remove product from list
     * @param string $id product id
     */
    public function actionRemove($id) {
        $this->model->remove($id);
        if (!Yii::app()->request->isAjaxRequest)
            $this->redirect($this->createUrl('index'));
    }

}
