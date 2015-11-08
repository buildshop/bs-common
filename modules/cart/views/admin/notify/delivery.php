<?php
if ($this->isAjax) {
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div', array('class' => 'wrapper'));
}
?>
<div class="widget">
    <div class="whead">
        <h6><?php echo $this->pageName ?></h6>
        <a class="buttonH bBlue" title="" href="/admin/shop/notify/deliverySend"><?php echo Yii::t('CartModule.admin', 'Отправить') ?></a>
        <div class="clear"></div>
    </div>

    <?php
    $this->widget('ext.fancybox.Fancybox', array('target' => 'td.image a'));
    $this->widget('ext.adminList.GridView', array(
        'dataProvider' => $dataProvider,
        'selectableRows' => false,
        'autoColumns'=>false,
        'enableHeader'=>false,
        'columns' => array(
            array(
                'class' => 'SGridIdColumn',
                'type' => 'html',
                'htmlOptions' => array('class' => 'image'),
                'value' => '(!empty($data->mainImage))?Html::link(Html::image($data->mainImage->getUrl("50x50"),""),$data->mainImage->getUrl("500x500")):"no image"'
            ),
            array(
                'name' => 'name',
                'type' => 'raw',
                'value' => 'Html::link(Html::encode($data->name), array("/shop/admin/products/update", "id"=>$data->id))',
            ),
            'price',
            array(
                'name' => 'manufacturer_id',
                'type' => 'raw',
                'value' => '$data->manufacturer ? Html::encode($data->manufacturer->name) : ""',
                'filter' => Html::listData(ShopManufacturer::model()->orderByName()->findAll(), 'id', 'name')
            ),
            array(
                'name' => 'supplier_id',
                'type' => 'raw',
                'value' => '$data->supplier_id ? Html::encode($data->supplier->name) : ""',
                'filter' => Html::listData(ShopSuppliers::model()->findAll(), 'id', 'name')
            ),
            array(
                //'name' => 'categories',
                'type' => 'raw',
                'header' => 'Категория/и',
                'htmlOptions' => array('style' => 'width:100px'),
                'value' => '$data->getCategories()',
                'filter' => false
            ),
        ),
    ));
    ?>
    <div class="clear"></div>
</div>
<?php
if ($this->isAjax) echo Html::closeTag('div');
