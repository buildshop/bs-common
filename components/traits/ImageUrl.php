<?php

trait ImageUrl {

    /**
     * Get url to product image. Enter $size to resize image.
     * @param mixed $attr Model attribute
     * @param mixed $size New size of the image. e.g. '150x150'
     * @param mixed $dir Folder name upload
     * @return string
     */
    public function getImageUrl($attr, $dir, $size = false,$resize='resize') {
        Yii::import('ext.phpthumb.PhpThumbFactory');
        $attrname = $this->$attr;
        if (!empty($attrname)) {
            if ($size !== false) {
                $thumbPath = Yii::getPathOfAlias('webroot.assets') . DS . $dir . DS . $size;
                if (!file_exists($thumbPath))
                    mkdir($thumbPath, 0777, true);

                // Path to source image
                $fullPath = Yii::getPathOfAlias('webroot.uploads') . DS . $dir . DS . $attrname;
                // Path to thumb
                $thumbPath = $thumbPath . '/' . $attrname;

                if (!file_exists($thumbPath)) {
                    // Resize if needed
                    $sizes = explode('x', $size);
                    $thumb = PhpThumbFactory::create($fullPath);
                    $thumb->$resize($sizes[0], $sizes[1])->save($thumbPath); //resize/adaptiveResize
                }

                return '/assets/' . $dir . '/' . $size . '/' . $attrname;
            }
            // return '/uploads/product/' . $attrname;
        } else {
            return false;
        }
    }

}