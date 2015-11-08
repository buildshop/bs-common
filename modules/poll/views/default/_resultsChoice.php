<?= Html::encode($choice->name); ?>:
<div class="progress">
    <div class="progress-bar <?= $color?>" role="progressbar" aria-valuenow="<?= $percent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $percent; ?>%;">
        <?= $percent; ?>%
    </div>
</div>

