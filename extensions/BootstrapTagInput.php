<?php

class BootstrapTagInput extends CInputWidget {

    public $multiple = false;
    public $options = array();
    public $defaultOptions = array();

    function run() {
        $this->defaultOptions = array(
            'tagClass' => 'form-control',
            'angular' => false,
        );
        list($name, $id) = $this->resolveNameID();
        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $id;

        $this->htmlOptions['data-role'] = 'tagsinput';


        if ($this->hasModel()) {
            echo Html::activeTextField($this->model, $this->attribute, $this->htmlOptions);
        } else {
            echo Html::textField($name, $this->value, $this->htmlOptions);
        }

        $options = CJavaScript::encode(CMap::mergeArray($this->defaultOptions, $this->options));
        $cs = Yii::app()->getClientScript();

        if ($this->defaultOptions['angular'])
            $cs->registerScriptFile(Yii::app()->controller->baseAssetsUrl . '/js/bootstrap/plugins/taginput/bootstrap-tagsinput-angular.min.js');
        $cs->registerScriptFile(Yii::app()->controller->baseAssetsUrl . '/js/bootstrap/plugins/taginput/bootstrap-tagsinput.min.js');
        $cs->registerCssFile(Yii::app()->controller->baseAssetsUrl . '/js/bootstrap/plugins/taginput/bootstrap-tagsinput.css');
        $js = "jQuery('#{$id}').tagsinput({$options});";
        $cs->registerScript(__CLASS__ . '#' . $id, $js);
    }

}