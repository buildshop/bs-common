<?php

class ActiveForm extends CActiveForm {

    public function label($model, $attribute, $htmlOptions = array()) {
        return Html::activeLabel($model, $attribute, $htmlOptions);
    }

    public function labelEx($model, $attribute, $htmlOptions = array()) {
        return Html::activeLabelEx($model, $attribute, $htmlOptions);
    }

}