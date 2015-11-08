<?php

Yii::import('mod.shop.models.ShopProduct');
Yii::import('mod.shop.models.ShopAttribute');

/**
 * Handle compare lists
 */
class CompareProductsComponent extends CComponent {
    /**
     * Max products to compare
     */

    const MAX_PRODUCTS = 20;

    /**
     * @var string
     */
    public $sessionKey = 'CompareProductsComponent';

    /**
     * @var CHttpSession
     */
    public $session;

    /**
     * @var array
     */
    private $_products;

    /**
     * @var array
     */
    private $_attributes;

    /**
     * Initialize component
     */
    public function __construct() {
        $this->session = Yii::app()->session;

        if (!isset($this->session[$this->sessionKey]) || !is_array($this->session[$this->sessionKey]))
            $this->session[$this->sessionKey] = array();
    }

    /**
     * Check if product exists add to list
     * @param string $id product id
     */
    public function add($id) {
        if ($this->count() <= self::MAX_PRODUCTS && ShopProduct::model()
                        ->active()
                        ->countByAttributes(array('id' => $id)) > 0) {
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
        if (isset($current[$id]))
            unset($current[$id]);
        $this->setIds($current);
    }

    /**
     * @return array of product id added to compare
     */
    public function getIds() {
        return $this->session[$this->sessionKey];
    }

    /**
     * @param array $ids
     */
    public function setIds(array $ids) {
        $this->session[$this->sessionKey] = array_unique($ids);
    }

    /**
     * Clear compare list
     */
    public function clear() {
        $this->setIds(array());
    }

    /**
     * @return array of ShopProduct models to compare
     */
    public function getProducts() {
        if ($this->_products === null)
            $this->_products = ShopProduct::model()
                    ->findAllByPk(array_values($this->getIds()));
        return $this->_products;
    }

    /**
     * Count products to compare
     * @return int
     */
    public function count() {
        return sizeof($this->getIds());
    }

    /**
     * Count user compare items without creating new instance
     * @static
     * @return int
     */
    public static function countSession() {
        return sizeof(Yii::app()->session['CompareProductsComponent']);
    }

    /**
     * Load ShopAttribute models by names
     * @return array of ShopAttribute models
     */
    public function getAttributes() {
        if ($this->_attributes === null) {
            $this->_attributes = array();
            $names = array();
            foreach ($this->getProducts() as $p)
                $names = array_merge($names, array_keys($p->getEavAttributes()));

            $cr = new CDbCriteria;
            $cr->addInCondition('t.name', $names);
            $query = ShopAttribute::model()
                    ->displayOnFront()
                    ->useInCompare()
                    ->findAll($cr);

            foreach ($query as $m)
                $this->_attributes[$m->name] = $m;
        }
        return $this->_attributes;
    }

}
