<?php

Yii::import('mod.exchange1c.components.C1ExternalFinder');
Yii::import('mod.exchange1c.components.C1ProductImage');
Yii::import('mod.shop.models.ShopCategory');
Yii::import('mod.shop.models.ShopProduct');
Yii::import('mod.shop.models.ShopAttribute');
Yii::import('mod.shop.models.ShopTypeAttribute');
Yii::import('mod.shop.models.ShopAttributeOption');


/**
 * Imports products from XML file
 */
class C1ProductsImport extends CComponent {
    /**
     * ID of the ShopType model to apply to new attributes and products
     */

    const DEFAULT_TYPE = 1;

    /**
     * @var string alias where to save uploaded files
     */
    public $tempDirectory = 'application.runtime';

    /**
     * @var string
     */
    protected $xml;

    /**
     * @var ShopCategory
     */
    protected $_rootCategory;

    /**
     * @static
     * @param $type
     * @param $mode
     */
    public static function processRequest($type, $mode) {

        $method = 'command' . ucfirst($type) . ucfirst($mode);
        $import = new self;
        if (method_exists($import, $method))
            $import->$method();
    }

   // public function __construct() {
   //     $this->tempDirectory = Yii::app()->settings->get('exchange1c', 'tempdir');
   // }

    /**
     * Authenticate
     */
    public function commandCatalogCheckauth() {
        echo "success\n";
        echo Yii::app()->session->sessionName . "\n";
        echo Yii::app()->session->sessionId . "\n";
    }

    /**
     * Initialize catalog.
     */
    public function commandCatalogInit() {
        $fileSize = (int) (ini_get('upload_max_filesize')) * 1024 * 1024;
        echo "zip=no\n";
        echo "filelimit={$fileSize}\n";
    }

    /**
     * Save file
     */
    public function commandCatalogFile() {
        $fileName = Yii::app()->request->getQuery('filename');
        $result = file_put_contents($this->buildPathToTempFile($fileName), file_get_contents('php://input'));
        if ($result !== false)
            echo "success\n";
    }

    /**
     * Import
     */
    public function commandCatalogImport() {
        $this->xml = $this->getXml(Yii::app()->request->getQuery('filename'));
        if (!$this->xml)
            return false;

        // Import categories
        if (isset($this->xml->{"Классификатор"}->{"Группы"}))
            $this->importCategories($this->xml->{"Классификатор"}->{"Группы"});

        // Import properties
        if (isset($this->xml->{"Классификатор"}->{"Свойства"}))
            $this->importProperties();

        // Import products
        if (isset($this->xml->{"Каталог"}->{"Товары"}))
            $this->importProducts();

        // Import prices
        if (isset($this->xml->{"ПакетПредложений"}->{"Предложения"}))
            $this->importPrices();

        echo "success\n";
    }

    /**
     * Import catalog products
     */
    public function importProducts() {
        foreach ($this->xml->{"Каталог"}->{"Товары"}->{"Товар"} as $product) {
            $createExId = false;
            $model = C1ExternalFinder::getObject(C1ExternalFinder::OBJECT_TYPE_PRODUCT, $product->{"Ид"});

            if (!$model) {
                $model = new ShopProduct(); // Add "null" by PANIX
                $model->type_id = self::DEFAULT_TYPE;
                $model->price = 0;
                $model->switch = 1;
                $createExId = true;
            }

            $model->name = $product->{"Наименование"};
            $model->seo_alias = CMS::translit($model->name);
            $model->sku = $product->{"Артикул"};
            if($model->save(false,false)){
              }else{
             // Yii::log(CJSON::encode($model->getErrors()),'info','application');
              }

            // Create external id
            if ($createExId === true)
                $this->createExternalId(C1ExternalFinder::OBJECT_TYPE_PRODUCT, $model->id, $product->{"Ид"});

            // Set category
            $categoryId = C1ExternalFinder::getObject(C1ExternalFinder::OBJECT_TYPE_CATEGORY, $product->{"Группы"}->{"Ид"}, false);
            $model->setCategories(array($categoryId), $categoryId);

            // Set image
          //  $image = C1ProductImage::create($this->buildPathToTempFile($product->{"Картинка"}));
           // if ($image && !$model->mainImage)
           ///     $model->addImage($image);
           if(!empty($product->{"Картинка"})){
         foreach($product->{"Картинка"} as $pi) {

            $image=C1ProductImage::create($this->buildPathToTempFile($pi));

            if($image && !$model->mainImage)
               $model->addImage($image);

         }
           }
            // Process properties
            if (isset($product->{"ЗначенияСвойств"}->{"ЗначенияСвойства"})) {
                $attrsdata = array();
                foreach ($product->{"ЗначенияСвойств"}->{"ЗначенияСвойства"} as $attribute) {
                    $attributeModel = C1ExternalFinder::getObject(C1ExternalFinder::OBJECT_TYPE_ATTRIBUTE, $attribute->{"Ид"});
                    if ($attributeModel && $attribute->{"Значение"} != '') {
                        $cr = new CDbCriteria;
                        $cr->with = 'option_translate';
                        $cr->compare('option_translate.value', $attribute->{"Значение"});
                        $option = ShopAttributeOption::model()->find($cr);

                        if (!$option)
                            $option = $this->addOptionToAttribute($attributeModel->id, $attribute->{"Значение"});
                        $attrsdata[$attributeModel->name] = $option->id;
                    }
                }

                if (!empty($attrsdata)) {
                    $model->setEavAttributes($attrsdata, true);
                }
            }
        }
    }

