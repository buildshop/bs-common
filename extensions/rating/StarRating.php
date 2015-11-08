<?php

class StarRating extends CStarRating {

    public $maxRating = 5;

    public function init() {
        for ($x = 1; $x <= $this->maxRating; $x++) {
            $this->titles[$x] = Yii::t('app', 'RATING', $x);
        }
        parent::init();
    }

    public function registerClientScript($id) {

        $assetsUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets', false, -1, YII_DEBUG);


        $jsOptions = $this->getClientOptions();
        $jsOptions = empty($jsOptions) ? '' : CJavaScript::encode($jsOptions);
        $js = "jQuery('#{$id} > input').rating({$jsOptions});";
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('rating');
        $cs->registerScriptFile($assetsUrl . '/js/rating.js');
        $cs->registerScript('Yii.CStarRating#' . $id, $js);

        if ($this->cssFile !== false)
            self::registerCssFile($this->cssFile);
    }

}

?>
