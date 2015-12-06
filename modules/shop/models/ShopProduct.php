<?php

//Yii::import('mod.shop.ShopModule');
Yii::import('mod.shop.models.ShopProductTranslate');
Yii::import('mod.shop.models.ShopProductCategoryRef');
Yii::import('mod.shop.models.ShopProductImage');
Yii::import('mod.shop.models.components.ShopProductImageSaver');
Yii::import('app.traits.ImageUrl');

/**
 * This is the model class for table "ShopProduct".
 *
 * The followings are the available columns in table 'ShopProduct':
 * @property integer $id
 * @property integer $manufacturer_id
 * @property boolean $use_configurations
 * @property array $configurations array of product pks
 * @property array $configurable_attributes array of ShopAttribute pks used to configure product
 * @property integer $type_id
 * @property string $name
 * @property string $seo_alias
 * @property float $price Product price. For configurable product its min_price
 * @property float $max_price for configurable products. Used in ShopProduct::priceRange to display prices on category view
 * @property boolean $switch
 * @property string $short_description
 * @property string $full_description
 * @property string $seo_title
 * @property string $seo_description
 * @property string $seo_keywords
 * @property string $sku
 * @property string $quantity
 * @property string $auto_decrease_quantity
 * @property string $availability
 * @property integer $views
 * @property integer $added_to_cart_count
 * @property string $date_create
 * @property string $date_update
 * @property integer $votes 
 * @property integer $rating
 * @property integer $score
 * @property string $discount
 * @method ShopProduct active() Find Only active products
 * @method ShopProduct newest() Order products by creating date
 * @method ShopProduct byViews() Order by views count
 * @method ShopProduct byAddedToCart() Order by views count
 * @method ShopProduct withEavAttributes
 */
class ShopProduct extends ActiveRecord {

    use ImageUrl;

    /**
     * @var null Id if product to exclude from search
     */
    public $exclude = null;

    /**
     * @var array of related products
     */
    private $_related;

    /**
     * @var array of attributes used to configure product
     */
    private $_configurable_attributes;
    private $_configurable_attribute_changed = false;

    /**
     * @var array
     */
    private $_configurations;

    /**
     * @var string
     */
    public $translateModelName = 'ShopProductTranslate';

    /**
     * Multilingual attrs
     */
    public $name;
    public $short_description;
    public $full_description;
    public $seo_title;
    public $seo_description;
    public $seo_keywords;
    public $image;
    public $quantity = 1; //default value 
    /**
     * @var float min/max price
     */
    public $aggregation_price;

