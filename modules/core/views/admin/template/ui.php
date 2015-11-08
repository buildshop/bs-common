<script>
    $(function() {
        $( "#slider-vertical" ).slider({
            orientation: "vertical",
            range: "min",
            min: 0,
            max: 100,
            value: 60,
            slide: function(event, ui) {
                var val = ui.value;
                $("#amount").css({'bottom':val-8+'%','position':'absolute','left':10,'background-color':'transparent'})
                $("#amount").val(val+'px');
            }
        });
        $("#amount").val($( "#slider-vertical" ).slider("value"));
        $( "#slider-vertical2" ).slider({
            orientation: "vertical",
            range: true,
            min: 0,
            max: 100,
            value: [ 17, 67 ],
            slide: function(event, ui) {
                var val = ui.value;
                $("#amount2").css({'bottom':val-8+'%','position':'absolute','left':10,'background-color':'transparent'})
                $("#amount2").val(val+'px');
            }
        });
        $("#amount2").val($( "#slider-vertical2" ).slider("value"));
    });
</script>
<?php
Yii::app()->tpl->openWidget(array(
    'title' => 'Шапка диалога',
    'htmlOptions' => array('class' => 'fluid')
));
?>
<div class="formRow">
    <div class="grid1">
        <div class="slider-container" style="position: relative;">
            Отступ сверху 
            <input type="text" id="amount">
            <div id="slider-vertical" class="  " style="height:100px;"></div>
        </div>
    </div>
    <div class="grid1">
        <div class="slider-container2" style="position: relative;">
            <input type="text" id="amount2">
            <div id="slider-vertical2" class="  " style="height:100px;"></div>
        </div>
    </div>
    <div class="clear"></div>
    </div>
<?php Yii::app()->tpl->closeWidget(); ?>


<?php
Yii::app()->tpl->openWidget(array(
    'title' => 'Less',
    'htmlOptions' => array('class' => 'fluid')
));
$less = Yii::app()->settings->get('less');

echo Html::form($this->createUrl('/admin/core/template/ui'), 'post', array('id' => 'user-login-form'));
?>
<div class="formRow">
    <div class="grid3">Цвет начало градиента</div>
    <div class="grid3"><?php
$this->widget('ext.colorpicker.ColorPicker', array(
    'name' => 'Less[btn-default-bgcolor]',
    'value' => $less['btn-default-bgcolor'],
    'selector' => 'Less_btn-default-bgcolor'
));
?></div>
    <div class="grid3">Цвет начало градиента</div>
    <div class="grid3">
        <?php
        $this->widget('ext.colorpicker.ColorPicker', array(
            'name' => 'Less[btn-primary-bgcolor]',
            'value' => $less['btn-primary-bgcolor'],
            'selector' => 'Less_btn-primary-bgcolor'
        ));
        ?>
    </div>
    <div class="grid3">
        <?php
        $this->widget('ext.colorpicker.ColorPicker', array(
            'name' => 'Less[btn-success-bgcolor]',
            'value' => $less['btn-success-bgcolor'],
            'selector' => 'Less_btn-success-bgcolor'
        ));
        ?>
    </div>
    <div class="grid3">
        <?php
        $this->widget('ext.colorpicker.ColorPicker', array(
            'name' => 'Less[btn-info-bgcolor]',
            'value' => $less['btn-info-bgcolor'],
            'selector' => 'Less_btn-info-bgcolor'
        ));
        ?>
    </div>
    <div class="grid3">
        <?php
        $this->widget('ext.colorpicker.ColorPicker', array(
            'name' => 'Less[btn-warning-bgcolor]',
            'value' => $less['btn-warning-bgcolor'],
            'selector' => 'Less_btn-warning-bgcolor'
        ));
        ?>
    </div>
    <div class="grid3">
        <?php
        $this->widget('ext.colorpicker.ColorPicker', array(
            //'mode'=>'flat',
            'name' => 'Less[btn-danger-bgcolor]',
            'value' => $less['btn-danger-bgcolor'],
            'selector' => 'Less_btn-danger-bgcolor'
        ));
        ?>
    </div>
    <div class="clear"></div>
</div>
<?php echo Html::submitButton('save'); ?>
<?php echo Html::endForm(); ?>
<?php Yii::app()->tpl->closeWidget(); ?>