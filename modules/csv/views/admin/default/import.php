
    <div class="formRow">

    <?php if ($importer->hasErrors()): ?>
        <div class="errorSummary"><p>Ошибки импорта:</p>
            <ul>
                <?php
                $i = 0;
                foreach ($importer->getErrors() as $error) {
                    if ($i < 10) {
                        if ($error['line'] > 0)
                            echo "<li>" . Yii::t('admin', 'Строка') . ": " . $error['line'] . ". " . $error['error'] . "</li>";
                        else
                            echo "<li>" . $error['error'] . "</li>";
                    }
                    else {
                        $n = count($importer->getErrors()) - $i;
                        echo '<li>' . Yii::t('admin', 'и еще({n}).', array('{n}' => $n)) . '</li>';
                        break;
                    }
                    $i++;
                }
                ?>
            </ul>
        </div>
            <?php endif; ?>

    <?php if ($importer->stats['date_create'] > 0 OR $importer->stats['date_update'] > 0) : ?>
        <div class="successSummary">
        <?php echo Yii::t('admin', 'Создано продуктов: ') . $importer->stats['date_create']; ?><br/>
        <?php echo Yii::t('admin', 'Обновлено продуктов: ') . $importer->stats['date_update']; ?>
        </div>
        <?php endif ?>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'fileUploadForm',
            'htmlOptions' => array('enctype' => 'multipart/form-data')
                ));
        ?>

    <div class="row">
        <input type="file" name="file" class="file">
        <input type="submit" value="<?php echo Yii::t('core', 'Начать импорт') ?>" class="buttonS bGreen">
    </div>

    <div class="row" style="height: 25px;">
        <label style="width: 300px"><input type="checkbox" name="create_dump" value="1" checked="checked" /> Создать резервную копию БД.</label>
    </div>

    <div class="row">
        <label style="width: 300px;"><input type="checkbox" name="remove_images" value="1" checked="checked" /> Удалить загруженные картинки</label>
    </div>

    <?php $this->endWidget(); ?>

    <div class="importDescription">
        <ul>
            <li><?php echo Yii::t('admin', 'Первой строкой файла должны быть указаны колонки для импорта.') ?></li>
            <li><?php echo Yii::t('admin', 'Разделитель поля - точка с запятой(;).') ?></li>
            <li><?php echo Yii::t('admin', 'Колонки name, category, type, price - обязательны.') ?></li>
            <li><?php echo Yii::t('admin', 'Файл должен иметь кодировку UTF-8 или CP1251.') ?></li>
        </ul>
        <br/>
        <a class="buttonS bBlue" href="<?php echo $this->createUrl('sample') ?>"><?php echo Yii::t('admin', 'Пример файла') ?></a>
    </div>
</div>

    <table class="tDefault checkAll tMedia">
        <?php
        foreach ($importer->getImportableAttributes() as $k => $v) {
            echo '<tr>';
            echo '<td width="200px">' . CHtml::encode($v) . '</td>';
            echo '<td>' . $k . '</td>';
            echo '</tr>';
        }
        ?>
    </table>




