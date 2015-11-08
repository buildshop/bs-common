<?php

class Widget extends CWidget {

    public $title;
    public $cs;
    public $assetsUrl;
    public $assetsPath;
    public $registerFile = array();
    public $registerCoreFile = array();
    public $min;

    public function init() {
        if (!isset($this->assetsPath))
            throw new Exception(get_class($this) . ': не могу определить пусть к ресурсам assetsPath.');

        $this->min = (YII_DEBUG) ? '' : '.min';
        $this->cs = Yii::app()->clientScript;
        $this->assetsUrl = Yii::app()->getAssetManager()->publish($this->assetsPath, false, -1, YII_DEBUG);
        $this->registerAssets();
        parent::init();
    }

    protected function registerAssets() {
        foreach ($this->registerFile as $file) {
            if (preg_match('/[-\w]+\.js/', $file)) {
                $this->cs->registerScriptFile($this->assetsUrl . '/js/' . $file);
            } else {
                $this->cs->registerCssFile($this->assetsUrl . '/css/' . $file);
            }
        }
        foreach ($this->registerCoreFile as $file) {
            $this->cs->registerCoreScript($file);
        }
    }


}

?>
