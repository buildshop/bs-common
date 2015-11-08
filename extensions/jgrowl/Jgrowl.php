<?php

/**
 * <b>Example of use:</b>
 * 
 * <code>
 * 
 * </code>
 * 
 * @package widgets.other
 * @uses CComponent
 */
class Jgrowl extends CComponent {

    public static function register() {
        $assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.jgrowl.assets'), false, -1, YII_DEBUG);

        $cs = Yii::app()->clientScript;
        if (YII_DEBUG) {
        $cs->registerCssFile($assetsUrl . '/jquery.jgrowl.css');
            $cs->registerScriptFile($assetsUrl . '/jquery.jgrowl.js');
        } else {
        $cs->registerCssFile($assetsUrl . '/jquery.jgrowl.min.css');
            $cs->registerScriptFile($assetsUrl . '/jquery.jgrowl.min.js');
        }
    }

}
