<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title"><?php echo $title ?></div>
        <?php if (isset($options)) { ?>
            <div class="panel-option">
                <a data-toggle="dropdown" href="#">
                    <i class="flaticon-table-edit"></i>
                </a>
                <ul class="dropdown-menu pull-right">
                    <?php foreach ($options as $opt) { ?>
                        <li><?= Html::link('<span class="' . $opt['icon'] . '"></span>' . $opt['label'], $opt['href'], $opt['htmlOptions']) ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>
        <?php if (isset($buttons)) { ?>
            <?php foreach ($buttons as $btn) { ?>
                <?= Html::link($btn['label'], $btn['url'], $btn['htmlOptions']); ?>
            <?php } ?>
        <?php } ?>
    </div>
    <div class="panel-body">
