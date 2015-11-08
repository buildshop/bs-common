

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'notify-form',
    'action' => '/notify/index',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => '')
        ));
?>
<input type="hidden" name="product_id" value="<?= $product->id ?>">
<div class="formRow noBorderB">
    <div class="grid3"><?php echo $form->labelEx($model, 'email') ?></div>
    <div class="grid9"><?php echo $form->textField($model, 'email') ?>
        <?php echo $form->error($model, 'email') ?></div>
    <div class="clear"></div>
</div>
<?php $this->endWidget(); ?>

