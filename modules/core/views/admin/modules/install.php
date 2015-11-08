<?php
Yii::app()->tpl->openWidget(array('title' => $this->pageName));
?>

<?php if (!empty($modules)) { ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th><?= Yii::t('app', 'NAME') ?></th>
                <th><?= Yii::t('app', 'DESCRIPTION') ?></th>
                <th><?= Yii::t('app', 'VERSION') ?></th>
                <th><?= Yii::t('app', 'AUTHOR') ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($modules as $module => $info) { ?>
                <tr>
                    <td class="text-center"><i class="<?= $info['icon'] ?>"></i></td>
                    <td><?= Html::encode($info['name']) ?></td>
                    <td><?= $info['description'] ?></td>
                    <td class="text-center"><?= $info['version'] ?></td>
                    <td class="text-center"><?= $info['author'] ?></td>
                    <td class="text-center"><?= Html::link(Yii::t('app', 'INSTALLED'), $this->createUrl('install', array('name' => $module)), array('class' => 'btn btn-success btn-sm')) ?></td>
                </tr>
            <?php } ?>
        </tbody></table>
<?php } else { ?>
    <?php Yii::t('app', 'NO_MODULES_INSTALL') ?>
<?php } ?>

<?php Yii::app()->tpl->closeWidget(); ?>


