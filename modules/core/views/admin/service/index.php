<?php
$package = Yii::app()->package;
?>
<script>
    var plan = '<?= $package->plan ?>';
    if(plan=='pro'){
        var oneMonthPrice = 1000;
        var sixMonthPrice = 950;
        var yearMonthPrice = 900;
    }else if(plan=='standart'){
        var oneMonthPrice = 500;
        var sixMonthPrice = 480;
        var yearMonthPrice = 450;
    }else if(plan=='lite'){
        var oneMonthPrice = 160;
        var sixMonthPrice = 150;
        var yearMonthPrice = 140;
    }


    $(function(){
        $('#months').change(function(){
            var that = $(this).val();
            var t = $('#total');
            if(that >= 12){
                t.html(that * yearMonthPrice);
                console.log('year');
            }else if(that >= 6){
                t.html(that * sixMonthPrice); 
                console.log('six');
            }else{
                t.html(that * oneMonthPrice);
                console.log('one');
            }
        });
        
        
        $('select.payments').on('change', function() {
            var render = $(this).parent().parent().find('.render_payment');
            $.ajax({
                url:'/admin/core/payment/getPayment',
                data: {system:this.value,price:$('#total').text()},
                type:'POST',
                success:function(data){
                    render.html(data);
                }
            });
        });
        
    });

</script>

<div class="panel panel-default">
    <div class="panel-heading">132</div>
    <div class="panel-body">
        <table class="table table-striped">
            <tr>
                <th>Услуга</th>
                <th>Способ оплаты</th>
                <th></th>
            </tr>
            <tr>
                <td>Продление тарифного плана</td>
                <td>
                    <select class="payments" name="system">
                        <option value="privat24">Привать24</option>
                        <option value="webmoney">WebMoney</option>
                    </select>
                </td>
                <td>
                    <div class="render_payment"></div>
                </td>
            </tr>
        </table>
    </div>
</div>


<a class="payment payment-pb24" href="#"></a>
<a class="payment payment-webmoney" href="#"></a>





<div class="row">
    <div class="col-lg-5">
        Количество месяцов: <input type="text" id="months" name="months" value="1" class="form-control" />
        <div id="total" class="h3">0</div>
    </div>
</div>

<?php
if (Yii::app()->request->isAjaxRequest) {
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div', array('class' => 'wrapper'));
}
$data = LicenseCMS::run()->readData();
?>
<div class="fluid">
    <div class="grid6">
        <?php
        if (isset($data['support'])) {
            Yii::app()->tpl->openWidget(array('title' => 'Контактная информация'));
            echo $data['support']['address'];
            foreach ($data['support']['members'] as $members) {
                ?>


                <div class="tab_content">
                    <span class="icon-user"></span>
                    <?= $members['name'] ?> ( <?= $members['place'] ?>)
                    <br/>
                    <div>
                        <span class="icon-phone"></span> <?= $members['contact']['phone'] ?>
                    </div>
                    <div>
                        <span class="icon-envelope"></span> <?= $members['contact']['email'] ?>
                    </div>

                </div>
                <?php
            }
            Yii::app()->tpl->closeWidget();
        }

        if (isset($data['news'])) {
            Yii::app()->tpl->openWidget(array('title' => 'Новости'));

            foreach ($data['news'] as $news) {
                echo $news['title'];
            }

            Yii::app()->tpl->closeWidget();
        }
        ?>
    </div>
    <div class="grid6">
        <?php
        if (isset($providerProffers)) {
            Yii::app()->tpl->openWidget(array('title' => 'Предложения'));
            $this->widget('ext.adminList.GridView', array(//ext.adminList.GridView
                'dataProvider' => $providerProffers,
                'selectableRows' => false,
                'enableHeader' => false,
                'autoColumns' => false,
                'enablePagination' => true,
                'columns' => array(
                    array(
                        'name' => 'title',
                        'header' => 'Название файла',
                        'type' => 'html',
                        // 'value' => '$data->title',
                        'htmlOptions' => array('class' => 'textL'),
                    ),
                )
                    )
            );
            Yii::app()->tpl->closeWidget();
        }





        Yii::app()->tpl->openWidget(array('title' => 'Написать тех поддержки'));
        echo new CMSForm($supportForm->config, $supportForm);
        Yii::app()->tpl->closeWidget();
        ?>
    </div>
</div>
<div id="api-upgrade"></div>

<?php
if (Yii::app()->request->isAjaxRequest) {
    echo Html::closeTag('div');
}
?>





