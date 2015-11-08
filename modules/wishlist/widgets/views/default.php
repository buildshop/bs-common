<div class="btn-group">
    <span class="btn btn-xs btn-default disabled"><span class="icon-heart"></span></span>
    <?= Html::link('В список желаний', 'javascript:wishlist.add(' . $pk . ');', array('class' => 'btn btn-xs btn-info')); ?>
</div>