    /**
     * @var integer used only to render admin form
     */
    public $main_category_id;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return ShopProduct the static model class
     */
    const MODULE_ID = 'shop';
    const route = '/shop/admin/products';

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{shop_product}}';
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    public function getAutoSku(){
        $pack = Yii::app()->package->value;
        return 'BS'.$pack->shop[0]['id'].'-'.sprintf("%07d",$this->id);
    }
    public function getProductLabel() {
        $result = array();
        $result['label'] = Yii::t('app', 'PRODUCT_LABEL', $this->label);
        switch ($this->label) {
            case 1:
                $result['class'] = 'new';
                break;
            case 2:
                $result['class'] = 'hit';
                break;
            case 3:
                $result['class'] = 'discount';
                break;
            default:
                $result = false;
        }
        return $result;
    }

    public function getGridColumns() {
        return array(
            array(
                'name' => 'image',
                'type' => 'html',
                'htmlOptions' => array('class' => 'image'),
                'filter' => false,
                'value' => '(!empty($data->mainImage))?Html::link(Html::image($data->mainImage->getUrl("50x50"),""),$data->mainImage->getUrl("500x500")):"no image"'
            ),
            array(
                'name' => 'name',
                'type' => 'raw',
                'value' => 'Html::link(Html::encode($data->name), array("/shop/admin/products/update", "id"=>$data->id))',
                'htmlOptions' => array('class' => 'text-left'),
            ),
            array(
                'name' => 'manufacturer_id',
                'type' => 'raw',
                'htmlOptions' => array('class' => 'text-center'),
                'value' => '$data->manufacturer ? Html::link(Html::encode($data->manufacturer->name), $data->manufacturer->getUpdateUrl()) : ""',
                'filter' => Html::listData(ShopManufacturer::model()->orderByName()->findAll(), 'id', 'name')
            ),
            array(
                'name' => 'supplier_id',
                'type' => 'raw',
                'value' => '$data->supplier_id ? Html::encode($data->supplier->name) : ""',
                'filter' => Html::listData(ShopSuppliers::model()->findAll(), 'id', 'name')
            ),
            array(
                'type' => 'raw',
                'header' => 'Категория/и',
                'name' => 'main_category_id',
                'htmlOptions' => array('style' => 'width:100px'),
                'value' => '$data->getCategories()',
                'filter' => false
            ),
            array(
                'name' => 'switch',
                'filter' => array(1 => Yii::t('app', 'Показанные'), 0 => Yii::t('app', 'Скрытые')),
                'value' => '$data->switch ? Yii::t("app", "Показан") : Yii::t("app", "Скрыт")'
            ),
            array('name' => 'price', 'value' => '$data->price'),
            array('name' => 'sku', 'value' => '$data->sku'),
            array('name' => 'pcs', 'value' => '$data->pcs'),
            array('name' => 'date_create', 'value' => 'CMS::date($data->date_create)'),
            array('name' => 'date_update', 'value' => '$data->date_update'),
            array('name' => 'quantity', 'value' => '$data->quantity'),
            'DEFAULT_CONTROL' => array(
                'class' => 'ButtonColumn',
                'group' => false,
                'template' => '{switch}{update}{delete}',
            ),
            'DEFAULT_COLUMNS' => array(
                array('class' => 'CCheckBoxColumn'),
                array('class' => 'HandleColumn')
            ),
        );
    }

    public function getForm() {
        Yii::import('zii.widgets.jui.CJuiDatePicker');
        Yii::app()->controller->widget('ext.tinymce.TinymceWidget');
        return array(
            'showErrorSummary' => true,
            'attributes' => array(
                'enctype' => 'multipart/form-data',
                'class' => 'form-horizontal',
                'id' => __CLASS__
            ),
            'elements' => array(
                'content' => array(
                    'type' => 'form',
                    'title' => $this->t('TAB_GENERAL'),
                    'elements' => array(
                        'name' => array(
                            'type' => 'text', 'id' => 'title',
                            'visible' => Yii::app()->settings->get('shop', 'auto_gen_url') ? false : true
                        ),
                        'seo_alias' => array(
                            'type' => 'text', 'id' => 'alias',
                            'visible' => (Yii::app()->settings->get('shop', 'auto_gen_url') || Yii::app()->settings->get('core', 'translate_object_url')) ? false : true
                        ),
                        'sku' => array(
                            'type' => 'text',
                            'afterField' => '<span class="fieldIcon icon-barcode "></span>'
                        ),
                        'price' => array(
                            'type' => $this->use_configurations ? 'hidden' : 'text',
                            'afterField' => '<span class="fieldIcon icon-coin"></span>'
                        ),
                        'price_purchase' => array(
                            'type' => 'text',
                            'afterField' => '<span class="fieldIcon icon-coin"></span>'
                        ),
                        'pcs' => array(
                            'type' => 'text',
                            'visible' => Yii::app()->settings->get('shop', 'wholesale') ? true : false
                        ),
                        'label' => array(
                            'type' => 'dropdownlist',
                            'items' => self::getProductLabels(),
                            'empty' => Yii::t('core', 'EMPTY_DROPDOWNLIST', 1)
                        ),
                        'currency_id' => array(
                            'type' => 'dropdownlist',
                            'items' => Html::listData(ShopCurrency::model()->findAll(array('condition' => '`t`.`default`=:int', 'params' => array(':int' => 0))), 'id', 'name'),
                            'empty' => '&mdash; Не привязывать &mdash;',
                            'visible' => Yii::app()->controller->module->accept_currency
                        ),
                        'main_category_id' => array(
                            'type' => 'dropdownlist',
                            'items' => ShopCategory::flatTree(),
                            'empty' => '---',
                        ),
                        //'dasdsa',
                        'switch' => array(
                            'type' => 'dropdownlist',
                            'items' => array(
                                1 => Yii::t('app', 'YES'),
                                0 => Yii::t('app', 'NO')
                            ),
                            'hint' => $this->t('HINT_SWITCH'),
                        ),
                        'date_create' => array(
                            'type' => 'CJuiDatePicker',
                            'options' => array(
                                'dateFormat' => 'yy-mm-dd ' . date('H:i:s'),
                            ),
                            'afterField' => '<span class="fieldIcon icon-calendar-2"></span>'
                        ),
                        'manufacturer_id' => array(
                            'type' => 'dropdownlist',
                            'items' => Html::listData(ShopManufacturer::model()->findAll(), 'id', 'name'),
                            'empty' => $this->t('EMPTY_MANUFACTURER'),
                        ),
                        'supplier_id' => array(
                            'type' => 'dropdownlist',
                            'items' => Html::listData(ShopSuppliers::model()->findAll(), 'id', 'name'),
                            'empty' => $this->t('EMPTY_SUPPLIERS'),
                            'visible' => Yii::app()->controller->module->supplier
                        ),
                        'short_description' => array(
                            'type' => 'textarea',
                            'class' => 'editor',
                            'hint' => (Yii::app()->settings->get('shop', 'auto_fill_short_desc')) ? Yii::t('ShopModule.admin', 'MODE_ENABLE', array(
                                        '{mode}' => Yii::t('ShopModule.SettingsShopForm', 'AUTO_FILL_SHORT_DESC')
                                    )) : null
                        ),
                        'full_description' => array(
                            'type' => 'textarea',
                            'class' => 'editor'
                        ),
                    ),
                ),
                'warehouse' => array(
                    'type' => 'form',
                    'title' => $this->t('TAB_WAREHOUSE'), //'icon-drawer-3',
                    'elements' => array(
                        'quantity' => array(
                            'type' => 'text',
                        ),
                        'discount' => array(
                            'type' => 'text',
                            'hint' => $this->t('HINT_DISCOUNT'),
                        ),
                        'auto_decrease_quantity' => array(
                            'type' => 'dropdownlist',
                            'items' => array(
                                0 => Yii::t('app', 'NO'),
                                1 => Yii::t('app', 'YES')
                            ),
                            'hint' => $this->t('HINT_AUTO_DECREASE_QUANTITY'),
                        ),
                        'availability' => array(
                            'type' => 'dropdownlist',
                            'items' => $this->getAvailabilityList()
                        ),
                    ),
                ),
                'seo' => array(
                    'type' => 'form',
                    'title' => $this->t('TAB_SEO'), //'icon-globe',
                    'elements' => array(
                        'seo_title' => array(
                            'type' => 'text',
                        ),
                        'seo_keywords' => array(
                            'type' => 'textarea',
                        ),
                        'seo_description' => array(
                            'type' => 'textarea',
                        ),
                    ),
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => ($this->isNewRecord) ? Yii::t('app', 'CREATE', 0) : Yii::t('app', 'SAVE')
                )
            )
        );
    }

    public function scopes() {
        $alias = $this->getTableAlias(true);
        return array(
            'active' => array(
                'condition' => $alias . '.switch=1',
            ),
            'newToDay' => array(
                'condition' => $alias . '.date_create BETWEEN :fr AND :to AND ' . $alias . '.switch=1',
                'params' => array(
                    ':fr' => date('Y-m-d H:i:s', strtotime(date('Y-m-d'))),
                    ':to' => date('Y-m-d H:i:s', strtotime(date('Y-m-d')) + 86400)
                )
            ),
            'newest' => array('order' => $alias . '.date_create DESC'),
            'byViews' => array('order' => $alias . '.views DESC'),
            'byAddedToCart' => array('order' => $alias . '.added_to_cart_count DESC'),
        );
    }

    public static function getCSort() {
        $sort = new CSort;
        $sort->defaultOrder = 't.date_create DESC';
        $sort->attributes = array(
            '*',
            'name' => array(
                'asc' => 'translate.name',
                'desc' => 'translate.name DESC',
            ),
        );

        return $sort;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('price', 'commaToDot'),
            array('price, price_purchase, type_id, pcs, manufacturer_id, main_category_id, supplier_id, currency_id, ordern', 'numerical'),
            array('switch', 'boolean'),
            array('use_configurations', 'boolean', 'on' => 'insert'),
            array('quantity, availability, manufacturer_id, auto_decrease_quantity, label', 'numerical', 'integerOnly' => true),
            array('name, price, seo_alias', 'required'),
            array('seo_alias', 'translitFilter', 'translitAttribute' => 'name'), //Off for import
            array('date_create', 'date', 'format' => 'yyyy-M-d H:m:s'),
            array('name, seo_alias, seo_title, seo_keywords, seo_description, sku', 'length', 'max' => 255),
            array('short_description, full_description, discount', 'type', 'type' => 'string'),
            // Search
            array('id, name, switch, seo_alias, price, sku, short_description, full_description, date_create, date_update, manufacturer_id, ordern, label', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        Yii::import('mod.comments.models.Comments');
        return array(
            'supplier' => array(self::BELONGS_TO, 'ShopSuppliers', 'supplier_id'),
            'images' => array(self::HAS_MANY, 'ShopProductImage', 'product_id'),
            'mainImage' => array(self::HAS_ONE, 'ShopProductImage', 'product_id', 'condition' => 'is_main=1'),
            'imagesNoMain' => array(self::HAS_MANY, 'ShopProductImage', 'product_id', 'condition' => 'is_main=0'),
            'currency' => array(self::BELONGS_TO, 'ShopCurrency', 'currency_id'),
            'manufacturer' => array(self::BELONGS_TO, 'ShopManufacturer', 'manufacturer_id', 'scopes' => 'applyTranslateCriteria'),
            'productsCount' => array(self::STAT, 'ShopProduct', 'manufacturer_id', 'select' => 'count(t.id)'),
            'type' => array(self::BELONGS_TO, 'ShopProductType', 'type_id'),
            //   'typeGet' => array(self::BELONGS_TO, 'ShopProductType', 'type_id', 'condition' => 'typeGet.id=:tid', 'params' => array(':tid' => (int) $_GET['ShopProduct']['type_id'])), //Специально для Добавлние товара.
            'commentsCount' => array(self::STAT, 'Comments', 'object_id', 'condition' => '`t`.`model`="mod.shop.models.ShopProduct"', 'scopes' => 'active'),
            'related' => array(self::HAS_MANY, 'ShopRelatedProduct', 'product_id'),
            'relatedProducts' => array(self::HAS_MANY, 'ShopProduct', array('related_id' => 'id'), 'through' => 'related'),
            'relatedProductCount' => array(self::STAT, 'ShopRelatedProduct', 'product_id'),
            'categorization' => array(self::HAS_MANY, 'ShopProductCategoryRef', 'product'),
            'categories' => array(self::HAS_MANY, 'ShopCategory', array('category' => 'id'), 'through' => 'categorization'),
            'mainCategory' => array(self::HAS_ONE, 'ShopCategory', array('category' => 'id'), 'through' => 'categorization', 'condition' => 'categorization.is_main = 1', 'scopes' => 'applyTranslateCriteria'),
            'translate' => array(self::HAS_ONE, $this->translateModelName, 'object_id'),
            // Product variation
            'variants' => array(self::HAS_MANY, 'ShopProductVariant', array('product_id'), 'with' => array('attribute', 'option'), 'order' => 'option.ordern'),
        );
    }

    /**
     * Find product by seo_alias.
     * Scope.
     * @param string ShopProduct seo_alias
     * @return ShopProduct
     */
    public function withUrl($seo_alias) {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'seo_alias=:url',
            'params' => array(':url' => $seo_alias)
        ));
        return $this;
    }

    /**
     * Find product by label.
     * Scope.
     * @param tinyint ShopProduct label
     * @return ShopProduct
     */
    public function labelStatus($num = 0) {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'label=:label',
            'params' => array(':label' => $num)
        ));
        return $this;
    }

    /**
     * Find product by limit.
     * Scope.
     * @param tinyint ShopProduct limit
     * @return ShopProduct
     */
    public function limited($limit = null) {
        $this->getDbCriteria()->mergeWith(array(
            'limit' => $limit,
        ));
        return $this;
    }

    /**
     * Filter products by category
     * Scope
     * @param ShopCategory|string|array $categories to search products
     * @return ShopProduct
     */
    public function applyCategories($categories, $select = 't.*') {
        if ($categories instanceof ShopCategory)
            $categories = array($categories->id);
        else {
            if (!is_array($categories))
                $categories = array($categories);
        }

        $criteria = new CDbCriteria;

        if ($select)
            $criteria->select = $select;
        $criteria->join = 'LEFT JOIN `{{shop_product_category_ref}}` `categorization` ON (`categorization`.`product`=`t`.`id`)';
        $criteria->addInCondition('categorization.category', $categories);
        $this->getDbCriteria()->mergeWith($criteria);

        return $this;
    }

    /**
     * Filter products by EAV attributes.
     * Example: $model->applyAttributes(array('color'=>'green'))->findAll();
     * Scope
     * @param array $attributes list of allowed attribute models
     * @return ShopProduct
     */
    public function applyAttributes(array $attributes) {
        if (empty($attributes))
            return $this;
        return $this->withEavAttributes($attributes);
    }

    /**
     * Filter product by manufacturers
     * Scope
     * @param string|array $manufacturers
     * @return ShopProduct
     */
    public function applyManufacturers($manufacturers) {
        if (!is_array($manufacturers))
            $manufacturers = array($manufacturers);

        if (empty($manufacturers))
            return $this;

        $criteria = new CDbCriteria;
        $criteria->addInCondition('manufacturer_id', $manufacturers);
        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

    /**
     * Filter products by min_price
     * @param $value
     */
    public function applyMinPrice($value) {
        if ($value) {
            $criteria = new CDbCriteria;
            $criteria->addCondition('t.price >= ' . (int) $value);
            $this->getDbCriteria()->mergeWith($criteria);
        }
        return $this;
    }

    /**
     * Filter products by man_price
     * @param $value
     */
    public function applyMaxPrice($value) {
        if ($value) {
            $criteria = new CDbCriteria;
            $criteria->addCondition('t.price <= ' . (int) $value);
            $this->getDbCriteria()->mergeWith($criteria);
        }
        return $this;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @param $params
     * @param $additionalCriteria
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search($params = array(), $additionalCriteria = null) {
        $criteria = new CDbCriteria;

        $criteria->with = array(
            'categorization',
            'translate',
            'type',
        );

        if ($additionalCriteria !== null)
            $criteria->mergeWith($additionalCriteria);

        if ($this->manufacturer_id) {
            $manufacturerCr = new CDbCriteria;
            $manufacturerCr->with = array('manufacturer');
            $criteria->mergeWith($manufacturerCr);
        }

        $ids = $this->id;
        // Adds ability to accepts id as "1,2,3" string
        if (false !== strpos($ids, ',')) {
            $ids = explode(',', $this->id);
            $ids = array_map('trim', $ids);
        }

        $criteria->compare('t.id', $ids);
        $criteria->compare('t.ordern', $this->ordern);
        $criteria->compare('label', $this->label);
        $criteria->compare('translate.name', $this->name, true);
        $criteria->compare('t.seo_alias', $this->seo_alias, true);
        $criteria->compare('t.price', $this->price);
        $criteria->compare('t.switch', $this->switch);
        $criteria->compare('translate.short_description', $this->short_description, true);
        $criteria->compare('translate.full_description', $this->full_description, true);
        $criteria->compare('t.sku', $this->sku, true);
        $criteria->compare('t.date_create', $this->date_create, true);
        $criteria->compare('t.date_update', $this->date_update, true);
        $criteria->compare('type_id', $this->type_id);
        $criteria->compare('manufacturer_id', $this->manufacturer_id);
        $criteria->compare('supplier_id', $this->supplier_id);

        if (isset($params['category']) && $params['category']) {
            $criteria->with = array('categorization' => array('together' => true));
            $criteria->compare('categorization.category', $params['category']);
        }
        if (isset($_GET['ShopProduct']['categories']) && $_GET['ShopProduct']['categories']) {
            $criteria->with = array('categorization' => array('together' => true));
            $criteria->compare('categorization.category', $_GET['ShopProduct']['categories']);
        }

        // Id of product to exclude from search
        if ($this->exclude)
            $criteria->compare('t.id !', array(':id' => $this->exclude));
        /* Товары за сегодня */
        if (isset($params['today']) && $params['today'] == true) {
            $today = strtotime(date('Y-m-d'));
            $criteria->addBetweenCondition('t.date_create', date('Y-m-d H:i:s', $today), date('Y-m-d H:i:s', $today + 86400));
        }


        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => self::getCSort()
                )
        );
    }

    /**
     * @return array
     */
    public function behaviors() {
        $a = array();
        $a['eavAttr'] = array(
            'class' => 'mod.shop.components.eav.EEavBehavior',
            'tableName' => '{{shop_product_attribute_eav}}'
        );
        $a['TranslateBehavior'] = array(
            'class' => 'app.behaviors.TranslateBehavior',
            'relationName' => 'translate',
            'translateAttributes' => array(
                'name',
                'short_description',
                'full_description',
                'seo_title',
                'seo_description',
                'seo_keywords',
            ),
        );
        if (Yii::app()->hasModule('comments')) {
            $a['comments'] = array(
                'class' => 'mod.comments.components.CommentBehavior',
                'model' => 'mod.shop.models.ShopProduct',
                'owner_title' => 'name', // Attribute name to present comment owner in admin panel
            );
        }
        if (Yii::app()->hasModule('discounts')) {
            $a['discounts'] = array(
                'class' => 'mod.discounts.components.DiscountBehavior'
            );
        }
        return $a;
    }

    /**
     * Save related products. Notice, related product will be saved after save() method called.
     * @param array $ids Array of related products
     */
    public function setRelatedProducts($ids = array()) {
        $this->_related = $ids;
    }

    public function beforeValidate() {
        // For configurable product set 0 price
        if ($this->use_configurations)
            $this->price = 0;

        return parent::beforeValidate();
    }

    public function beforeSave() {
        if (Yii::app()->settings->get('shop', 'auto_fill_short_desc')) {
            //Yii::import('mod.shop.widgets.SAttributesTableRenderer');
            $a = new AttributesRender;
            $this->short_description = $a->getStringAttr($this);
        }
        return parent::beforeSave();
    }

    public function afterSave() {
        // Process related products
        if ($this->_related !== null) {
            $this->clearRelatedProducts();

            foreach ($this->_related as $id) {
                $related = new ShopRelatedProduct;
                $related->product_id = $this->id;
                $related->related_id = $id;
                $related->save(false, false);
            }
        }

        // Save configurable attributes
        if ($this->_configurable_attribute_changed === true) {
            // Clear
            Yii::app()->db->createCommand()->delete('{{shop_product_configurable_attributes}}', 'product_id = :id', array(':id' => $this->id));

            foreach ($this->_configurable_attributes as $attr_id) {
                Yii::app()->db->createCommand()->insert('{{shop_product_configurable_attributes}}', array(
                    'product_id' => $this->id,
                    'attribute_id' => $attr_id
                ));
            }
        }

        // Process min and max price for configurable product
        if ($this->use_configurations)
            $this->updatePrices($this);
        else {
            // Check if product is configuration
            $query = Yii::app()->db->createCommand()
                    ->from('{{shop_product_configurations}} t')
                    ->where(array('in', 't.configurable_id', array($this->id)))
                    ->queryAll();

            foreach ($query as $row) {
                $model = ShopProduct::model()->findByPk($row['product_id']);
                if ($model)
                    $this->updatePrices($model);
            }
        }

        //  $this->date_update = date('Y-m-d H:i:s');

        return parent::afterSave();
    }

    /**
     * Update price and max_price for configurable product
     * @param ShopProduct $model
     */
    public function updatePrices(ShopProduct $model) {
        // Get min and max prices
        $query = Yii::app()->db->createCommand()
                ->select('MIN(t.price) as min_price, MAX(t.price) as max_price')
                ->from('{{shop_product}} t')
                ->where(array('in', 't.id', $model->getConfigurations(true)))
                ->queryRow();

        // Update
        Yii::app()->db->createCommand()
                ->update('{{shop_product}}', array(
                    'price' => $query['min_price'],
                    'max_price' => $query['max_price']
                        ), 'id=:id', array(':id' => $model->id));
    }

    /**
     * Delete related data.
     */
    public function afterDelete() {
        // Delete related products
        $this->clearRelatedProducts();
        ShopRelatedProduct::model()->deleteAll('related_id=:id', array('id' => $this->id));

        // Delete categorization
        ShopProductCategoryRef::model()->deleteAllByAttributes(array(
            'product' => $this->id
        ));

        // Delete images
        $images = $this->images;
        if (!empty($images)) {
            foreach ($images as $image)
                $image->delete();
        }

        // Delete variants
        $variants = ShopProductVariant::model()->findAllByAttributes(array('product_id' => $this->id));
        foreach ($variants as $v)
            $v->delete();

        // Clear configurable attributes
        Yii::app()->db->createCommand()->delete('{{shop_product_configurable_attributes}}', 'product_id=:id', array(':id' => $this->id));

        // Delete configurations
        Yii::app()->db->createCommand()->delete('{{shop_product_configurations}}', 'product_id=:id', array(':id' => $this->id));
        Yii::app()->db->createCommand()->delete('{{shop_product_configurations}}', 'configurable_id=:id', array(':id' => $this->id));

        // Delete from wish lists if install module "wishlist"
        if (Yii::app()->hasModule('wishlist')) {
            Yii::import('mod.wishlist.models.*');
            $wishlistProduct = WishlistProducts::model()->findByAttributes(array('product_id' => $this->id));
            if ($wishlistProduct)
                $wishlistProduct->delete();
        }
        return parent::afterDelete();
    }

    /**
     * Clear all related products
     */
    private function clearRelatedProducts() {
        ShopRelatedProduct::model()->deleteAll('product_id=:id', array('id' => $this->id));
    }

    /**
     * @return array
     */
    public function getAvailabilityList() {
        return array(
            1 => Yii::t('ShopModule.ShopProduct', 'AVAILABILITY_LIST', 1),
            2 => Yii::t('ShopModule.ShopProduct', 'AVAILABILITY_LIST', 2),
        );
    }

    /**
     * @return array
     */
    public static function getProductLabels() {
        return array(
            1 => Yii::t('ShopModule.default', 'PRODUCT_LABEL', 1),
            2 => Yii::t('ShopModule.default', 'PRODUCT_LABEL', 2),
            3 => Yii::t('ShopModule.default', 'PRODUCT_LABEL', 3),
        );
    }

    /**
     * Set product categories and main category
     * @param array $categories ids.
     * @param integer $main_category Main category id.
     */
    public function setCategories(array $categories, $main_category) {

        $dontDelete = array();
        if (!ShopCategory::model()->countByAttributes(array('id' => $main_category)))
            $main_category = 1;

        if (!in_array($main_category, $categories))
            array_push($categories, $main_category);


        foreach ($categories as $c) {
            $count = ShopProductCategoryRef::model()->countByAttributes(array(
                'category' => $c,
                'product' => $this->id,
            ));
            if ($count == 0) {
                $record = new ShopProductCategoryRef;
                $record->category = (int) $c;
                $record->product = $this->id;
                $record->switch = $this->switch; // new param
                $record->save(false, false, false);
            }

            $dontDelete[] = $c;
        }

        // Clear main category
        ShopProductCategoryRef::model()->updateAll(array(
            'is_main' => 0,
            'switch' => $this->switch
                ), 'product=:p', array(':p' => $this->id));

        // Set main category
        ShopProductCategoryRef::model()->updateAll(array(
            'is_main' => 1,
            'switch' => $this->switch,
                ), 'product=:p AND category=:c', array(':p' => $this->id, ':c' => $main_category));

        // Delete not used relations
        if (sizeof($dontDelete) > 0) {
            $cr = new CDbCriteria;
            $cr->addNotInCondition('category', $dontDelete);

            ShopProductCategoryRef::model()->deleteAllByAttributes(array(
                'product' => $this->id,
                    ), $cr);
        } else {
            // Delete all relations
            ShopProductCategoryRef::model()->deleteAllByAttributes(array(
                'product' => $this->id,
            ));
        }
    }

    /**
     * Prepare variations
     * @return array product variations
     */
    public function processVariants() {
        $result = array();
        foreach ($this->variants as $v) {
            $result[$v->attribute->id]['attribute'] = $v->attribute;
            $result[$v->attribute->id]['options'][] = $v;
        };
        return $result;
    }

    /**
     * @param $ids array of ShopAttribute pks
     */
    public function setConfigurable_attributes(array $ids) {
        $this->_configurable_attributes = $ids;
        $this->_configurable_attribute_changed = true;
    }

    /**
     * @return array
     */
    public function getConfigurable_attributes() {
        if ($this->_configurable_attribute_changed === true)
            return $this->_configurable_attributes;

        if ($this->_configurable_attributes === null) {
            $this->_configurable_attributes = Yii::app()->db->createCommand()
                    ->select('t.attribute_id')
                    ->from('{{shop_product_configurable_attributes}} t')
                    ->where('t.product_id=:id', array(':id' => $this->id))
                    ->group('t.attribute_id')
                    ->queryColumn();
        }

        return $this->_configurable_attributes;
    }

    /**
     * @return array of product ids
     */
    public function getConfigurations($reload = false) {
        if (is_array($this->_configurations) && $reload === false)
            return $this->_configurations;

        $this->_configurations = Yii::app()->db->createCommand()
                ->select('t.configurable_id')
                ->from('{{shop_product_configurations}} t')
                ->where('product_id=:id', array(':id' => $this->id))
                ->group('t.configurable_id')
                ->queryColumn();

        return $this->_configurations;
    }

    /**
     * Calculate product price by its variants, configuration and self price
     * @static
     * @param $product
     * @param array $variants
     * @param $configuration
     */
    public static function calculatePrices($product, array $variants, $configuration) {
        if (($product instanceof ShopProduct) === false)
            $product = ShopProduct::model()->findByPk($product);

        if (($configuration instanceof ShopProduct) === false && $configuration > 0)
            $configuration = ShopProduct::model()->findByPk($configuration);

        if ($configuration instanceof ShopProduct) {
            $result = $configuration->price;
        } else {
            //$result = ($product->currency_id)? $product->price: $product->price;
            $result = $product->price;
        }

        // if $variants contains not models
        if (!empty($variants) && ($variants[0] instanceof ShopProductVariant) === false)
            $variants = ShopProductVariant::model()->findAllByPk($variants);

        foreach ($variants as $variant) {
            // Price is percent
            if ($variant->price_type == 1)
                $result += ($result / 100 * $variant->price);
            else
                $result += $variant->price;
        }

        return $result;
    }

    /**
     * Apply price format
     * @static
     * @param $price
     * @return string formatted price
     */
    public static function formatPrice($price) {
        $c = Yii::app()->settings->get('shop');
        return iconv("windows-1251", "UTF-8", number_format($price, $c['fp_penny'], chr($c['fp_separator_thousandth']), chr($c['fp_separator_hundredth'])));
    }

    /**
     * Convert to active currency and format price.
     * Display min and max price for configurable products.
     * Used in product listing.
     * @return string
     */
    public function priceRange() {
        if (Yii::app()->hasModule('discounts')) {
            $price = $this->appliedDiscount ? $this->toCurrentCurrency('discountPrice') : Yii::app()->currency->convert($this->price);
        } else {
            $price = Yii::app()->currency->convert($this->price);
        }

        $max_price = Yii::app()->currency->convert($this->max_price);
        // $symbol = Yii::app()->currency->active->symbol;

        if ($this->use_configurations && $max_price > 0)
            return self::formatPrice($price) . ' - ' . self::formatPrice($max_price);
        // if($this->currency_id){
        //      return self::formatPrice(($price*$this->currency->rate)/$this->currency->rate_old) . ' ' . $symbol;
        //   }else{
        return self::formatPrice($price);
        //  }
    }

    /**
     * Replaces comma to dot
     * @param $attr
     */
    public function commaToDot($attr) {
        $this->$attr = str_replace(',', '.', $this->$attr);
    }

    /**
     * Convert price to current currency
     *
     * @param string $attr
     * @return mixed
     */
    public function toCurrentCurrency($attr = 'price') {
        return Yii::app()->currency->convert($this->$attr);
    }

    /**
     * Add new image to product.
     * First image image will be marked as main
     * @param CUploadedFile $image
     */
    public function addImage(CUploadedFile $image) {
        new ShopProductImageSaver($this, $image);
    }

    /**
     * Method to get main image title.
     *
     * @return string
     */
    public function getMainImageTitle() {
        if ($this->mainImage)
            return $this->mainImage->title;
    }

    /**
     * Check if product is on warehouse.
     *
     * @return bool
     */
    public function getIsAvailable() {
        return $this->availability == 1;
    }

    /**
     * @return string
     */
    public function getAbsoluteUrl() {
        return Yii::app()->createAbsoluteUrl('/shop/product/view', array('seo_alias' => $this->seo_alias));
    }

    /**
     * @return string
     */
    public function getRelativeUrl() {
        return Yii::app()->createUrl('/shop/product/view', array('seo_alias' => $this->seo_alias));
    }

    /**
     * Decrease product quantity when added to cart
     */
    public function decreaseQuantity() {
        if ($this->auto_decrease_quantity && (int) $this->quantity > 0) {
            $this->quantity--;
            $this->save(false, false, false);
        }
    }

    /**
     * Allows to access EAV attributes like normal model attrs.
     * e.g $model->eav_some_attribute_name
     *
     * @todo Optimize, cache.
     * @param $name
     * @return null
     */
    public function __get($name) {
        if (substr($name, 0, 4) === 'eav_') {
            if ($this->getIsNewRecord())
                return null;

            $attribute = substr($name, 4);
            $eavData = $this->getEavAttributes();

            if (isset($eavData[$attribute]))
                $value = $eavData[$attribute];
            else
                return null;

            $attributeModel = ShopAttribute::model()->findByAttributes(array('name' => $attribute));
            return $attributeModel->renderValue($value);
        }
        return parent::__get($name);
    }

    public function getCategories() {
        $content = array();
        foreach ($this->categories as $c) {
            $content[] = $c->name;
        }
        return implode(', ', $content);
    }

    public function keywords() {
        $config = Yii::app()->settings->get('shop');
        if ($config['auto_gen_meta']) {
            return $this->replaceMeta($config['auto_gen_tpl_keywords']);
        } else {
            return $this->seo_keywords;
        }
    }

    public function description() {
        $config = Yii::app()->settings->get('shop');
        if ($config['auto_gen_meta']) {
            return $this->replaceMeta($config['auto_gen_tpl_description']);
        } else {
            return $this->seo_description;
        }
    }

    public function title() {
        $config = Yii::app()->settings->get('shop');
        if ($config['auto_gen_meta']) {
            return $this->replaceMeta($config['auto_gen_tpl_title']);
        } else {
            return ($this->seo_title) ? $this->seo_title : $this->name;
        }
    }

    public function replaceMeta($text) {
        $array = array(
            "%PRODUCT_NAME%" => $this->name,
            "%PRODUCT_PRICE%" => $this->price,
            "%PRODUCT_ARTICLE%" => $this->sku,
            "%PRODUCT_PCS%" => $this->pcs,
            "%PRODUCT_BRAND%" => $this->manufacturer->name,
            "%PRODUCT_MAIN_CATEGORY%" => $this->mainCategory->name,
            "%CURRENT_CURRENCY%" => Yii::app()->currency->active->symbol
        );
        return CMS::textReplace($text, $array);
    }

}
