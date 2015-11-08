<?php

Yii::import('mod.shop.models.ShopManufacturerTranslate');

/**
 * This is the model class for table "shop_manufacturer".
 *
 * The followings are the available columns in table 'shop_manufacturer':
 * @property integer $id
 * @property string $name
 * @property string $seo_alias
 * @property string $description
 * @property string $seo_title
 * @property string $seo_keywords
 * @property string $seo_description
 * @method ShopManufacturer orderByName()
 */
Yii::import('app.traits.ImageUrl');

class ShopManufacturer extends ActiveRecord {

    use ImageUrl;

    const MODULE_ID = 'shop';

    public $translateModelName = 'ShopManufacturerTranslate';
    // public $aliasPathImage = 'uploads.shop.manufacturer';
    public $image;
    // public $removeImage = false;

    /**
     * Multilingual attrs
     */
    public $name;
    public $description;
    public $seo_title;
    public $seo_description;
    public $seo_keywords;

    public function getGridColumns() {
        return array(
            array(
                'name' => 'image',
                'type' => 'raw',
                'filter' => false,
                'value' => '$data->getImageView()',
            ),
            array(
                'name' => 'name',
                'type' => 'raw',
                'value' => 'Html::link(Html::encode($data->name), array("/shop/admin/manufacturer/update", "id"=>$data->id))',
            ),
            'DEFAULT_CONTROL' => array(
                'class' => 'ButtonColumn',
                'template' => '{switch}{update}{delete}',
            ),
            'DEFAULT_COLUMNS' => array(
                array('class' => 'CCheckBoxColumn'),
                array('class' => 'HandleColumn'),
            ),
        );
    }

    public function getForm() {
        $tab = new TabForm(array('id' => __CLASS__,
                    'attributes' => array(
                        'enctype' => 'multipart/form-data',
                        'class' => 'form-horizontal'
                    ),
                    'showErrorSummary' => true,
                    'elements' => array(
                        'content' => array(
                            'type' => 'form',
                            'title' => $this->t('TAB_GENERAL_INFO'),
                            'elements' => array(
                                'name' => array(
                                    'type' => 'text', 'id' => 'title',
                                ),
                                'seo_alias' => array(
                                    'type' => 'text', 'id' => 'alias',
                                    'visible' => (Yii::app()->settings->get('core', 'translate_object_url')) ? false : true
                                ),
                                'image' => array(
                                    'type' => 'file',
                                    'hint' => $this->getImageView()
                                ),
                                'cat_id' => array(
                                    'type' => 'dropdownlist',
                                    'items' => ShopCategory::flatTree(),
                                    'empty' => '---',
                                ),
                                'description' => array(
                                    'type' => 'textarea',
                                ),
                            ),
                        ),
                        'seo' => array(
                            'type' => 'form',
                            'title' => $this->t('TAB_META'),
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
                        ), $this);
        return $tab;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ShopManufacturer the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{shop_manufacturer}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('name, seo_alias', 'required'),
            array('seo_alias', 'translitFilter', 'translitAttribute' => 'name'), //LocalUrlValidator
            array('ordern, cat_id', 'numerical'),
            //array('removeImage', 'boolean'),
            array('description, image', 'type', 'type' => 'string'),
            array('name, seo_alias, seo_title, seo_keywords, seo_description, image', 'length', 'max' => 255),
            array('image', 'file', 'types' => 'jpg, gif, png',
                'allowEmpty' => true,
                'safe' => true,
                'maxSize' => 1024 * 1024 * 50,
                'tooLarge' => 'File has to be smaller than 50MB'
            ),
            array('id, name, image, seo_alias, description, seo_title, seo_keywords, seo_description, ordern', 'safe', 'on' => 'search'),
        );
    }

    public function getImageView() {
        // die($this->image);
        if (file_exists(Yii::getPathOfAlias('webroot.uploads.manufacturer') . DS . $this->image) && !empty($this->image)) {
            return Html::image($this->getImageUrl('image', 'manufacturer', '100x100'), $this->name, array('class' => 'overview-image'));
        } else {
            return Html::image('http://placehold.it/125x75/&text=No image', $this->name, array('class' => 'overview-image'));
        }
    }

    public function overviewImage() {
        if (!$this->isNewRecord) {
            // if (file_exists(Yii::getPathOfAlias("webroot.uploads.manufacturer.{$this->image}") . '/' . $this->image) && !empty($this->image)) {
            Yii::app()->controller->widget('ext.fancybox.Fancybox', array('target' => '.overview-image'));
            //return Html::image('/uploads/shop/manufacturer/' . $this->image, $this->name, array('class' => 'icon-image-2 overview-image'));
            return '<a href="/uploads/manufacturer/' . $this->image . '" class="icon-image-2 overview-image" title="' . $this->name . '"></a>';
            //} else {
            //    return false;
            //}
        }
    }

    /**
     * Find manufacturer by url.
     * Scope.
     * @param string $url
     * @return ShopProduct
     */
    public function withUrl($seo_lias) {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'seo_alias=:url',
            'params' => array(':url' => $seo_lias)
        ));
        return $this;
    }

    /**
     * Find manufacturer only image.
     * Scope.
     * @return ShopManufacturer
     */
    public function onlyImage() {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'image!=""',
        ));
        return $this;
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'man_translate' => array(self::HAS_ONE, $this->translateModelName, 'object_id'),
            'productsCount' => array(self::STAT, 'ShopProduct', 'manufacturer_id', 'select' => 'count(t.id)'),
        );
    }

    public function scopes() {
        return array(
            'orderByName' => array(
                'order' => 'man_translate.name'
            ),
        );
    }

    /**
     * @return array
     */
    public function behaviors() {
        return array(
            'TranslateBehavior' => array(
                'class' => 'app.behaviors.TranslateBehavior',
                'relationName' => 'man_translate',
                'translateAttributes' => array(
                    'name',
                    'description',
                    'seo_title',
                    'seo_description',
                    'seo_keywords',
                ),
            ),
        );
    }

    public function afterDelete() {
        // Clear product relations
        ShopProduct::model()->updateAll(array(
            'manufacturer_id' => new CDbExpression('NULL'),
                ), 'manufacturer_id = :id', array(':id' => $this->id));

        return parent::afterDelete();
    }

    /**
     * @return string
     */
    public function getViewUrl() {
        $url = Yii::app()->createUrl('/shop/manufacturer/index', array('seo_alias' => $this->seo_alias));
        return urldecode($url);
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->with = array('man_translate');

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.ordern', $this->ordern);
        $criteria->compare('man_translate.name', $this->name, true);
        $criteria->compare('t.seo_alias', $this->seo_alias, true);
        $criteria->compare('man_translate.description', $this->description, true);
        $criteria->compare('t.image', $this->image, true);
        $sort = new CSort;
        $sort->attributes = array(
            '*',
            'name' => array(
                'asc' => 'man_translate.name',
                'desc' => 'man_translate.name DESC',
            ),
        );

        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => $sort
                ));
    }

}