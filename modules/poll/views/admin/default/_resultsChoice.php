    <div class="formRow">
    <?php echo CHtml::encode($choice->name); ?>
<span  style="float:right;font-size:11px">(<?php echo $voteCount; ?> <?php echo $voteCount == 1 ? 'голос' : 'голосов'; ?>) <?php echo $percent; ?>%</span>
<div class="contentProgress mt8">
<div id="bar2" class="<?php echo $color?> tipS" style="width: <?php echo $percent; ?>%;"></div>
</div>
</div>
