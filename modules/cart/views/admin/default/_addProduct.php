<div style="padding-bottom:15px;">
    <?php
    /**
     * Add new product to order.
     * Display products list.
     */
    if (!isset($dataProvider))
        $dataProvider = new ShopProduct('search');

// Fix sort url
    $dataProvider = $dataProvider->search();
    $dataProvider->sort->route = 'addProductList';
    $dataProvider->pagination->route = 'addProductList';

    $this->widget('ext.adminList.GridView', array(
        'filter' => $dataProvider->model,
        'enableHeader' => false,
        'autoColumns' => false,
        'dataProvider' => $dataProvider,
        'ajaxUrl' => Yii::app()->createUrl('/admin/cart/addProductList', array('id' => $model->id)),
        'template' => '{items}{pager}',
        'selectableRows' => 0,
        'columns' => array(
            array(
                'name' => 'id',
                'type' => 'text',
                'value' => '$data->id',
                'filter' => false
            ),
            array(
                'class' => 'SGridIdColumn',
                'type' => 'html',
                'value' => '(!empty($data->mainImage))?Html::link(Html::image($data->mainImage->getUrl("50x50"),""),$data->mainImage->getUrl("500x500")):"no image"'
            ),
            array(
                'name' => 'name',
                'type' => 'raw',
            ),
            array(
                'name' => 'sku',
                'value' => '$data->sku',
            ),
            array(
                'type' => 'raw',
                'name' => 'price',
                'value' => 'Html::textField("price_{$data->id}", $data->price, array("style"=>"text-align:center;width:80px;border:1px solid silver;padding:1px;"))',
            ),
            array(
                'type' => 'raw',
                'value' => 'Html::textField("count_{$data->id}", 1, array("class"=>"spinner"))',
                'header' => Yii::t('CartModule.OrderProduct', 'QUANTITY'),
            ),
            array(
                'class' => 'CLinkColumn',
                'header' => '',
                'label' => '<span class="flaticon-cart-add"></span>',
                'urlExpression' => '$data->id',
                'htmlOptions' => array(
                    'class' => 'addProductToOrder',
                    'onClick' => 'return addProductToOrder(this, ' . $model->id . ', "' . Yii::app()->request->csrfToken . '");'
                ),
            ),
        ),
    ));
    ?>
</div>
