<?php

Yii::import('zii.widgets.CMenu');

class AdminMenu extends CMenu {


    private $nljs;
    public $cssFile;
    public $activateParents = true;
    public $totalCount = false;
    public $ajax = false;
    //public $items = array();
    public $submenuHtmlOptions = array('class' => 'dropdown-menu');
   public $htmlOptions = array('class' => 'nav navbar-nav');
    const CACHE_ID = 'EngineMainMenu';

    /**
     * Give the last items css 'parent' style 
     */
    protected function cssParentItems($items) {

        foreach ($items as $i => $item) {
            if (isset($item['items'])) {
                if (isset($item['itemOptions']['class']))
                    $items[$i]['itemOptions']['class'].=' dropdown';
                else
                    $items[$i]['itemOptions']['class'] = 'dropdown';

                $items[$i]['items'] = $this->cssParentItems($item['items']);
            }
        }

        return array_values($items);
    }

    /**
     * Initialize the widget
     */
    public function init() {
        if (!$this->getId(false))
            $this->setId('cssmenu');

        $this->nljs = "\n";

        $defaultItems = array(
            'system' => array(
                'label' => Yii::t('app', 'SYSTEM'),
                'icon' => 'fa-gear',
            ),
            'modules' => array(
                'label' => Yii::t('app', 'MODULES'),
                'icon' => 'fa-bars',
            ),
        );
        $cacheID = self::CACHE_ID . '-' . Yii::app()->language;
        // $items = Yii::app()->cache->get($cacheID);
        // if ($items === false) {
        $found = $this->findMenu();
        //  unset($found['system'],$found['users']);
        //}

        
        $items = CMap::mergeArray($defaultItems, $found);

        $this->items = $this->cssParentItems($items);

        parent::init();
    }



    protected function renderMenuRecursive($items) {

        foreach ($items as $item) {
            if ($this->totalCount) {
                $totalCount = '<span class="total_count">(' . $item['total_count'] . ')</span>';
            } else {
                $totalCount = '';
            }
            echo Html::openTag('li', isset($item['itemOptions']) ? $item['itemOptions'] : array());
            if (isset($item['url']))
                echo Html::link('<i class="fa ' . $item['icon'] . '"></i> ' . $item['label'] . ' ' . $totalCount, $item['url'], isset($item['linkOptions']) ? $item['linkOptions'] : array());
            else
                echo Html::link('<i class="fa ' . $item['icon'] . '"></i> ' . $item['label'] . ' ' . $totalCount . '<span class="caret"></span>', "javascript:void(0);", isset($item['linkOptions']) ? $item['linkOptions'] : array('class' => 'dropdown-toggle', 'data-toggle' => "dropdown"));

            if (isset($item['items']) && count($item['items'])) {
                echo "\n" . Html::openTag('ul', $this->submenuHtmlOptions) . "\n";
                $this->renderMenuRecursive($item['items']);
                echo Html::closeTag('ul') . "\n";
            }
            echo Html::closeTag('li') . "\n";
        }
    }

    protected function normalizeItems($items, $route, &$active, $ischild = 0) {

        foreach ($items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            }
            if ($this->encodeLabel)
                $items[$i]['label'] = Html::encode($item['label']);
            $hasActiveChild = false;
            if (isset($item['items'])) {
                $items[$i]['items'] = $this->normalizeItems($item['items'], $route, $hasActiveChild, 1);
                if (empty($items[$i]['items']) && $this->hideEmptyItems)
                    unset($items[$i]['items']);
            }
            if (!isset($item['active'])) {
                if (($this->activateParents && $hasActiveChild) || $this->isItemActive($item, $route))
                    $active = $items[$i]['active'] = true;
                else
                    $items[$i]['active'] = false;
            }
            else if ($item['active'])
                $active = true;
            if ($items[$i]['active'] && $this->activeCssClass != '' && !$ischild) {
                if (isset($item['itemOptions']['class']))
                    $items[$i]['itemOptions']['class'].=' ' . $this->activeCssClass;
                else
                    $items[$i]['itemOptions']['class'] = $this->activeCssClass;
            }
        }
        return array_values($items);
    }

    public function findMenu($mod = false) {
        $result = array();
        $modules = Yii::app()->getModules();
        foreach ($modules as $mid=>$module) {
            $class = Yii::import('mod.' . $mid . '.' . ucfirst($mid) . 'Module');
            if (method_exists($class, 'getAdminMenu')) {
                $result = CMap::mergeArray($result, $class::getAdminMenu());
            }
        }
        return ($mod) ? $result[$mod] : $result;
    }


}