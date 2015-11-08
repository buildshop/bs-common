
<form action="/admin/news/default/update?id=<?= $model->id; ?>" method="POST" id="edit_mode">
    <h1 id="News[title]" class="edit_mode_title"><?= Html::text($model->title); ?></h1>
    <div class="date">
        <span class="icon-calendar-2"></span>
        <?= CMS::date($model->date_create) ?>
    </div>
    <div class="edit_mode_text" id="News[short_text]"><?= Html::text($model->short_text); ?></div>
    <div class="edit_mode_text" id="News[full_text]"><?= Html::text($model->full_text); ?></div>
</form>

<?php
if (Yii::app()->hasModule('comments')) {
    $this->widget('mod.comments.widgets.comment.CommentWidget', array('model' => $model));
}
?>


