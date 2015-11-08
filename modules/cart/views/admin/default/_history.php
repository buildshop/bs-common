<div class="clearfix"></div>
<?php
$history = $model->getHistory();

if (empty($history)) {

    echo Yii::app()->tpl->alert('info', Yii::t('CartModule.admin', 'HISTORY_EMPTY'), false);
    return;
}
?>

<table class="table table-striped">
    <thead>
        <tr>
            <th></th>
            <th><?= Yii::t('CartModule.admin', 'BEFORE'); ?></th>
            <th><?= Yii::t('CartModule.admin', 'AFTER'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($history as $row) {

            $this->renderPartial('_history_' . $row->handler, array(
                'data' => $row,
            ));
        }
        ?>
    </tbody>
</table>