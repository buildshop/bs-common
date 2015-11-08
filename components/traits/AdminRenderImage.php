<?php

trait AdminRenderImage {


    public function renderImage($uploads_dir_alias, $attr) {
        if (!$this->isNewRecord) {
            if (file_exists(Yii::getPathOfAlias("webroot.uploads.{$uploads_dir_alias}") . '/' . $this->$attr) && !empty($this->$attr)) {
                Yii::app()->controller->widget('ext.fancybox.Fancybox', array('target' => '.overview-image'));
                $dir = str_replace('.', '/', $uploads_dir_alias);
                return '<a style="font-size:22px;" href="/uploads/' . $dir . '/' . $this->$attr . '" class=" overview-image" title="' . $this->$attr . '"><i class="flaticon-images"></i></a>';
            } else {
                return false;
            }
        }
    }

}