    /**
     * Import catalog prices
     */
    public function importPrices() {
        foreach ($this->xml->{"ПакетПредложений"}->{"Предложения"}->{"Предложение"} as $offer) {
            $product = C1ExternalFinder::getObject(C1ExternalFinder::OBJECT_TYPE_PRODUCT, $offer->{"Ид"});
            if ($product) {
                $product->price = $offer->{"Цены"}->{"Цена"}->{"ЦенаЗаЕдиницу"};
                $product->quantity = $offer->{"Количество"};
                $product->save(false,false,false);
            }
        }
    }

    /**
     * @param $attribute_id
     * @param $value
     * @return ShopAttributeOption
     */
    public function addOptionToAttribute($attribute_id, $value) {
        // Add option
        $option = new ShopAttributeOption;
        $option->attribute_id = $attribute_id;
        $option->value = $value;
        $option->save(false,false);
        return $option;
    }

    /**
     * Import product properties
     */
    public function importProperties() {
        foreach ($this->xml->{"Классификатор"}->{"Свойства"}->{"Свойство"} as $attribute) {
            $model = C1ExternalFinder::getObject(C1ExternalFinder::OBJECT_TYPE_ATTRIBUTE, $attribute->{"Ид"});

            if ($attribute->{"ЭтоФильтр"} == 'false')
                $useInFilter = false;
            else
                $useInFilter = true;

            if (!$model) {
                // Create new attribute
                $model = new ShopAttribute;
                $model->name = CMS::translit($attribute->{"Наименование"});
                $model->name = str_replace('-', '_', $model->name);
                $model->title = $attribute->{"Наименование"};
                $model->type = ShopAttribute::TYPE_DROPDOWN;
                $model->use_in_filter = $useInFilter;
                $model->display_on_front = true;

                if ($model->save(false,false)) {
                    // Add to type
                    $typeAttribute = new ShopTypeAttribute;
                    $typeAttribute->type_id = self::DEFAULT_TYPE;
                    $typeAttribute->attribute_id = $model->id;
                    $typeAttribute->save(false,false);

                    $this->createExternalId(C1ExternalFinder::OBJECT_TYPE_ATTRIBUTE, $model->id, $attribute->{"Ид"});
                }
            }

            // Update attributes
            $model->name = CMS::translit($attribute->{"Наименование"});
            $model->use_in_filter = $useInFilter;
            $model->save();
        }
    }

    /**
     * @param $data
     * @param null|ShopCategory $parent
     */
    public function importCategories($data, $parent = null) {
        foreach ($data->{"Группа"} as $category) {
            // Find category by external id
            $model = C1ExternalFinder::getObject(C1ExternalFinder::OBJECT_TYPE_CATEGORY, $category->{"Ид"});

            if (!$model) {
                $model = new ShopCategory;
                $model->name = $category->{"Наименование"};
                $model->seo_alias = CMS::translit($category->{"Наименование"});
                $model->appendTo($this->getRootCategory());
                $this->createExternalId(C1ExternalFinder::OBJECT_TYPE_CATEGORY, $model->id, $category->{"Ид"});
            }

            if ($parent === null)
                $model->moveAsLast($this->getRootCategory());
            else
                $model->moveAsLast($parent);

            $model->saveNode();

            // Process subcategories
            if (isset($category->{"Группы"}))
                $this->importCategories($category->{"Группы"}, $model);
        }
    }

    /**
     * parse xml file from temp dir.
     * @param $xmlFileName
     * @return bool|object
     */
    public function getXml($xmlFileName) {
        $xmlFileName = str_replace('../', '', $xmlFileName);
        $fullPath = Yii::getPathOfAlias($this->tempDirectory) . DS . $xmlFileName;
        if (file_exists($fullPath) && is_file($fullPath))
            return simplexml_load_file($fullPath);
        else
            return false;
    }

    /**
     * @return ShopCategory
     */
    public function getRootCategory() {
        if ($this->_rootCategory)
            return $this->_rootCategory;
        $this->_rootCategory = ShopCategory::model()->findByPk(1);
        return $this->_rootCategory;
    }

    /**
     * @param $type
     * @param $id
     * @param $externalId
     */
    public function createExternalId($type, $id, $externalId) {
        Yii::app()->db->createCommand()->insert('{{exchange1c}}', array(
            'object_type' => $type,
            'object_id' => $id,
            'external_id' => $externalId
        ));
    }

    /**
     * Builds path to 1C downloaded files. E.g: we receive
     * file with name 'import/df3/fl1.jpg' and build path to temp dir,
     * protected/runtime/fl1.jpg
     *
     * @param $fileName
     * @return string
     */
    public function buildPathToTempFile($fileName) {
        $fileName = end(explode('/', $fileName));
        return Yii::getPathOfAlias($this->tempDirectory) . DS . $fileName;
    }

}
