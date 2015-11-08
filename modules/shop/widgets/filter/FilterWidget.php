<?php

/**
 * Base class to render filters by:
 *  Manufacturer
 *  Price
 *  Eav attributes
 *
 * Usage:
 * $this->widget('application.modules.shop.widgets.SFilterRenderer', array(
 *      // ShopCategory model. Used to create url
 *      'model'=>$model,
 *  ));
 *
 * @method CategoryController getOwner()
 */
class FilterWidget extends CWidget {

    /**
     * @var array of ShopAttribute models
     */
    public $attributes;
    public $countAttr = true;
    public $countManufacturer = true;

    /**
     * @var ShopCategory
     */
    public $model;

    /**
     * @var string default view to render results
     */

    /**
     * @var string min price in the query
     */
    private $_currentMinPrice = null;

    /**
     * @var string max price in the query
     */
    private $_currentMaxPrice = null;

    public function init() {
        $this->registerAssets();
    }

    public function getMinPrice() {
        return $this->controller->getMinPrice();
    }

    public function getMaxPrice() {
        return $this->controller->getMaxPrice();
    }

    /**
     * Render filters
     */
    public function run() {
        $this->render($this->skin, array(
            'manufacturers' => $this->getCategoryManufacturers(),
            'attributes' => $this->getCategoryAttributes(),
        ));
    }

    /**
     * Get active/applied filters to make easier to cancel them.
     */
    public function getActiveFilters() {
        $request = Yii::app()->request;
        // Render links to cancel applied filters like prices, manufacturers, attributes.
        $menuItems = array();
        $manufacturers = array_filter(explode(',', $request->getQuery('manufacturer')));
        $manufacturers = ShopManufacturer::model()
                //->cache($this->controller->cacheTime)
                ->findAllByPk($manufacturers);

        if ($request->getQuery('min_price')) {
            array_push($menuItems, array(
                'linkOptions' => array('class' => 'remove'),
                'label' => Yii::t('ShopModule.core', 'от {minPrice} {c}', array('{minPrice}' => (int) $this->getCurrentMinPrice(), '{c}' => Yii::app()->currency->active->symbol)),
                'url' => $request->removeUrlParam('/shop/category/view', 'min_price')
            ));
        }

        if ($request->getQuery('max_price')) {
            array_push($menuItems, array(
                'label' => Yii::t('ShopModule.core', 'до {maxPrice} {c}', array('{maxPrice}' => (int) $this->getCurrentMaxPrice(), '{c}' => Yii::app()->currency->active->symbol)),
                'linkOptions' => array('class' => 'remove'),
                'url' => $request->removeUrlParam('/shop/category/view', 'max_price')
            ));
        }

        if (!empty($manufacturers)) {
            foreach ($manufacturers as $manufacturer) {
                array_push($menuItems, array(
                    'label' => $manufacturer->name,
                    'linkOptions' => array('class' => 'remove'),
                    'url' => $request->removeUrlParam('/shop/category/view', 'manufacturer', $manufacturer->id)
                ));
            }
        }

        // Process eav attributes
        $activeAttributes = $this->getOwner()->activeAttributes;
        if (!empty($activeAttributes)) {
            foreach ($activeAttributes as $attributeName => $value) {
                if (isset($this->getOwner()->eavAttributes[$attributeName])) {
                    $attribute = $this->getOwner()->eavAttributes[$attributeName];
                    foreach ($attribute->options as $option) {
                        if (isset($activeAttributes[$attribute->name]) && in_array($option->id, $activeAttributes[$attribute->name])) {
                            array_push($menuItems, array(
                                'label' => $option->value,
                                'linkOptions' => array('class' => 'remove'),
                                'url' => $request->removeUrlParam('/shop/category/view', $attribute->name, $option->id)
                            ));
                        }
                    }
                }
            }
        }

        return $menuItems;
    }

