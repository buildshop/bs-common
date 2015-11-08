<?php

/**
 * Validate uploaded product image.
 * Create unique image name.
 */
class ShopUploadedImage {

    /**
     * @param CUploadedFile $image
     * @return bool
     */
    public static function isAllowedSize(CUploadedFile $image) {
        $config = Yii::app()->settings->get('shop');
        return ($image->getSize() <= $config['maxFileSize']);
    }

    /**
     * @param CUploadedFile $image
     * @return bool
     */
    public static function isAllowedExt(CUploadedFile $image) {
        return in_array(strtolower($image->getExtensionName()), array('jpg', 'jpeg', 'png', 'gif'));
    }

    /**
     * @param CUploadedFile $image
     * @return bool
     */
    public static function isAllowedType(CUploadedFile $image) {
        $type = CFileHelper::getMimeType($image->getTempName());
        if (!$type)
            $type = CFileHelper::getMimeTypeByExtension($image->getName());
        return in_array($type, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png'));
    }

    /**
     * @param CUploadedFile $image
     * @return bool
     */
    public static function hasErrors(CUploadedFile $image) {
        return !(!$image->getError() && self::isAllowedExt($image) === true && self::isAllowedSize($image) === true && self::isAllowedType($image) === true);
    }

    /**
     * @return string Path to save product image
     */
    public static function getSavePath() {
        return Yii::getPathOfAlias('webroot.uploads.product');
    }

    /**
     * @param ShopProduct $model
     * @param CUploadedFile $image
     * @return string
     */
    public static function createName(ShopProduct $model, CUploadedFile $image) {
        $path = self::getSavePath();
        $name = self::generateRandomName($model, $image);

        if (!file_exists($path . '/' . $name))
            return $name;
        else
            self::createName($model, $image);
    }

    /**
     * Generates random name bases on product and image models
     *
     * @param ShopProduct $model
     * @param CUploadedFile $image
     * @return string
     */
    public static function generateRandomName(ShopProduct $model, CUploadedFile $image) {
        return strtolower($model->id . '_' . crc32(microtime()) . '.' . $image->getExtensionName());
    }

}