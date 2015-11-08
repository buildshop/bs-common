<script>
    $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    $(document).ready( function() {
        $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
        
            var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;
        
            if( input.length ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }
        
        });
    });
</script>

<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => '')
));
?>

<ul class="nav nav-tabs">
    <li role="presentation" class="active"><a href="#">Импорт</a></li>
    <li role="presentation"><a href="#">Экспорт</a></li>
</ul>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home">

        вфыфыв
    </div>

</div>

<div class="row">
    <div class="col-lg-12">
        <?php if ($importer->hasErrors()) { ?>
            <div class="alert alert-danger"><p>Ошибки импорта:</p>
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
        <?php } ?>

        <?php if ($importer->stats['create'] > 0 OR $importer->stats['update'] > 0) { ?>
            <div class="alert alert-success">
                <?php echo Yii::t('XmlModule.admin', 'CREATE_PRODUCTS', array('{n}' => $importer->stats['create'])); ?>
                <?php echo Yii::t('XmlModule.admin', 'UPDATE_PRODUCTS', array('{n}' => $importer->stats['update'])); ?>
            </div>
        <?php } ?>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'fileUploadForm',
            'htmlOptions' => array('enctype' => 'multipart/form-data')
                ));
        ?>

        <div class="row">





            <div class="col-lg-6 col-sm-6 col-12">
                <div class="input-group">
                    <span class="input-group-btn">
                        <span class="btn btn-primary btn-file">
                            Выбрать XML файл <input type="file" name="file">
                        </span>
                    </span>
                    <input type="text" class="form-control" readonly>
                    <span class="input-group-btn">
                        <input type="submit" value="<?php echo Yii::t('core', 'Начать импорт') ?>" class="btn btn-success">
                    </span>
                </div>



                <label style="width: 300px"><input type="checkbox" name="create_dump" value="1" /> Создать резервную копию БД.</label>



                <label style="width: 300px;"><input type="checkbox" name="remove_images" value="1" /> Удалить загруженные картинки</label>

            </div>



        </div>
        <?php $this->endWidget(); ?>

        <div class="btn-group">

            <a class="btn btn-info" href="<?php echo $this->createUrl('sample') ?>"><?= Yii::t('XmlModule.admin', 'DOWNLOAD') ?></a>
            <a class="btn btn-primary" role="button" data-toggle="collapse" href="#xmlguide" aria-expanded="false" aria-controls="collapseExample">
                Документация
            </a>





        </div>


        <div class="collapse2" id="xmlguide2">
            
            <?php $this->renderPartial('_documentation',array());?>
            <div class="row" style="display: none">
                <div class="col-lg-6">
                    <pre>
&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;products&gt;
    &lt;product&gt;
        ....One product...
    &lt;/product&gt;
    &lt;product&gt;
        ....Two product...
        &lt;attributes&gt;
            &lt;attribute name="{attr_name}"&gt;
                ....VALUE...
            &lt;/attribute&gt;
        &lt;/attributes&gt;
    &lt;/product&gt;
    ...etc...
&lt;/products&gt;
                    </pre>
                </div>
                <div class="col-lg-6">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th class="text-center">Название</th>
                            <th class="text-center">Тег</th>
                            <th class="text-center">Обязательный</th>
                        </tr>
                        <?php
                        foreach ($importer->getImportableAttributes() as $k => $v) {
                            $required = (in_array($k, $importer->requiredAttrs)) ? 'Да' : '';
                            echo '<tr>';
                            echo '<td width="200px">' . CHtml::encode($v) . '</td>';
                            echo '<td>' . $k . '</td>';
                            echo '<td class="text-center">' . $required . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>




</div>




<?php Yii::app()->tpl->closeWidget(); ?>





<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="panel with-nav-tabs panel-default">
                <div class="panel-heading">

                    <div class="panel-title pull-left">dsasdas</div>

                    <ul class="nav nav-tabs pull-right">
                        <li class="active"><a href="#import" data-toggle="tab">Импорт</a></li>
                        <li><a href="#export" data-toggle="tab">Экспорт</a></li>

                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body panel-body-static">
                    <div class="tab-content">
                        <div class="tab-pane  active" id="import">Default 1</div>
                        <div class="tab-pane " id="export">Default 2</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel with-nav-tabs panel-primary">
                <div class="panel-heading">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab1primary" data-toggle="tab">Primary 1</a></li>
                        <li><a href="#tab2primary" data-toggle="tab">Primary 2</a></li>
                        <li><a href="#tab3primary" data-toggle="tab">Primary 3</a></li>
                        <li class="dropdown">
                            <a href="#" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#tab4primary" data-toggle="tab">Primary 4</a></li>
                                <li><a href="#tab5primary" data-toggle="tab">Primary 5</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1primary">Primary 1</div>
                        <div class="tab-pane fade" id="tab2primary">Primary 2</div>
                        <div class="tab-pane fade" id="tab3primary">Primary 3</div>
                        <div class="tab-pane fade" id="tab4primary">Primary 4</div>
                        <div class="tab-pane fade" id="tab5primary">Primary 5</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
