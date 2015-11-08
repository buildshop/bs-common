<?php

Yii::import('mod.shop.models.ShopAttributeTranslate');

/**
 * This is the model class for table "ShopAttribute".
 *
 * The followings are the available columns in table 'ShopAttribute':
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property integer $type
 * @property boolean $display_on_front
 * @property integer $ordern
 * @property boolean $required
 * @property boolean $use_in_compare
 * @property boolean $use_in_filter Display attribute options as filter on front
 * @property boolean $use_in_variants Use attribute and its options to configure products
 * @property boolean $select_many Allow to filter products on front by more than one option value.
 * @method ShopCategory useInFilter()
 */
class ShopAttribute extends ActiveRecord {

    const TYPE_TEXT = 1;
    const TYPE_TEXTAREA = 2;
    const TYPE_DROPDOWN = 3;
    const TYPE_SELECT_MANY = 4;
    const TYPE_RADIO_LIST = 5;
    const TYPE_CHECKBOX_LIST = 6;
    const TYPE_YESNO = 7;

    /**
     * @var string attr name
     */
    public $title;
    public $abbreviation;

    /**
     * @var string
     */
    public $translateModelName = 'ShopAttributeTranslate';
    const MODULE_ID = 'shop';

    public function getGridColumns() {
        Yii::import('mod.shop.ShopModule');
        return array(
            array(
                'name' => 'title',
                'type' => 'raw',
                'htmlOptions' => array('class' => 'textL'),
                'value' => 'Html::link(Html::encode($data->title), array("/shop/admin/attribute/update", "id"=>$data->id))',
            ),
            array('name' => 'name', 'value' => '$data->name'),
            array('name' => 'abbreviation', 'value' => '$data->abbreviation'),
            array(
                'name' => 'display_on_front',
                'value' => '$data->display_on_front ? Yii::t("app", "YES") : Yii::t("app", "NO")'
            ),
            array(
                'name' => 'use_in_filter',
                'value' => '$data->use_in_filter ? Yii::t("app", "YES") : Yii::t("app", "NO")'
            ),
            array(
                'name' => 'select_many',
                'value' => '$data->select_many ? Yii::t("app", "YES") : Yii::t("app", "NO")'
            ),
            array(
                'name' => 'use_in_variants',
                'value' => '$data->use_in_variants ? Yii::t("app", "YES") : Yii::t("app", "NO")'
            ),
            array(
                'name' => 'use_in_compare',
                'value' => '$data->use_in_compare ? Yii::t("app", "YES") : Yii::t("app", "NO")'
            ),
            array(
                'name' => 'type',
                'filter' => ShopAttribute::getTypesList(),
                'value' => 'Html::encode(ShopAttribute::getTypeTitle($data->type))'
            ),
            'DEFAULT_CONTROL' => array(
                'class' => 'ButtonColumn',
                'template' => '{update}{delete}',
            ),
            'DEFAULT_COLUMNS' => array(
                array('class' => 'HandleColumn')
            ),
        );
    }

