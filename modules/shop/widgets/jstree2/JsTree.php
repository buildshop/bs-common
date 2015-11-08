<?php

/**
 * <b>Example of use:</b>
 * 
 * <code>
 * $this->widget('mod.shop.widgets.jstree.SJsTree',array('data'=>'ARRAY_TRE', 'options'=>'ARRAY_OPTIONS'));
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
        $assetsUrl = Yii::app()->assetManager->publish(dirname(__FILE__) . '/assets', false, -1, YII_DEBUG);
        $this->cs = Yii::app()->getClientScript();
        $this->cs->registerCoreScript('cookie');
        $this->cs->registerScriptFile($assetsUrl . '/jstree.js');
         $this->cs->registerCssFile($assetsUrl . '/themes/default/style.css');
    }

    public function run() {
        echo Html::openTag('div', array(
            'id' => $this->id,
        ));
       // echo Html::openTag('ul');
       // $this->createHtmlTree2($this->data);
       // echo Html::closeTag('ul');
        echo Html::closeTag('div');

        $options = CJavaScript::encode($this->options);

        $this->cs->registerScript('JsTreeScript', "
			$('#{$this->id}').jstree({$options});
	




$(document).on('dnd_start.vakata', function (e, data) {
    console.log(data);
    console.log(data.data.obj.attr('id').replace('node_',''));
    console.log(data.data.obj.attr('data-switch'));
    console.log(data.data.obj.attr('data-key'));


});






$(document).on('dnd_stop.vakata', function (e, data) {
    console.log(data);
    console.log(data.data.obj.attr('id').replace('node_',''));
    console.log(data.data.obj.attr('data-switch'));
    console.log(data.data.obj.attr('data-key'));
    /*
        $.ajax({
            async : false,
            type: 'GET',
            url: '/admin/shop/category/moveNode',
            data : {
                'id' : data.data.obj.attr('id').replace('node_',''),
               // 'ref' : data.rslt.cr === -1 ? 1 : data.rslt.np.attr('id').replace('ShopCategoryTreeNode_',''),
               // 'position' : data.rslt.cp + i
            }

        });*/


});
        
        
        ");
    }
    private function createHtmlTree($data) {
        $result=array();
        foreach ($data as $node) {
            $result[]=array(
                'id'=>$node['id'],
                'text'=>Html::encode($node->name),
                'children'=>$this->createHtmlTree($node['children'])
            );
           // if ($node['hasChildren'] === true) {
           //     $result['children'][]=$this->createHtmlTree($node['children']);
            //}
          /*  echo Html::openTag('li', array(
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
            echo Html::closeTag('li');*/
        }
        return $result;
    }
    /**
     * Create ul html tree from data array
     * @param string $data
     */
    private function createHtmlTree2($data) {
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
