
<h1><?= Html::text($model->title); ?></h1><?php echo $model->category->name; ?>
<div class="date">
    <span class="icon-calendar-2"></span>
    <?= CMS::date($model->date_create) ?>
</div>
<?= Html::text($model->short_text); ?>
<?= Html::text($model->full_text); ?>

<?php
//$this->widget('Rating', array('model'=>$model));
?>
<?php

if (Yii::app()->hasModule('comments')) {
    $this->widget('mod.comments.widgets.comment.CommentWidget', array('model' => $model));
}
?>