    /**
     * @return array of attributes used in category
     */
    public function getCategoryAttributes() {
        $data = array();

        foreach ($this->attributes as $attribute) {
            $data[$attribute->name] = array(
                'title' => $attribute->title,
                'selectMany' => (boolean) $attribute->select_many,
                'filters' => array()
            );
            foreach ($attribute->options as $option) {
                $data[$attribute->name]['filters'][] = array(
                    'title' => $option->value,
                    'count' => $this->countAttributeProducts($attribute, $option),
                    'queryKey' => $attribute->name,
                    'queryParam' => $option->id,
                );
            }
        }
        return $data;
    }

    /**
     * Count products by attribute and option
     * @param ShopAttribute $attribute
     * @param string $option option id to search
     * @todo Optimize attributes merging
     * @return string
     */
    public function countAttributeProducts($attribute, $option) {
        if ($this->countAttr) {
            $model = new ShopProduct(null);
            $model->attachBehaviors($model->behaviors());
            $model->active()
                    ->cache($this->controller->cacheTime)
                    ->applyCategories($this->model)
                    ->applyMinPrice($this->convertCurrency(Yii::app()->request->getQuery('min_price')))
                    ->applyMaxPrice($this->convertCurrency(Yii::app()->request->getQuery('max_price')));

            if (Yii::app()->request->getParam('manufacturer'))
                $model->applyManufacturers(explode(',', Yii::app()->request->getParam('manufacturer')));

            $data = array($attribute->name => $option->id);
            $current = $this->getOwner()->activeAttributes;

            $newData = array();

            foreach ($current as $key => $row) {
                if (!isset($newData[$key]))
                    $newData[$key] = array();
                if (is_array($row)) {
                    foreach ($row as $v)
                        $newData[$key][] = $v;
                }
                else
                    $newData[$key][] = $row;
            }

            $newData[$attribute->name][] = $option->id;
            return $model->withEavAttributes($newData)->count();
        }
    }

    /**
     * @return array of category manufacturers
     */
    public function getCategoryManufacturers() {
        $cr = new CDbCriteria;
        $cr->select = 't.manufacturer_id, t.id';
        $cr->group = 't.manufacturer_id';
        $cr->addCondition('t.manufacturer_id IS NOT NULL');

        //@todo: Fix manufacturer translation
        $mdl = $this->model;
        $dependency = new CDbCacheDependency('SELECT MAX(date_update) FROM {{shop_product}}');
        //$dependency = new CChainedCacheDependency();
        $manufacturers = ShopProduct::model()
                ->cache($this->controller->cacheTime, $dependency)
                ->active()
                ->applyCategories($mdl, null)
                ->with(array(
                    'manufacturer' => array(
                        'with' => array(
                            'productsCount' => array(
                                'scopes' => array(
                                    'active',
                                    'applyCategories' => array($mdl, null),
                                    'applyAttributes' => array($this->getOwner()->activeAttributes),
                                    'applyMinPrice' => array($this->convertCurrency(Yii::app()->request->getQuery('min_price'))),
                                    'applyMaxPrice' => array($this->convertCurrency(Yii::app()->request->getQuery('max_price'))),
                            ))
                        ),
                        )))
                ->findAll($cr);

        $data = array(
            'title' => Yii::t('default', 'Производитель'),
            'selectMany' => true,
            'filters' => array()
        );

        if ($manufacturers) {
            foreach ($manufacturers as $m) {
                $m = $m->manufacturer;
                if ($m) {
                    $model = new ShopProduct(null);
                    $model->attachBehaviors($model->behaviors());
                    $model->active()
                            ->cache($this->controller->cacheTime, $dependency)
                            ->applyCategories($this->model)
                            ->applyMinPrice($this->convertCurrency(Yii::app()->request->getQuery('min_price')))
                            ->applyMaxPrice($this->convertCurrency(Yii::app()->request->getQuery('max_price')))
                            ->applyAttributes($this->getOwner()->activeAttributes)
                            ->applyManufacturers($m->id);

                    $data['filters'][] = array(
                        'title' => $m->name,
                        'count' => $model->count(),
                        'queryKey' => 'manufacturer',
                        'queryParam' => $m->id,
                    );
                }
            }
        }

        return $data;
    }

