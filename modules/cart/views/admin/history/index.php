
<script>
    var currency = '<?= Yii::app()->currency->active->symbol; ?>';
    $(function(){
        total_price = function() {
            var sum = 0;
            $('.total_price').each(function() {
                sum += Number($(this).text());
            });
            return sum+' '+currency;
        };
        total_quantity = function() {
            var sum = 0;
            $('.quantity').each(function(key,index) {
                sum += Number($(this).text());
            });
            return sum;
        };
        total_price_purchase = function() {
            var sum = 0;
            $('.price_purchase').each(function(key,index) {
                var pr = $('.price:eq('+key+')').text();
                var total_price = $('.total_price:eq('+key+')').text();
                var pcs = $('.pcs:eq('+key+')').text();
                var q = $('.quantity:eq('+key+')').text();
                var price = Math.abs(Number(total_price - pr*pcs*q));
                if($(this).text() > 0){
                    sum += price;
                }else{
                    sum += 0;
                }
            
      
            });
            return sum+' '+currency;
        };
        $('#total_price').text(total_price);
        $('#total_quantity').text(total_quantity);
        $('#total_price_purchase').text(total_price_purchase);
        
        
        

        
    });

</script>
<div id="tester"></div>
<div class="widget">
    <div class="whead"><h6><?php echo $this->pageName ?></h6>

        <?php //if ($model->search(array('scope' => 'ordersMade'))->totalItemCount) { ?>
        <?= Html::form('/admin/cart/history/', 'GET', array('id' => 'filter_form', 'class' => 'floatR')); ?>
        <div class="row-form">

            <?php echo Html::activeLabelEx($form, 'from_date'); ?>

            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => CHtml::activeName($form, 'from_date'),
                'value' => $form->from_date,
                'options' => array(
                    'dateFormat' => "yy-mm-dd",
                ),
            ));
            ?></div>

        <div class="row-form">
            <?php echo CHtml::activeLabelEx($form, 'to_date'); ?>

            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => CHtml::activeName($form, 'to_date'),
                'value' => $form->to_date,
                'options' => array(
                    'dateFormat' => "yy-mm-dd",
                ),
            ));
            ?></div>

        <div class="row-form">
            <?php echo Html::link('<span class="icon-medium icon-arrow-right-2 colorWhite"></span>', 'javascript:void(0)', array('class' => 'btn bBlue ', 'onClick' => '$("#filter_form").submit()')); ?>
        </div> <div class="clear"></div>
            <?= Html::endForm();?>



        <div class="clear"></div></div>


    <?php
    if (isset($_GET['OrderProduct'])) {
        $supplier_id = $_GET['OrderProduct']['supplier_id'];
    } else {
        $supplier_id = null;
    }
    $this->widget('ext.fancybox.Fancybox', array('target' => 'td.image a'));
    $this->widget('ext.adminList.GridView', array(
        'ajaxUpdate' => false,
        'dataProvider' => $model->search(array('history' => true)),
        'filter' => $model,
        'filterCssClass' => 'tfilter',
        'enableCustomActions' => false,
        'selectableRows' => false,
        'columns' => array(
            //   array('class' => 'CheckBoxColumn'),
            array(
                'class' => 'SGridIdColumn',
                'type' => 'html',
                'htmlOptions' => array('class' => 'image'),
                'value' => '(!empty($data->prd->mainImage))?CHtml::link(CHtml::image($data->prd->mainImage->getUrl("50x50"),""),$data->prd->mainImage->getUrl("500x500")):"no image"'
            ),
            array(
                'name' => 'name',
                'value' => '$data->name',
                'htmlOptions' => array('class' => 'textL')
            ),
            array(
                'name' => 'order_id',
                'value' => '$data->order_id',
                'filter' => false
            ),
            array(
                'name' => 'categories',
                'type' => 'raw',
                'htmlOptions' => array('style' => 'width:100px'),
                'value' => '$data->getCategories()',
                'filter' => false
            ),
            array(
                'name' => 'supplier_id',
                //'type' => 'html',
                'value' => '$data->supplier_id ? Html::encode($data->supplier->name) : Yii::t("core", "NO")',
                'filter' => Html::dropDownList('OrderProduct[supplier_id]', $supplier_id, Html::listData(ShopSuppliers::model()->findAll(), "id", "name"), array('empty' => 'Все')),
            // 'filter' => CHtml::listData(ShopSuppliers::model()->findAll(), "id", "name"),
            ),
            array(
                // 'name' => 'quantity',
                'header' => 'Ящиков',
                'value' => '$data->quantity',
                'htmlOptions' => array('class' => 'quantity'),
                'filter' => false
            ),
            array(
                // 'name' => 'quantity',
                'header' => 'Пар в ящ.',
                'value' => '$data->prd->pcs',
                'htmlOptions' => array('class' => 'pcs'),
                'filter' => false
            ),
            array(
                'header' => 'Цена закупки',
                'value' => '$data->prd->price_purchase',
                'htmlOptions' => array('class' => 'price_purchase')
            ),
            array(
          'header' => 'Цена продажи',
          'value' => '$data->price',
          'htmlOptions' => array('class' => 'price'),
          'filter' => false
          ), 
            array(
                 'header' => 'Общ. сумма',
                'type' => 'raw',
                'value' => '$data->prd->price_purchase * $data->prd->pcs * $data->quantity',
                'htmlOptions' => array('class' => 'total_price'),
                'filter' => false
            ),


        /* array(
          'class' => 'ButtonColumn',
          'template' => '{delete}',
          ), */
        ),
    ));
    ?>

    <div class="clear"></div>
    <?php if ($model->search(array('scope' => 'ordersMade'))->totalItemCount) { ?>
        <div class="body" style="width:400px;float:right">
            <ul class="wInvoice">
                <li>
                    <h4 class="blue" id="total_quantity"></h4>
                    <span>Ящиков</span>
                </li>

                <li>
                    <h4 class="red" id="total_price"></h4>
                    <span>Оборот</span>
                </li>
                <li>
                    <h4 class="green" id="total_price_purchase"></h4>
                    <span>Доход</span>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    <?php } ?>
</div>
<script>
    
    function parseUrl( url ) {
        var a = document.createElement("a");
        a.href = url;
        return a;
    }

    $(function(){
        $('#save_pdf').click(function(){
            //  window.location.href='http://'+window.location.hostname+'/admin/shop/pdf/'+parseUrl($(this).attr("href")).search;
            //   alert(parseUrl($(this).attr("href")).search);


            window.open(
            'http://'+window.location.hostname+'/admin/shop/pdf/'+parseUrl($(this).attr("href")).search,
            '_blank' // <- This is what makes it open in a new window.
        );


            return false;
        });
       
    });
</script>