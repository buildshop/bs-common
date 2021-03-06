<?php

Yii::import('mod.discounts.models.ShopDiscount');
//Yii::import('application.modules.discounts.DiscountsModule');

/**
 * Product discount behavior
 *
 * @var $owner ShopProduct
 */
class DiscountBehavior extends CActiveRecordBehavior {

    /**
     * @var mixed|null|Discount
     */
    public $appliedDiscount = null;

    /**
     * @var float product price before discount applied
     */
    public $originalPrice;
    public $discountPrice;
    public $discountSum;
    /**
     * @var null
     */
    public static $discounts = null;

    /**
     * Attach behavior to model
     * @param $owner
     */
    public function attach($owner) {
        if (!$owner->isNewRecord && Yii::app()->controller instanceof Controller) {
            if (DiscountBehavior::$discounts === null) {
                DiscountBehavior::$discounts = ShopDiscount::model()
                        ->activeOnly()
                        ->applyDate()
                        ->findAll();
            }

            parent::attach($owner);
        }
    }

    /**
     * After find event
     */
    public function afterFind($event) {
        if ($this->appliedDiscount !== null)
            return;

        $user = Yii::app()->user;

        // Personal product discount
        if (!empty($this->owner->discount)) {
            $discount = new ShopDiscount();
            $discount->name = Yii::t('core', 'Скидка');
            $discount->sum = $this->owner->discount;
            $this->applyDiscount($discount);
        }

        // Process discount rules
        if (!$this->hasDiscount()) {
            foreach (DiscountBehavior::$discounts as $discount) {
                $apply = false;

                // Validate category
                if ($this->searchArray($discount->categories, $this->ownerCategories)) {
                    $apply = true;

                    // Validate manufacturer
                    if (!empty($discount->manufacturers))
                        $apply = in_array($this->owner->manufacturer_id, $discount->manufacturers);

                    // Apply discount by user role. Discount for admin disabled.
                    if (!empty($discount->userRoles) && $user->checkAccess('Admin') !== true) {
                        $apply = false;

                        foreach ($discount->userRoles as $role) {
                            if ($user->checkAccess($role)) {
                                $apply = true;
                                break;
                            }
                        }
                    }
                }

                if ($apply === true)
                    $this->applyDiscount($discount);
            }
        }

        // Personal discount for users.
        if (!$user->isGuest && !empty($user->model->discount) && !$this->hasDiscount()) {
            $discount = new ShopDiscount();
            $discount->name = Yii::t('core', 'Персональная скидка');
            $discount->sum = $user->model->discount;
            $this->applyDiscount($discount);
        }
    }

    /**
     * Apply discount to product and decrease its price
     * @param Discount $discount
     */
    protected function applyDiscount(ShopDiscount $discount) {
        //if(Yii::app()->getModule('shop')->discount){
        if ($this->appliedDiscount === null) {
            $sum = $discount->sum;
            if ('%' === substr($discount->sum, -1, 1))
                $sum = $this->owner->price * (int) $sum / 100;

            $this->originalPrice = $this->owner->price;
            $this->discountPrice = $this->owner->price - $sum;
            $this->discountSum = $discount->sum;
            //$this->owner->price -= $sum;
            $this->appliedDiscount = $discount;
        }
        //}else{
        //      $this->appliedDiscount = null;
       // }
    }

    /**
     * Search value from $a in $b
     * @param array $a
     * @param array $b
     * @return array
     */
    protected function searchArray(array $a, array $b) {
        foreach ($a as $v)
            if (in_array($v, $b))
                return true;
        return false;
    }

    /**
     * @return array
     */
    public function getOwnerCategories() {
        $id = 'discount_product_categories' . $this->owner->date_update;
        $data = Yii::app()->cache->get($id);

        if ($data === false) {
            $data = Html::listData($this->owner->categories, 'id', 'id');
            Yii::app()->cache->set($id, $data,(int)Yii::app()->settings->get('core','cache_time'));
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function hasDiscount() {
        return !($this->appliedDiscount === null);
    }

}
