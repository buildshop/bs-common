<?php

Yii::import('mod.shop.models.ShopProduct');
//Yii::import('application.modules.shop.models.ShopWishlist');
//Yii::import('application.modules.shop.models.ShopWishlistProducts');

/**
 * Handle user wish list
 */
class WishListComponent extends CComponent {
    /**
     * Max products can be added to wish list
     */

    const MAX_PRODUCTS = 50;

    /**
     * @var array if products id
     */
    private $_products;

    /**
     * @var ShopWishlist
     */
    private $_model;

    /**
     * @var null
     */
    private $_user_id;

    /**
     * @param mixed $user_id
     */
    public function __construct($user_id = null) {
        if ($user_id)
            $this->_user_id = $user_id;
        else
            $this->_user_id = Yii::app()->user->id;
    }

    /**
     * Check if product exists add to list
     * @param string $id product id
     * @return boolean
     */
    public function add($id) {
        if ($this->count() <= self::MAX_PRODUCTS && ShopProduct::model()->active()->countByAttributes(array('id' => $id)) > 0) {
            $current = $this->getIds();
            $current[(int) $id] = (int) $id;
            $this->setIds($current);
            return true;
        }
        return false;
    }

    /**
     * Remove product from list
     * @param $id
     */
    public function remove($id) {
        $current = $this->getIds();
        $pos = array_search($id, $current);
        if (isset($current[$pos]))
            unset($current[$pos]);
        $this->setIds($current);
    }

    /**
     * @return array of product id added to wishlist
     */
    public function getIds() {
        $model = $this->getModel();

        if ($model)
            return $model->getIds();

        return array();
    }

    /**
     * @param array $ids
     */
    public function setIds(array $ids) {
        $ids = array_unique($ids);
        $this->getModel()->setIds($ids);
    }

    /**
     * Clear compare list
     */
    public function clear() {
        $this->setIds(array());
    }

    /**
     * Get and/or create user wishlist
     * @return Wishlist
     */
    public function getModel() {
        if ($this->_model === null) {
            $model = Wishlist::model()->findByAttributes(array(
                'user_id' => $this->getUserId()
                    ));
            if ($model === null)
                $model = Wishlist::model()->create($this->getUserId());
            $this->_model = $model;
        }
        return $this->_model;
    }

    /**
     * @return array of ShopProduct models to wish list
     */
    public function getProducts() {
        if ($this->_products === null)
            $this->_products = ShopProduct::model()->findAllByPk(array_values($this->getIds()));
        return $this->_products;
    }

    /**
     * Get current user id
     * @return mixed
     */
    public function getUserId() {
        return $this->_user_id;
    }

    /**
     * @return string
     */
    public function getPublicLink() {
        return Yii::app()->createAbsoluteUrl('/wishlist/default/view', array('key' => $this->getModel()->key));
    }

    /**
     * @param $key
     * @return CActiveRecord
     * @throws CException
     */
    public function loadByKey($key) {
        $model = Wishlist::model()->findByAttributes(array(
            'key' => $key,
                ));
        if ($model === null)
            throw new CException();
        $this->_model = $model;
        $this->_user_id = $model->user_id;
        return $model;
    }

    /**
     * Count products added to wish list
     * @return int
     */
    public function count() {
        return sizeof($this->getIds());
    }

}
