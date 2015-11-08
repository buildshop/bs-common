<?php

$config = Yii::app()->settings->get('shop');
/**
 * @var $this SFilterRenderer
 */
/**
 * Render filters based on the next array:
 * $data[attributeName] = array(
 * 	    'title'=>'Filter Title',
 * 	    'selectMany'=>true, // Can user select many filter options
 * 	    'filters'=>array(array(
 * 	        'title'      => 'Title',
 * 	        'count'      => 'Products count',
 * 	        'queryKey'   => '$_GET param',
 * 	        'queryParam' => 'many',
 * 	    ))
 *  );
 */
// Render active filters




echo $this->render('_currentFilter', array(), true);
echo $this->render('_priceFilter', array('config' => $config), true);
if (!empty($manufacturers['filters']) || !empty($attributes))
    echo Html::openTag('div', array('class' => 'list-group'));

echo $this->render('_manufacturerFilter', array(
    'config' => $config,
    'manufacturers' => $manufacturers,
    'attributes' => $attributes
        ), true);
echo $this->render('_attributesFilter', array(
    'config' => $config,
    'attributes' => $attributes
        ), true);
if (!empty($manufacturers['filters']) || !empty($attributes))
    echo Html::closeTag('div');
?>
