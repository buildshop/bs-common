<?php

/**
 * API
 * http://silviomoreto.github.io/bootstrap-select/
 */
class SelectInput extends CInputWidget {

    public $options = array();
    public $data = array();

    public function run() {
        // if ($this->hasModel())
        list($name, $id) = $this->resolveNameID();

        $this->registerScript();

        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $id;
        if (isset($this->htmlOptions['name']))
            $name = $this->htmlOptions['name'];

        if ($this->hasModel())
            echo Html::activeDropDownList($this->model, $this->attribute, $this->data, $this->htmlOptions);
        else
            echo Html::dropdownlist($name, $this->value, $this->data, $this->htmlOptions);



        $config = CJavaScript::encode($this->options);
        $cs = Yii::app()->getClientScript();
        $cs->registerScript(__CLASS__ . '#' . $id, "$('#$id').selectpicker($config);");
    }

    protected function registerScript() {
        $lang = Yii::app()->languageManager->active->locale;
      //  if ($this->hasModel())
      //      list($name, $id) = $this->resolveNameID();

        $dir = dirname(__FILE__) . DS . 'assets';
        $baseUrl = Yii::app()->getAssetManager()->publish($dir, false, -1, YII_DEBUG);
        $min = YII_DEBUG ? '' : '.min';
        $cs = Yii::app()->getClientScript();

        $cs->registerScriptFile($baseUrl . "/js/bootstrap-select{$min}.js");
        if ($lang != 'en')
            $cs->registerScriptFile($baseUrl . "/js/i18n/defaults-{$lang}.js");
        $cs->registerCssFile($baseUrl . "/css/bootstrap-select{$min}.css");
    }

}
