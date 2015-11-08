<?php

Yii::import('ext.phpthumb.PhpThumbFactory');
//Yii::import('application.modules.shop.components.ShopImagesConfig');
Yii::import('mod.shop.components.ShopUploadedImage');

/**
 * Class ShopProductImageSaver
 *
 * Save/handle uploaded product images
 */
class ShopProductImageSaver {

    /**
     * @param ShopProduct $product
     * @param CUploadedFile $image
     */
    public function __construct(ShopProduct $product, CUploadedFile $image) {

        $name = ShopUploadedImage::createName($product, $image);
        $fullPath = ShopUploadedImage::getSavePath() . '/' . $name;
        $image->saveAs($fullPath);
        @chmod($fullPath, 0666);

        // Check if product has main image
        $is_main = (int) ShopProductImage::model()->countByAttributes(array(
                    'product_id' => $product->id,
                    'is_main' => 1
                ));

        $imageModel = new ShopProductImage;
        $imageModel->product_id = $product->id;
        $imageModel->name = $name;
        $imageModel->is_main = ($is_main == 0) ? true : false;
        $imageModel->uploaded_by = Yii::app()->user->id;
        //$imageModel->date_uploaded = date('Y-m-d H:i:s');
        $imageModel->save(false, false);

        $this->resize($fullPath);
        $this->watermark($fullPath);
    }

    public function resize($fullPath) {
        $config = Yii::app()->settings->get('shop');
        $sizes = explode('x', $config['maximum_image_size']);
        Yii::app()->img
                ->load($fullPath)
                ->thumb($sizes[0], $sizes[1])
                ->save();
    }

    public function watermark($fullPath) {
        $config = Yii::app()->settings->get('shop');
        if ($config['watermark_active']) {
            Yii::app()->img
                    ->load($fullPath)
                    // ->watermark(Yii::getPathOfAlias('webroot.uploads') . '/watermark.png', (int)$config['watermark_offsetX'], (int)$config['watermark_offsetY'], (int)$config['watermark_corner'], $config['watermark_zoom'])
                    ->watermark(Yii::getPathOfAlias('webroot.uploads') . '/watermark.png', $config['watermark_offsetX'], $config['watermark_offsetY'], $config['watermark_corner'], false)
                    ->save();
        }
    }

}