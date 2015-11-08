<?php


class TinymceWidget extends CWidget {

    protected $assetsPath;
    protected $assetsUrl;

    public function init() {
        parent::init();
        if ($this->assetsPath === null) {
            $this->assetsPath = dirname(__FILE__) . DS . 'assets';
        }
        if ($this->assetsUrl === null) {
            $this->assetsUrl = Yii::app()->assetManager->publish($this->assetsPath, false, -1, YII_DEBUG);
        }
        $this->registerScripts();
    }


    protected function registerScripts() {
        $cs = Yii::app()->clientScript;
        if (is_dir($this->assetsPath)) {
            $cs->registerScriptFile($this->assetsUrl . '/tinymce.min.js', CClientScript::POS_HEAD);
            $cs->registerScript('tinymce', 'tinymce.init({
    selector: ".editor",
    language : "'.Yii::app()->language.'",
    document_base_url: "",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen textcolor",
        "insertdatetime media table contextmenu paste moxiemanager"
    ],
    toolbar: "insertfile undo redo | styleselect fontsizeselect | forecolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    relative_urls : true,
    onvert_urls: false,
    remove_script_host: false


});', CClientScript::POS_HEAD);
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

}