<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
Yii::import('zii.widgets.CMenu');
Yii::import('mod.shop.models.*');

class NestedMenu extends CMenu {


    public $activeCssClass='selected';
    public function init() {
        $this->publishAssets();

        $this->items = $this->getMyItems();

        parent::init();
    }

    public function publishAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
        if (is_dir($assets)) {
            Yii::app()->clientScript->registerCssFile($baseUrl . '/css/nestedmenu.css');
        } else {
            throw new Exception('NestedMenu - Error: Couldn\'t find assets to publish.');
        }
    }

    protected function getMyItems() {
        $model = ShopCategory::model()->findByPk(1);
       // print_r($model);
      //  die;
        $result = $model->menuArray();
        return $result['items'];
    }
/*
    protected function renderMenuRecursive($items) {
        $count = 0;
        $n = count($items);
        foreach ($items as $item) {
            $count++;
            $options = isset($item['itemOptions']) ? $item['itemOptions'] : array();
            $class = array();
            if ($item['active'] && $this->activeCssClass != '')
                $class[] = $this->activeCssClass;
            if ($count === 1 && $this->firstItemCssClass !== null)
                $class[] = $this->firstItemCssClass;
            if ($count === $n && $this->lastItemCssClass !== null)
                $class[] = $this->lastItemCssClass;
           // $options['class'].=' pull-left';
            if ($this->itemCssClass !== null)
                $class[] = $this->itemCssClass;
            if ($class !== array()) {
                if (empty($options['class']))
                    $options['class'] = implode(' ', $class);
                else
                    $options['class'].=' ' . implode(' ', $class);
            }

            echo CHtml::openTag('li', $options);

            $menu = $this->renderMenuItem($item);
            if (isset($this->itemTemplate) || isset($item['template'])) {
                $template = isset($item['template']) ? $item['template'] : $this->itemTemplate;
                echo strtr($template, array('{menu}' => $menu));
            }
            else
                echo $menu;

            if (isset($item['items']) && count($item['items'])) {
                echo "\n" . CHtml::openTag('ul', isset($item['submenuOptions']) ? $item['submenuOptions'] : $this->submenuHtmlOptions) . "\n";
                $this->renderMenuRecursive($item['items']);
                echo CHtml::closeTag('ul') . "\n";
            }

            echo CHtml::closeTag('li') . "\n";
        }
    }*/

}

?>
