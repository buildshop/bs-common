<?php

/**
 * Represent model as array needed to create CMenu.
 * Usage:
 * 	'MenuArrayBehavior'=>array(
 * 		'class'=>'app.behaviors.MenuArrayBehavior',
 * 		'labelAttr'=>'name',
 * 		'urlExpression'=>'array("/shop/category", "id"=>$model->id)',
 * TODO: Cache queries
 * 	)
 */
class MenuArrayBehavior extends CActiveRecordBehavior {

    /**
     * @var string Owner attribute to be placed in `label` key
     */
    public $labelAttr;
    public $countProduct = false;

    /**
     * @var string Expression will be evaluated to create url.
     * Example: 'urlExpression'=>'array("/shop/category", "id"=>$model->id)',
     */
    public $urlExpression;

    public function menuArray() {
        return $this->walkArray($this->owner, 0);
    }

    /**
     * Recursively build menu array
     * @param $model CActiveRecord model with NestedSet behavior
     * @return array
     */
    protected function walkArray($model, $cc) {
        $url = $this->evaluateUrlExpression($this->urlExpression, array('model' => $model));

        $data = array(
            'label' => $model->{$this->labelAttr},
            'url' => $url,
            'linkOptions' => array('data-hover'=> ($model->level == 2)?'dropdown':'','data-toggle' => ($model->level < 2)?'dropdown':''),
            'itemOptions' => array('class' => ($model->level > 2 && $cc > 0) ? 'dropdown-submenu' : 'dropdown'),
            'submenuOptions' => array('class' => 'dropdown-menu'),
        );
        // TODO: Cache result
        $children = $model->children()
                ->active()
                ->findAll();
        if (!empty($children)) {
            foreach ($children as $c) {
                $checkcount = $c->children()
                        ->active()
                        ->count();
                $data['items'][] = $this->walkArray($c, $checkcount);
            }
        }
        return $data;
    }

    /**
     * @param $expression
     * @param array $data
     * @return mixed
     */
    public function evaluateUrlExpression($expression, $data = array()) {
        extract($data);
        return eval('return ' . $expression . ';');
    }

}
