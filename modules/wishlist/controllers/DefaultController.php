<?php

Yii::import('mod.wishlist.components.WishListComponent');

/**
 * Display products added to wish list
 */
class DefaultController extends Controller {

    /**
     * @var ShopWishlist
     */
    public $model;

    /**
     * @param CAction $action
     * @return bool
     * @throws CHttpException
     */
    public function beforeAction($action) {
        if (Yii::app()->user->isGuest && $this->action->id !== 'view') {
            Yii::app()->user->returnUrl = Yii::app()->request->getUrl();
            if (Yii::app()->request->isAjaxRequest)
                throw new CHttpException(302);
            else
                $this->redirect(Yii::app()->user->loginUrl);
        }

        $this->model = new WishListComponent();
        return true;
    }

    /**
     * Render index view
     */
    public function actionIndex() {
        $this->pageTitle = Yii::t('WishlistModule.default', 'MODULE_NAME');
        $this->render('index');
    }

    /**
     * Add product to wish list
     * @param $id ShopProduct id
     */
    public function actionAdd($id) {
        $this->model->add($id);
        $message = Yii::t('ShopModule.core', 'Продукт успешно добавлен в список желаний.');
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
     * @param $key
     * @throws CHttpException
     */
    public function actionView($key) {
        try {
            $this->model->loadByKey($key);
        } catch (CException $e) {
            throw new CHttpException(404, Yii::t('ShopModule.core', 'Ошибка. По вашему запросу ничего не найдено.'));
        }

        $this->render('index');
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
