<?php

/**
 * FancyBox widget class file.
 * 
 * FancyBox extends CWidget and implements a base class for a fancybox widget.
 * more about fancybox can be found at (see: http://fancybox.net/).
 * 
 * @author Thiago Otaviani Vidal <thiagovidal@othys.com>
 * @link http://www.othys.com
 * @uses CWidget
 * @package widgets.other
 * @copyright (c) 2010 Thiago Otaviani Vidal
 * @version: 1.6
 */
class Fancybox extends CWidget {

    /**
     * the id of the widget, since version 1.6
     * @var string
     */
    public $id;

    /**
     * the taget element on DOM
     * @var string 
     */
    public $target;

    /**
     * whether to enable mouse interaction
     * @var boolean 
     */
    public $mouseEnabled = true;

    /**
     * settings for fancybox
     * @var array 
     */
    public $config = array();

    /**
     * init widget
     */
    public function init() {
        // if not informed will generate Yii defaut generated id, since version 1.6
        if (!isset($this->id))
            $this->id = $this->getId();
        // publish the required assets
        $this->publishAssets();
    }

    /**
     * run the widget
     */
    public function run() {
        $config = CJavaScript::encode($this->config);
        Yii::app()->clientScript->registerScript($this->getId(), "$('$this->target').fancybox($config);");
    }

    /**
     * function to publish and register assets on page 
     * @throws Exception
     */
    public function publishAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $min = (YII_DEBUG) ? '' : '.pack';
        $baseUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
        if (is_dir($assets)) {
            Yii::app()->clientScript->registerCoreScript('jquery');
            Yii::app()->clientScript->registerScriptFile($baseUrl . "/jquery.fancybox{$min}.js", CClientScript::POS_HEAD);
            Yii::app()->clientScript->registerCssFile($baseUrl . '/jquery.fancybox.css');
            // if mouse actions enbled register the js
            if ($this->mouseEnabled) {
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.mousewheel-3.0.6.pack.js', CClientScript::POS_HEAD);
            }
        } else {
            throw new Exception('Fancybox - Error: Couldn\'t find assets to publish.');
        }
    }

}