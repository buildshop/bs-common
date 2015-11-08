<div class="clearfix"></div>
<?php if (isset($product)) { ?>
    <table class="table table-striped" id="relatedProductsTable">
        <?php foreach ($product->relatedProducts as $related) { ?>
            <tr>
            <input type="hidden" value="<?php echo $related->id ?>" name="RelatedProductId[]">
            <td class="relatedProductLine<?php echo $related->id ?>"><?php echo $related->id ?></td>
            <td><?php
        echo Html::link($related->name, array('admin/products/update', 'id' => $related->id), array(
            'target' => '_blank'
        ));
            ?></td>
            <td><a href="#" class="btn btn-danger btn-sm" onclick="$(this).parents('tr').remove();"><?php echo Yii::t('app', 'DELETE', 0) ?></a></td>
        </tr>
    <?php } ?>
    </table>
    <br/><br/>
<?php } ?>



<?php
/**
 * Related products tab
 */
Yii::app()->clientScript->registerScript("rti18n", strtr("var deleteButtonText='{text}';", array(
            '{text}' => Yii::t('app', 'DELETE'),
        )), CClientScript::POS_HEAD);

Yii::app()->getClientScript()->registerScriptFile($this->module->assetsUrl . '/admin/relatedProductsTab.js');

if (!isset($model)) {
    $model = new ShopProduct('search');
    $model->exclude = $exclude;
}

// Fix sort and pagination urls
$dataProvider = $model->search();
$dataProvider->sort->route = 'applyProductsFilter';
$dataProvider->pagination->route = 'applyProductsFilter';

$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    'ajaxUrl' => Yii::app()->createUrl('/shop/admin/products/applyProductsFilter', array('exclude' => $exclude)),
    'id' => 'RelatedProductsGrid',
    'genId' => false,
    'template' => '{items}{summary}',
    'enableCustomActions' => false,
    'autoColumns' => false,
    'enableHeader' => false,
    'enableHistory' => false,
    'selectableRows' => 0,
    //'filter'             => $model,
    'columns' => array(
        array(
            'name' => 'id',
            'type' => 'text',
            'value' => '$data->id',
            'filter' => Html::textField('RelatedProducts[id]', $model->id)
        ),
        array(
            'name' => 'image',
            'type' => 'html',
            'htmlOptions' => array('class' => 'image'),
            'filter' => false,
            'value' => '(!empty($data->mainImage))?Html::link(Html::image($data->mainImage->getUrl("50x50"),""),$data->mainImage->getUrl("500x500"),array("class"=>"small-thumbnail")):"no image"'
        ),
        array(
            'name' => 'name',
            'type' => 'raw',
            'value' => 'Html::link(Html::encode($data->name), array("update", "id"=>$data->id), array("target"=>"_blank"))',
            'filter' => Html::textField('RelatedProducts[name]', $model->name)
        ),
        array(
            'name' => 'sku',
            'value' => '$data->sku',
            'filter' => Html::textField('RelatedProducts[sku]', $model->sku)
        ),
        array(
            'name' => 'price',
            'value' => '$data->price',
            'filter' => Html::textField('RelatedProducts[price]', $model->price)
        ),
        array(
            'class' => 'CLinkColumn',
            'header' => '',
            'label' => Yii::t('app', 'CREATE', 0),
            'linkHtmlOptions'=>array(
              'class'=>'btn btn-success btn-sm'  
            ),
            'urlExpression' => '$data->id."/".Html::encode($data->name)',
            'htmlOptions' => array(
                'onClick' => 'return AddRelatedProduct(this);',
               
            ),
        ),
    ),
));
