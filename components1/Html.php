<?php

class Html extends CHtml {

    public static function activeLabelEx($model, $attribute, $htmlOptions = array()) {
        $realAttribute = $attribute;
        self::resolveName($model, $attribute); // strip off square brackets if any
        $htmlOptions['required'] = $model->isAttributeRequired($attribute);
        return self::activeLabel($model, $realAttribute, $htmlOptions);
    }

    public static function activeLabel($model, $attribute, $htmlOptions = array()) {
        $inputName = self::resolveName($model, $attribute);
        if (isset($htmlOptions['for'])) {
            $for = $htmlOptions['for'];
            unset($htmlOptions['for']);
        }
        else
            $for = self::getIdByName($inputName);
        if (isset($htmlOptions['label'])) {
            if (($label = $htmlOptions['label']) === false)
                return '';
            unset($htmlOptions['label']);
        }
        else
            $label = $model->getAttributeLabel($attribute);
        if ($model->hasErrors($attribute))
            self::addErrorCss($htmlOptions);
        return self::label($label, $for, $htmlOptions);
    }

    /**
     * @todo HTML and word filter
     * @param type $message
     * @param type $mode Default = 1
     * @return string
     */
    public static function text($message, $mode = 1) {
        $config = Yii::app()->settings->get('core');
        if ($mode)
            $message = strip_tags(urldecode($message));
        $message = htmlspecialchars(trim($message), ENT_QUOTES);
        if ($config['censor'] && $mode == 1) {
            $censor_l = explode(",", $config['censor_array']);
            foreach ($censor_l as $val)
                $message = preg_replace("#" . $val . "#iu", $config['censor_replace'], $message);
        }
        if (isset($_GET['highlight'])) {
            return self::highlight($message, $_GET['highlight']);
        } else {
            return $message;
        }
    }

    /**
     * 
     * @param type $text
     * @param type $word
     * @return type
     */
    public static function highlight($text, $word) {
        if ($word) {
            $pos = max(mb_stripos($text, $word, null, 'UTF-8') - 100, 0);
            $fragment = mb_substr($text, $pos, 200, 'UTF-8');
            $highlighted = str_ireplace($word, '<span class="highlight-word">' . $word . '</span>', $fragment);
        } else {
            $highlighted = mb_substr($text, 0, 200, 'UTF-8');
        }
        return $highlighted;
    }


}