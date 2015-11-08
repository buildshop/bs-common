<?php
$this->widget('ext.fancybox.Fancybox', array('target' => 'td.image a'));

Yii::app()->clientScript->registerScript('i18n', '
	var deleteQuestion = "' . Yii::t('CartModule.admin', 'Вы действительно удалить запись?') . '";
	var productSuccessAddedToOrder = "' . Yii::t('CartModule.admin', 'Продукт успешно добавлен к заказу.') . '";
', CClientScript::POS_BEGIN);

$this->widget('ext.adminList.GridView', array(
    'id' => 'orderedProducts',
    'enableHeader' => true,
    'name' => Yii::t('CartModule.admin', 'Продукты'),
    'headerButtons' => array(
        array(
            'label' => Yii::t('CartModule.admin', 'CREATE_PRODUCT'),
            'url' => 'javascript:openAddProductDialog(' . $model->id . ');',
            'htmlOptions' => array('class' => 'btn btn-success')
        ),

    ),
    'enableSorting' => false,
    'enablePagination' => false,
    'dataProvider' => $model->getOrderedProducts(),
    'selectableRows' => 0,
    'template' => '{items}',
));
?>

<script type="text/javascript">
    var orderTotalPrice = '<?php echo $model->total_price ?>';
    $(function(){
        var total_pcs = function() {
            var sum = 0;
            $('.quantity').each(function(key,index) {
                sum += Number($(this).text());
            });
            return sum;
        };
        $('#total_pcs').text(total_pcs);
    });
</script>


<ul class="list-group">
    <li class="list-group-item"><?= Yii::t('CartModule.admin', 'WHOLESALE', Yii::app()->settings->get('shop', 'wholesale')) ?> <span class="pull-right label label-lg label-default" id="total_pcs"></span></li>
    <?php if ($model->delivery_price > 0) { ?>
        <li class="list-group-item"> <?= Yii::t('CartModule.Order', 'DELIVERY_PRICE') ?>
            <span class="pull-right label label-lg label-info"><?= ShopProduct::formatPrice($model->delivery_price); ?> <?= Yii::app()->currency->main->symbol; ?></span>

        </li>
        <li class="list-group-item">
            <?= Yii::t('CartModule.admin', 'Сумма товаров') ?>
            <span class="pull-right label label-lg label-info"><?= ShopProduct::formatPrice($model->total_price) ?> <?= Yii::app()->currency->main->symbol ?></span>

        </li>
    <?php } ?>
    <li class="list-group-item"><b><?= Yii::t('CartModule.admin', 'FOR_PAYMENT') ?></b> <span class="pull-right label label-lg label-success"><?= ShopProduct::formatPrice($model->full_price) ?> <?= Yii::app()->currency->main->symbol ?></span></li>
</ul>