    public function getForm() {
        return new TabForm(array('id' => __CLASS__,
                    'showErrorSummary' => false,
                    'attributes' => array(
                        'class' => 'form-horizontal'
                    ),
                    'elements' => array(
                        'content' => array(
                            'type' => 'form',
                            'title' => $this->t('FORM_TITLE_PARAMS'),
                            'elements' => array(
                                'title' => array('type' => 'text'),
                                'name' => array(
                                    'type' => 'text',
                                    'hint' => $this->t('HINT_NAME')
                                ),
                                'abbreviation' => array('type' => 'text'),
                                'required' => array('type' => 'checkbox'),
                                'type' => array(
                                    'type' => 'dropdownlist',
                                    'items' => self::getTypesList()
                                ),
                                'display_on_front' => array(
                                    'type' => 'dropdownlist',
                                    'items' => array(
                                        1 => Yii::t('app', 'YES'),
                                        0 => Yii::t('app', 'NO'),
                                    ),
                                ),
                                'use_in_filter' => array(
                                    'type' => 'dropdownlist',
                                    'items' => array(
                                        1 => Yii::t('app', 'YES'),
                                        0 => Yii::t('app', 'NO'),
                                    ),
                                    'hint' => $this->t('HINT_USE_IN_FILTER')
                                ),
                                'select_many' => array(
                                    'type' => 'dropdownlist',
                                    'items' => array(
                                        1 => Yii::t('app', 'YES'),
                                        0 => Yii::t('app', 'NO'),
                                    ),
                                    'hint' => $this->t('HINT_SELECT_MANY')
                                ),
                                'use_in_variants' => array(
                                    'type' => 'dropdownlist',
                                    'items' => array(
                                        1 => Yii::t('app', 'YES'),
                                        0 => Yii::t('app', 'NO'),
                                    ),
                                ),
                                'use_in_compare' => array(
                                    'type' => 'dropdownlist',
                                    'items' => array(
                                        1 => Yii::t('app', 'YES'),
                                        0 => Yii::t('app', 'NO'),
                                    ),
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
                        ), $this);
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ShopAttribute the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{shop_attribute}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('name, title', 'required'),
            array('required', 'boolean'),
            array('name', 'unique'),
            array('use_in_compare, use_in_filter, select_many, display_on_front, use_in_variants', 'boolean'),
            array('name', 'match',
                'pattern' => '/^([a-z0-9_])+$/i',
                'message' => $this->t('PATTERN_NAME')
            ),
            array('type, ordern', 'numerical', 'integerOnly' => true),
            array('name, title, abbreviation', 'length', 'max' => 255),
            array('id, name, title, type', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array

      public function defaultScope() {
      $t = $this->getTableAlias();
      return array(
      'sorting' => 'ordern ASC',
      );
      } */

    /**
     * @return array
     */
    public function scopes() {
        $t = $this->getTableAlias();
        return array(
            'useInFilter' => array('condition' => $t . '.use_in_filter=1'),
            'useInVariants' => array('condition' => $t . '.use_in_variants=1'),
            'useInCompare' => array('condition' => $t . '.use_in_compare=1'),
            'displayOnFront' => array('condition' => $t . '.display_on_front=1'),
            'sorting' => array('order' => 'ordern ASC'),
        );
    }

    /**
     * @return array
     */
    public function behaviors() {
        return array(
            'TranslateBehavior' => array(
                'class' => 'app.behaviors.TranslateBehavior',
                'relationName' => 'attr_translate',
                'translateAttributes' => array(
                    'title',
                    'abbreviation'
                ),
                ));
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'attr_translate' => array(self::HAS_ONE, $this->translateModelName, 'object_id'),
            'options' => array(self::HAS_MANY, 'ShopAttributeOption', 'attribute_id', 'order' => '`options`.`ordern` ASC', 'scopes' => 'applyTranslateCriteria'),
            // Used in types
            'types' => array(self::HAS_MANY, 'ShopTypeAttribute', 'attribute_id'),
        );
    }

    /**
     * Get types as key value list
     * @static
     * @return array
     */
    public static function getTypesList() {
        return array(
            self::TYPE_TEXT => 'Text',
            self::TYPE_TEXTAREA => 'Textarea',
            self::TYPE_DROPDOWN => 'Dropdown',
            self::TYPE_SELECT_MANY => 'Multiple Select',
            self::TYPE_RADIO_LIST => 'Radio List',
            self::TYPE_CHECKBOX_LIST => 'Checkbox List',
            self::TYPE_YESNO => 'Yes/No',
        );
    }

    /**
     * @return string html field based on attribute type
     */
    public function renderField($value = null) {
        $name = 'ShopAttribute[' . $this->name . ']';
        switch ($this->type):
            case self::TYPE_TEXT:
                return Html::textField($name, $value);
                break;
            case self::TYPE_TEXTAREA:
                return Html::textArea($name, $value);
                break;
            case self::TYPE_DROPDOWN:
                $data = Html::listData($this->options, 'id', 'value');
                return Html::dropDownList($name, $value, $data, array('empty' => '---'));
                break;
            case self::TYPE_SELECT_MANY:
                $data = Html::listData($this->options, 'id', 'value');
                return Html::dropDownList($name . '[]', $value, $data, array('multiple' => 'multiple'));
                break;
            case self::TYPE_RADIO_LIST:
                $data = Html::listData($this->options, 'id', 'value');
                return Html::radioButtonList($name, (string) $value, $data);
                break;
            case self::TYPE_CHECKBOX_LIST:
                $data = Html::listData($this->options, 'id', 'value');
                return Html::checkBoxList($name . '[]', $value, $data);
                break;
            case self::TYPE_YESNO:
                $data = array(
                    1 => Yii::t('app', 'YES'),
                    2 => Yii::t('app', 'NO')
                );
                return Html::dropDownList($name, $value, $data, array('empty' => '---'));
                break;
        endswitch;
    }

    /**
     * Get attribute value
     * @param $value
     * @return string attribute value
     */
    public function renderValue($value) {
        switch ($this->type):
            case self::TYPE_TEXT:
            case self::TYPE_TEXTAREA:
                return $value;
                break;
            case self::TYPE_DROPDOWN:
            case self::TYPE_RADIO_LIST:
                $data = Html::listData($this->options, 'id', 'value');
                if (!is_array($value) && isset($data[$value]))
                    return $data[$value];
                break;
            case self::TYPE_SELECT_MANY:
            case self::TYPE_CHECKBOX_LIST:
                $data = Html::listData($this->options, 'id', 'value');
                $result = array();

                if (!is_array($value))
                    $value = array($value);

                foreach ($data as $key => $val) {
                    if (in_array($key, $value))
                        $result[] = $val;
                }
                return implode(', ', $result);
                break;
            case self::TYPE_YESNO:
                $data = array(
                    1 => Yii::t('app', 'YES'),
                    2 => Yii::t('app', 'NO')
                );
                if (isset($data[$value]))
                    return $data[$value];
                break;
        endswitch;
    }

    /**
     * @return string html id based on name
     */
    public function getIdByName() {
        $name = 'ShopAttribute[' . $this->name . ']';
        return CHtml::getIdByName($name);
    }

    /**
     * Get type label
     * @static
     * @param $type
     * @return string
     */
    public static function getTypeTitle($type) {
        $list = self::getTypesList();
        return $list[$type];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->with = array('attr_translate');

        $criteria->compare('`t`.`id`', $this->id);
        $criteria->compare('`t`.`name`', $this->name, true);
        $criteria->compare('`attr_translate`.`title`', $this->title, true);
        $criteria->compare('`attr_translate`.`abbreviation`', $this->abbreviation, true);
        $criteria->compare('`t`.`type`', $this->type);
        $criteria->compare('`t`.`ordern`', $this->ordern);
        $criteria->scopes = array('sorting');
        $sort = new CSort;
        $sort->defaultOrder = '`t`.`ordern` ASC';
        $sort->attributes = array(
            '*',
            'abbreviation' => array(
                'asc' => '`attr_translate`.`abbreviation`',
                'desc' => '`attr_translate`.`abbreviation` DESC',
            ),
            'title' => array(
                'asc' => '`attr_translate`.`title`',
                'desc' => '`attr_translate`.`title` DESC',
            ),
        );

        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => $sort
                ));
    }

    public function afterDelete() {
        // Delete options
        foreach ($this->options as $o)
            $o->delete();

        // Delete relations used in product type.
        ShopTypeAttribute::model()->deleteAllByAttributes(array('attribute_id' => $this->id));

        // Delete attributes assigned to products
        $conn = $this->getDbConnection();
        $command = $conn->createCommand("DELETE FROM `{{shop_product_attribute_eav}}` WHERE `attribute`='{$this->name}'");
        $command->execute();

        return parent::afterDelete();
    }

}