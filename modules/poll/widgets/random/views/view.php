<?php
$fn = new CPollHelper($model);
?>
<div class="text-center"><h4><?php echo $model->title ?></h4></div>
<?php $this->render('mod.poll.widgets.random.views.results', array('model' => $model)); ?>
<?php if(!is_object($userChoice)) { ?>
    Ваш голос: <strong><?php echo $fn->getUserVoted($userChoice) ?>.</strong>
<?php } ?>



