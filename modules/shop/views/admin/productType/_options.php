<?php
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->getModule('admin')->assetsUrl . '/js/jquery.dualListBox.js');
?>
<script>
    $(function(){
        $.configureBoxes({useFilters:false,useCounters:false});   
    });
</script>

<div class="form-group">
    <div class="col-sm-4"><?= Html::activeLabel($model, 'name', array('required' => true)); ?></div>
    <div class="col-sm-8"><?= Html::activeTextField($model, 'name', array('class' => 'form-control')); ?></div>

</div>

<div class="body row">
    <div class="leftBox col-lg-5">
        <?= Yii::t('ShopModule.admin', 'Атрибуты продукта') ?>
        <br />
        <?=
        Html::dropDownList('attributes[]', null, Html::listData($model->shopAttributes, 'id', 'title'), array(
            'id' => 'box2View',
            'multiple' => true,
            'class' => 'form-control attributesList',
            'style' => 'height:300px;'
        ));
        ?>
        <br/>
        <span id="box2Counter" class="countLabel"></span>
    </div>
    <div class="dualControl col-lg-2 text-center">
        <div class="btn-group">
            <button id="to2" type="button" class="dualBtn btn btn-default">&nbsp;&lt;&nbsp;</button>
            <button id="to1" type="button" class="dualBtn btn btn-default">&nbsp;&gt;&nbsp;</button>
        </div>
        <br/>
        <br/>
        <div class="btn-group">
            <button id="allTo2" type="button" class="dualBtn btn btn-default">&nbsp;&lt;&lt;&nbsp;</button>
            <button id="allTo1" type="button" class="dualBtn btn btn-default">&nbsp;&gt;&gt;&nbsp;</button>
        </div>
    </div>
    <div class="rightBox col-lg-5">
        <?= Yii::t('ShopModule.admin', 'Доступные атрибуты') ?><br />
        <?=
        Html::dropDownList('allAttributes', null, Html::listData($attributes, 'id', 'title'), array(
            'id' => 'box1View',
            'multiple' => true,
            'class' => 'form-control attributesList',
            'style' => 'height:300px;'
        ));
        ?>
        <br/>
        <span id="box1Counter" class="countLabel"></span>
    </div>
</div>

