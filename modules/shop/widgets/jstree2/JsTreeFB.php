<?php

/**
 * <b>Example of use:</b>
 * 
 * <code>
 * $this->widget('ext.jstree.SJsTree',array('data'=>'ARRAY_TRE', 'options'=>'ARRAY_OPTIONS'));
 * </code>
 * 
 * @package widgets
 * @uses CWidget
 */
class JsTree extends CWidget {

    /**
     * @var string Id of elements
     */
    public $id;

    /**
     * @var array of nodes. Each node must contain next attributes:
     *  id - If of node
     *  name - Name of none
     *  hasChildren - boolean has node children
     *  children - get children array
     */
    public $data = array();

    /**
     * @var array jstree options
     */
    public $options = array();

    /**
     * @var CClientScript
     */
    protected $cs;

    /**
     * Init widget
     */
    public function init() {
        $assetsUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets', false, -1, YII_DEBUG
        );
        $this->cs = Yii::app()->getClientScript();
      //  $this->cs->registerCoreScript('cookie');
        $this->cs->registerScriptFile($assetsUrl . '/jstree.js');
        $this->cs->registerCssFile($assetsUrl . '/themes/default/style.css');
        $this->cs->registerCssFile($assetsUrl . '/themes/default/filebrowser.css');
        
    }

    public function run() {
        Yii::import('mod.core.components.fs');
        $fs = new fs(Yii::getPathOfAlias('webroot.themes'));

    }

  
}
