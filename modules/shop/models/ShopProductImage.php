<?php

//Yii::import('mod.shop.components.ShopImagesConfig');

/**
 * This is the model class for table "ShopProductImage".
 *
 * The followings are the available columns in table 'ShopProductImage':
 * @property integer $id
 * @property integer $product_id
 * @property string $name
 * @property integer $is_main
 * @property integer $uploaded_by
 * @property string $date_uploaded
 * @property string $title
 */
class ShopProductImage extends ActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return ShopProductImage the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{shop_product_image}}';
    }

    /**
     * @return array
     */
    public function relations() {
        return array(
            'author' => array(self::BELONGS_TO, 'User', 'uploaded_by'),
            'product' => array(self::BELONGS_TO, 'ShopProduct', 'product_id'),
        );
    }

    /**
     * @return array
     */
    public function defaultScope() {
        return array(
            'order' => 'date_create DESC',
        );
    }

    /**
     * Get url to product image. Enter $size to resize image.
     * @param mixed $size New size of the image. e.g. '150x150'
     * @param mixed $resizeMethod Resize method name to override config. resize/adaptiveResize
     * @param mixed $random Add random number to the end of the string
     * @return string
     */
    public function getUrl($size = false, $resizeMethod = 'resize', $random = false) {
       // $config = Yii::app()->settings->get('shop');
        if ($size !== false) {
            $thumbPath = Yii::getPathOfAlias('webroot.assets.product') . '/' . $size;
            if (!file_exists($thumbPath))
                mkdir($thumbPath, 0777, true);

            // Path to source image
            $fullPath = Yii::getPathOfAlias('webroot.uploads.product') . '/' . $this->name;
            // Path to thumb
            $thumbPath = $thumbPath . '/' . $this->name;

            if (!file_exists($thumbPath)) {
                // Resize if needed
                Yii::import('ext.phpthumb.PhpThumbFactory');
                $sizes = explode('x', $size);
                $thumb = PhpThumbFactory::create($fullPath);

              //  if ($resizeMethod === false)
                 //   $resizeMethod = 'resize';
                $thumb->$resizeMethod($sizes[0], $sizes[1])->save($thumbPath);
            }

            return '/assets/product/' . $size . '/' . $this->name;
        }

        if ($random === true)
            return '/uploads/product/' . $this->name . '?' . rand(1, 10000);
        return '/uploads/product/' . $this->name;
    }

    public function attributeLabels() {
        return array(
            'product_id' => Yii::t('ShopModule.admin', 'Продукт'),
            'name' => Yii::t('ShopModule.admin', 'Имя файла'),
            'is_main' => Yii::t('ShopModule.admin', 'Главное'),
            'author' => Yii::t('ShopModule.admin', 'Автор'),
            'uploaded_by' => Yii::t('ShopModule.admin', 'Автор'),
            'date_create' => Yii::t('ShopModule.admin', 'Дата загрузки'),
            'title' => Yii::t('ShopModule.admin', 'Название'),
        );
    }

    /**
     * Delete file, etc...
     */
    public function afterDelete() {
        // Delete file
        if (file_exists($this->filePath))
            unlink($this->filePath);

        // If main image was deleted
        if ($this->is_main) {
            // Get first image and set it as main
            $model = ShopProductImage::model()->find();
            if ($model) {
                $model->is_main = 1;
                $model->save(false,false,false);
            }
        }

        return parent::afterDelete();
    }

    /**
     * @return string
     *
     */
    public function getFilePath() {
         $config = Yii::app()->settings->get('shop');
        return Yii::getPathOfAlias($config['path']) . '/' . $this->name;
    }

}