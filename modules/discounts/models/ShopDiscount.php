<?php

/**
 * Main discounts model
 * This is the model class for table "shop_discount".
 *
 * The followings are the available columns in table 'shop_discount':
 * @property integer $id
 * @property string $name
 * @property integer $switch
 * @property string $sum
 * @property integer $start_date
 * @property integer $end_date
 * @property string $roles json encoded
 */
class ShopDiscount extends ActiveRecord {

    const MODULE_ID = 'discounts';

    /**
     * @var array ids of categories to apply discount
     */
    protected $_categories;

    /**
     * @var array ids of manufacturers to apply discount
     */
    protected $_manufacturers;

    public function getGridColumns() {
        return array(
            array(
                'name' => 'name',
                'type' => 'raw',
                'value' => 'Html::link(CHtml::encode($data->name), array("/admin/shop/discounts/update", "id"=>$data->id))',
            ),
            array(
                'name' => 'sum',
                'value' => '$data->sum'
            ),
            array(
                'name' => 'switch',
                'filter' => array(1 => Yii::t('app', 'YES'), 0 => Yii::t('core', 'NO')),
                'value' => '$data->switch ? Yii::t("app", "YES") : Yii::t("core", "NO")'
            ),
            array(
                'name' => 'start_date',
                'value' => 'CMS::date($data->start_date,1)'
            ),
            array(
                'name' => 'end_date',
                'value' => '$data->end_date'
            ),
            'DEFAULT_CONTROL' => array(
                'class' => 'ButtonColumn',
                'template' => '{update}{delete}',
            ),
            'DEFAULT_COLUMNS' => array(
                array('class' => 'CCheckBoxColumn')
            ),
        );
    }

    public function attributeLabels() {
        return CMap::mergeArray(array(
                    'manufacturers' => Yii::t('DiscountsModule.ShopDiscount', 'MANUFACTURERS'),
                    'userRoles' => Yii::t('DiscountsModule.ShopDiscount', 'USER_ROLES')
                        ), parent::attributeLabels());
    }

    public function getForm() {
        Yii::import('zii.widgets.jui.CJuiDatePicker');
        return array(
            'showErrorSummary' => true,
            'attributes' => array(
                'class' => 'form-horizontal',
                'id' => __CLASS__,
            ),
            'elements' => array(
                'content' => array(
                    'type' => 'form',
                    'title' => Yii::t('DiscountsModule.admin', 'Общая информация'),
                    'elements' => array(
                        'name' => array(
                            'type' => 'text',
                        ),
                        'switch' => array(
                            'type' => 'checkbox',
                        ),
                        'sum' => array(
                            'type' => 'text',
                            'hint' => $this->t('HINT_SUM'),
                        ),
                        'start_date' => array(
                            'type' => 'CJuiDatePicker',
                            'htmlOptions' => array('class' => 'form-control'),
                            'options' => array(
                                'dateFormat' => 'yy-mm-dd ' . date('H:i:s'),
                            ),
                        ),
                        'end_date' => array(
                            'type' => 'CJuiDatePicker',
                            'htmlOptions' => array('class' => 'form-control'),
                            'options' => array(
                                'dateFormat' => 'yy-mm-dd ' . date('H:i:s'),
                            ),
                        ),
                        'manufacturers' => array(
                            'type' => 'dropdownlist',
                            'items' => Html::listData(ShopManufacturer::model()->orderByName()->findAll(), 'id', 'name'),
                            'multiple' => 'multiple',
                            'data-placeholder' => $this->t('HINT_MANUFACTURERS'),
                        ),
                        'userRoles' => array(
                            'type' => 'dropdownlist',
                            'items' => DiscountHelper::getGroup(),
                            'multiple' => 'multiple',
                        //   'hint' => $this->t('HINT_USER_ROLES'),
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


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{shop_discount}}';
    }

    /**
     * @return array
     */
    public function scopes() {
        $alias = $this->getTableAlias();
        return array(
            'orderByName' => array('order' => $alias . '.name ASC'),
            'activeOnly' => array('condition' => $alias . '.switch=1'),
            'applyDate' => array(
                'condition' => 'start_date <= :now AND end_date >= :now',
                'params' => array(':now' => date('Y-m-d H:i:s')),
            ),
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('name, sum, start_date, end_date', 'required'),
            array('switch', 'boolean'),
            array('name', 'length', 'max' => 255),
            array('sum', 'length', 'max' => 10),
            array('manufacturers, categories, userRoles', 'type', 'type' => 'array'),
            array('start_date, end_date', 'date', 'format' => 'yyyy-M-d H:m:s'),
            array('id, name, switch, sum, start_date, end_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function getCategories() {
        if (is_array($this->_categories))
            return $this->_categories;

        $this->_categories = Yii::app()->db->createCommand()
                ->select('category_id')
                ->from('{{shop_discount_category}}')
                ->where('discount_id=:id', array(':id' => $this->id))
                ->queryColumn();

        return $this->_categories;
    }

    /**
     * @param array $data
     */
    public function setCategories(array $data) {
        $this->_categories = $data;
    }

    /**
     * @return array
     */
    public function getUserRoles() {
        return json_decode($this->roles);
    }

    /**
     * @param array $roles
     */
    public function setUserRoles(array $roles) {
        $this->roles = json_encode($roles);
    }

    /**
     * @return array
     */
    public function getManufacturers() {
        if (is_array($this->_manufacturers))
            return $this->_manufacturers;

        $this->_manufacturers = Yii::app()->db->createCommand()
                ->select('manufacturer_id')
                ->from('{{shop_discount_manufacturer}}')
                ->where('discount_id=:id', array(':id' => $this->id))
                ->queryColumn();

        return $this->_manufacturers;
    }

    /**
     * @param array $data
     */
    public function setManufacturers(array $data) {
        $this->_manufacturers = $data;
    }

    /**
     * After save event
     */
    public function afterSave() {
        $this->clearRelations();

        // Process manufacturers
        if (!empty($this->_manufacturers)) {
            foreach ($this->_manufacturers as $id) {
                Yii::app()->db->createCommand()->insert('{{shop_discount_manufacturer}}', array(
                    'discount_id' => $this->id,
                    'manufacturer_id' => $id,
                ));
            }
        }

        // Process categories
        if (!empty($this->_categories)) {
            foreach ($this->_categories as $id) {
                Yii::app()->db->createCommand()->insert('{{shop_discount_category}}', array(
                    'discount_id' => $this->id,
                    'category_id' => $id,
                ));
            }
        }

        return parent::afterSave();
    }

    public function afterDelete() {
        $this->clearRelations();
    }

    /**
     * Clear discount manuacturer and category
     */
    public function clearRelations() {
        Yii::app()->db->createCommand()->delete('{{shop_discount_manufacturer}}', 'discount_id=:id', array(':id' => $this->id));
        Yii::app()->db->createCommand()->delete('{{shop_discount_category}}', 'discount_id=:id', array(':id' => $this->id));
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.name', $this->name, true);
        $criteria->compare('t.switch', $this->switch);
        $criteria->compare('t.sum', $this->sum, true);
        $criteria->compare('t.start_date', $this->start_date, true);
        $criteria->compare('t.end_date', $this->end_date, true);

        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}