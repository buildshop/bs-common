<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-7"><a class="lead" href="<?= $data->getUrl(); ?>"><?= Html::text($data->title); ?></a></div>
            <div class="col-md-2 text-right">

                <?php $this->widget('ext.admin.frontControl.FrontControlWidget', array('data' => $data)); ?>
            </div>
            <div class="col-md-3 text-right"><span class="icon-calendar-2"></span> <?= CMS::date($data->date_create) ?> <?php $this->widget('mod.users.widgets.favorites.FavoritesWidget', array('model' => $data)); ?></div>
        </div>
    </div>
    <div class="panel-body">
        <?= Html::text($data->short_text); ?>
    </div>
    <div class="panel-footer navbar-collapse collapse">
        <ul class="nav nav-pills">
            <li><span class="icon-user"></span> <?= $data->user->login ?></li>
            <li><span class="icon-folder-open"></span> <?php echo $data->category->name ?></li>
            <li><span class="icon-tags"></span> <?php echo implode(', ', $data->tagLinks); ?></li>
            <?php if (isset($data->commentsCount)) { ?>
            <li><span class="icon-bubbles-2"></span> (<?= $data->commentsCount; ?>)</li>
            <?php } ?>
        </ul>
    </div>
</div>
