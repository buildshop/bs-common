<?php

$this->pageTitle = Yii::t('ShopModule.core', 'Поиск');
$this->breadcrumbs[] = Yii::t('ShopModule.core', 'Поиск');
 
?>

<div class="catalog">

    <div class="products_list">
<?php
/*$this->widget('zii.widgets.CBreadcrumbs', array(
    'links' => $this->breadcrumbs,
));*/
?>

        <h1><?php
        echo Yii::t('ShopModule.core', 'Результаты поиска');
        if (($q = Yii::app()->request->getParam('q')))
            echo ' "' . CHtml::encode($q) . '"';
?></h1>
<?php if(false) { ?>
        <div class="actions">
            <?php
            echo Yii::t('ShopModule.core', 'Сортировать:');
            echo CHtml::dropDownList('sorter', Yii::app()->request->url, array(
                Yii::app()->request->removeUrlParam('/shop/category/search', 'sort') => '---',
                Yii::app()->request->addUrlParam('/shop/category/search', array('sort' => 'price')) => Yii::t('ShopModule.core', 'Сначала дешевые'),
                Yii::app()->request->addUrlParam('/shop/category/search', array('sort' => 'price.desc')) => Yii::t('ShopModule.core', 'Сначала дорогие'),
                    ), array('onchange' => 'applyCategorySorter(this)'));
            ?>

            <?php
            $limits = array(Yii::app()->request->removeUrlParam('/shop/category/search', 'per_page') => $this->allowedPageLimit[0]);
            array_shift($this->allowedPageLimit);
            foreach ($this->allowedPageLimit as $l)
                $limits[Yii::app()->request->addUrlParam('/shop/category/search', array('per_page' => $l))] = $l;

            echo Yii::t('ShopModule.core', 'На странице:');
            echo CHtml::dropDownList('per_page', Yii::app()->request->url, $limits, array('onchange' => 'applyCategorySorter(this)'));
            ?>
        </div>
        <?php } ?>
        <div id="vitrina">
            <div class="wrapper" style="width: 685px">
<?php
if (isset($provider)) {
    $this->widget('ListView', array(
        'dataProvider' => $provider,
        'ajaxUpdate' => false,
        'template' => '{items} {pager}',
        'itemView' => '_product',
        'sortableAttributes' => array(
            'name', 'price'
        ),
            'pager'=>array(
        'htmlOptions' => array('class'=>'pagination'),
        'header'=>'',
        'nextPageLabel'=>'Следующая »',
        'prevPageLabel'=>'« Предыдущая',
        'prevPageLabel'=>'« Предыдущая',
        'prevPageLabel'=>'« Предыдущая',
    )
    ));
} else {
    echo Yii::t('ShopModule.core', 'Нет результатов');
}
?>
            </div>
        </div>
    </div>
</div>