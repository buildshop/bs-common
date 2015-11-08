<?php


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
        $assetsUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets', false, -1, YII_DEBUG);
        $this->cs = Yii::app()->getClientScript();
        $this->cs->registerCoreScript('cookie');
        $this->cs->registerScriptFile($assetsUrl . '/jstree.js');
        $this->cs->registerCssFile($assetsUrl . '/themes/default/style.css');
    }

    public function run() {
        echo Html::openTag('div', array(
            'id' => $this->id,
        ));
        echo Html::openTag('ul');
        $this->createHtmlTree($this->data);
        echo Html::closeTag('ul');
        echo Html::closeTag('div');

        $options = CJavaScript::encode($this->options);

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
               // 'id' => $this->id . 'Node_' . $node['id'],
               // 'data-status' => $node['switch'],
                //'class' => ($node['switch']) ? '' : 'hiddenClass'
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
