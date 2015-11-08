
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'priceExportForm',
        ));
?>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th></th>
            <th><?= Yii::t('core', 'NAME') ?></th>
            <th><?= Yii::t('core', 'ID') ?></th>
        </tr>
    </thead>
    <?php
    foreach ($importer->getImportableAttributes('eav_') as $k => $v) {


        echo '<tr>';
        echo '<td align="left" width="10px"><input type="checkbox" checked name="attributes[]" value="' . $k . '"></td>';
        echo '<td align="left" class="textC">' . Html::encode($v) . '</td>';
        echo '<td align="left" class="textC">' . $k . '</td>';
        echo '</tr>';
    }
    ?>
</table>

<div class=text-center">
    <input type="submit" value="<?php echo Yii::t('XmlModule.admin', 'DOWNLOAD') ?>" class="btn btn-success">
</div>

<?php $this->endWidget(); ?>



