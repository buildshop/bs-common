<h3><?= Yii::t('CartModule.default','PAYMENT_METHODS');?></h3>
<?php
foreach ($paymenyMethods as $pay) {
    echo Html::activeRadioButton($form, 'payment_id', array(
        'checked' => ($form->payment_id == $pay->id),
        'uncheckValue' => null,
        'value' => $pay->id,
        'data-value' => Html::encode($pay->name),
        'id' => 'payment_id_' . $pay->id,
        'class' => 'payment_checkbox'
    ));
    echo Html::encode($pay->name);
}
?>
