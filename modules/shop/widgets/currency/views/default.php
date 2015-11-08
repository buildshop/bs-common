<div class="currencies">
    <?php echo Yii::t('core', 'Валюта:'); ?>
    <?php
    foreach (Yii::app()->currency->currencies as $currency) {
        echo Html::ajaxLink($currency->symbol, '/shop/ajax/activateCurrency/' . $currency->id, array(
            'success' => 'js:function(){window.location.reload(true)}',
                ), array('id' => 'sw' . $currency->id, 'class' => Yii::app()->currency->active->id === $currency->id ? 'active' : ''));
    }
    ?>
</div>