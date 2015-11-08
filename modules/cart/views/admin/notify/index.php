<?php
if ($this->isAjax) {
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div', array('class' => 'wrapper'));
}
?>


    <?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => '')
));


    $this->widget('ext.adminList.GridView', array(
        'dataProvider' => $dataProvider,
        'selectableRows' => false,
        'name'=>$this->pageName,
        'autoColumns'=>false,
        'enableHeader'=>false,
        'columns' => array(
            array(
                'name' => 'name',
                'type' => 'raw',
                'value' => 'Html::link(Html::encode($data->product->name), array("/shop/admin/products/update", "id"=>$data->product->id))',
            ),
            array(
                'name' => 'product_availability',
                'type' => 'raw',
                'value' => 'Html::encode($data->product->availabilityItems[$data->product->availability])',
            ),
            array(
                'name' => 'product_quantity',
                'type' => 'raw',
                'value' => 'Html::encode($data->product->quantity)',
            ),
            array(
                'name' => 'totalEmails'
            ),
            array(
                'class' => 'CLinkColumn',
                'label' => 'Отправить письмо',
                'urlExpression' => 'Yii::app()->createUrl("cart/admin/notify/send", array("product_id"=>$data->product_id))',
                'linkHtmlOptions' => array(
                    'confirm' => Yii::t('CartModule.core', 'Вы уверены?'),
                    'class'=>'btn btn-primary'
                )
            ),
            array(
                'class' => 'ButtonColumn',
                'template' => '{delete}',
            ),
        ),
    ));
    Yii::app()->tpl->closeWidget();
    ?>

<?php
if ($this->isAjax) echo Html::closeTag('div');