<?php

class TagInput extends CInputWidget {

    public $options = array();
    public $defaultOptions = array();

    function run() {
        $this->defaultOptions = array(
            'width' => '100%',
            'defaultText' => Yii::t('app','ADD_TAG'),
            'height'=>'auto',
        );
        list($name, $id) = $this->resolveNameID();
        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $id;
        if ($this->hasModel())
            echo Html::activeTextField($this->model, $this->attribute, $this->htmlOptions);
        else
            echo Html::textField($name, $this->value, $this->htmlOptions);

        $options = CJavaScript::encode(CMap::mergeArray($this->defaultOptions, $this->options));
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile(Yii::app()->controller->baseAssetsUrl . '/js/jquery.tagsinput.min.js');
        $js = "jQuery('#{$id}').tagsInput({$options});";
        $cs->registerScript(__CLASS__ . '#' . $id, $js);
    }

}