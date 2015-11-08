<?php

/**
 * <b>Example of use:</b>
 * 
 * <code>
 * $this->widget('mod.shop.widgets.jstree.JsTree',array('data'=>'ARRAY_TRE', 'options'=>'ARRAY_OPTIONS'));
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
    public $theme = 'default';
    private $assetsUrl;
   // public $cacheid = 'widget.tree';
    /**
     * Init widget
     */
    public function init() {
        $this->assetsUrl = Yii::app()->assetManager->publish(dirname(__FILE__) . '/assets', false, -1, YII_DEBUG);
        $this->cs = Yii::app()->getClientScript();
        $this->cs->registerCoreScript('cookie');
        if (Yii::app()->request->isAjaxRequest) {
            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
            Yii::app()->clientScript->scriptMap['cookie.js'] = false;
        }
        $this->cs->registerScriptFile($this->assetsUrl . '/jquery.jstree.js');
        $this->cs->registerCssFile($this->assetsUrl . "/themes/{$this->theme}/style.css");
    }

    public function run() {
       /* $data = Yii::app()->cache->get($this->cacheid);
        if ($data === false) {
            $data = $this->data;
            Yii::app()->cache->set($this->cacheid, $data);
        }*/
        echo Html::openTag('div', array(
            'id' => $this->id,
        ));
        echo Html::openTag('ul');
        $this->createHtmlTree($this->data);
        echo Html::closeTag('ul');
        echo Html::closeTag('div');



        $defaultOptions = array(
            "themes" => array(
                "theme" => "default",
               // 'url' => $this->assetsUrl . '/themes/' . $this->theme
            //"dots" => false,
            //"icons" => false
            )
        );
        $options = CJavaScript::encode(CMap::mergeArray($defaultOptions, $this->options));

        $this->cs->registerScript('JsTreeScript', "
			$('#{$this->id}').jstree({$options});
		");
    }

    /**
     * Create ul html tree from data array
     * @param string $data
     */
    private function createHtmlTree($data) {
        foreach ($data as $node) {
            echo Html::openTag('li', array(
                'id' => $this->id . 'Node_' . $node['id'],
                'data-status' => $node['switch'],
                'class' => ($node['switch']) ? '' : 'hiddenClass'
            ));
            echo Html::link(Html::encode($node->name));
            if ($node['hasChildren'] === true) {
                echo Html::openTag('ul');
                $this->createHtmlTree($node['children']);
                echo Html::closeTag('ul');
            }
            echo Html::closeTag('li');
        }
    }

}