    /**
     * @return mixed
     */
    public function getCurrentMinPrice() {
        if ($this->_currentMinPrice !== null)
            return $this->_currentMinPrice;

        if (Yii::app()->request->getQuery('min_price'))
            $this->_currentMinPrice = Yii::app()->request->getQuery('min_price');
        else
            $this->_currentMinPrice = Yii::app()->currency->convert($this->controller->getMinPrice());

        return $this->_currentMinPrice;
    }

    /**
     * @return mixed
     */
    public function getCurrentMaxPrice() {
        if ($this->_currentMaxPrice !== null)
            return $this->_currentMaxPrice;

        if (Yii::app()->request->getQuery('max_price'))
            $this->_currentMaxPrice = Yii::app()->request->getQuery('max_price');
        else
            $this->_currentMaxPrice = Yii::app()->currency->convert($this->controller->getMaxPrice());

        return $this->_currentMaxPrice;
    }

    /**
     * Proxy to CurrencyManager::activeToMain
     * @param $sum
     */
    public function convertCurrency($sum) {
        $cm = Yii::app()->currency;
        if ($cm->active->id != $cm->main->id)
            return $cm->activeToMain($sum);
        return $sum;
    }

    private function registerAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $assetsUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
        $cs = Yii::app()->clientScript;
        if (is_dir($assets)) {
            $cs->registerCssFile($assetsUrl . '/css/shop-filter.css');
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

    /* public function getAjaxActiveFilters() {
      //print_r(Yii::app()->request->getPost('manufacturer'));
      // Render links to cancel applied filters like prices, manufacturers, attributes.
      $menuItems = array();
      $manu = Yii::app()->request->getPost('manufacturer');

      //  $manufacturers = array_filter(explode(',', $manu));


      $manufacturers = ShopManufacturer::model()
      ->cache($this->controller->cacheTime)
      ->findAllByPk(Yii::app()->request->getPost('manufacturer'));

      if (Yii::app()->request->getPost('min_price')) {
      array_push($menuItems, array(
      'linkOptions' => array('class' => 'remove'),
      'label' => Yii::t('ShopModule.core', 'от {minPrice} {c}', array('{minPrice}' => (int) $this->getCurrentMinPrice(), '{c}' => Yii::app()->currency->active->symbol)),
      'url' => Yii::app()->request->removeUrlParam('/shop/category/view', 'min_price')
      ));
      }

      if (Yii::app()->request->getPost('max_price')) {
      array_push($menuItems, array(
      'label' => Yii::t('ShopModule.core', 'до {maxPrice} {c}', array('{maxPrice}' => (int) $this->getCurrentMaxPrice(), '{c}' => Yii::app()->currency->active->symbol)),
      'linkOptions' => array('class' => 'remove'),
      'url' => Yii::app()->request->removeUrlParam('/shop/category/view', 'max_price')
      ));
      }

      if (!empty($manufacturers)) {
      foreach ($manufacturers as $manufacturer) {
      array_push($menuItems, array(
      'label' => $manufacturer->name,
      'linkOptions' => array('class' => 'remove'),
      'url' => Yii::app()->request->removeUrlParam('/shop/category/view', 'manufacturer', $manufacturer->id)
      ));
      }
      }

      // Process eav attributes
      $activeAttributes = $this->getOwner()->activeAttributes;
      if (!empty($activeAttributes)) {
      foreach ($activeAttributes as $attributeName => $value) {
      if (isset($this->getOwner()->eavAttributes[$attributeName])) {
      $attribute = $this->getOwner()->eavAttributes[$attributeName];
      foreach ($attribute->options as $option) {
      if (isset($activeAttributes[$attribute->name]) && in_array($option->id, $activeAttributes[$attribute->name])) {
      array_push($menuItems, array(
      'label' => $option->value,
      'linkOptions' => array('class' => 'remove'),
      'url' => Yii::app()->request->removeUrlParam('/shop/category/view', $attribute->name, $option->id)
      ));
      }
      }
      }
      }
      }

      return $menuItems;
      } */
}
