<?php

Yii::import('mod.shop.models.ShopAttributeOptionTranslate');

/**
 * Shop options for dropdown and multiple select
 * This is the model class for table "ShopAttributeOptions".
 *
 * The followings are the available columns in table 'ShopAttributeOptions':
 * @property integer $id
 * @property integer $attribute_id
 * @property string $value
 * @property integer $position
 */
class ShopAttributeOption extends ActiveRecord {

    public $translateModelName = 'ShopAttributeOptionTranslate';

    /**
     * @var string multilingual attr
     */
    public $value;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CActiveRecord the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{shop_attribute_option}}';
    }

    public function relations() {
        return array(
            'option_translate' => array(self::HAS_ONE, $this->translateModelName, 'object_id')
        );
    }

    /**
     * @return array
     */
    public function behaviors() {
        return array(
            'TranslateBehavior' => array(
                'class' => 'app.behaviors.TranslateBehavior',
                'relationName' => 'option_translate',
                'translateAttributes' => array(
                    'value',
                ),
            )
        );
    }

}