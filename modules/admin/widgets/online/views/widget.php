<div class="divider"><span></span></div>
<div class="numStats">
    <ul>
        <li><?= $online['totals']['guest'];?><span><?= Yii::t('core','CHECKUSERS',0)?></span></li>
        <li><?= $online['totals']['users'];?><span><?= Yii::t('core','CHECKUSERS',1)?></span></li>
        <li><?= $online['totals']['admin'];?><span><?= Yii::t('core','CHECKUSERS',2)?></span></li>
        <li><?= $online['totals']['bot'];?><span><?= Yii::t('core','CHECKUSERS',3)?></span></li>
        <li><?= $online['totals']['all'];?><span><b><?= Yii::t('core','TOTAL')?></b></span></li>
    </ul>

    <div class="clear"></div>
</div>

<div class="divider"><span></span></div>

<ul class="userList">
    <?php

    $this->widget('ListView', array(
        'dataProvider' => $model->search(),
        'itemView' => '_view',
        'template' => "{items}\n{pager}",
    ));
    ?>

</ul>
