
<?php


	$handle = opendir(Yii::getPathOfAlias('webroot.themes.default.less'));
        $data=array();
	while ($file = readdir($handle)) {
            $preg = preg_match("/^(.+)\.less/", $file, $matches);
            	if ($preg) {
                  //  echo 'file: <b>'.$file.'</b>';
                     $data[] = array(
                         'filename' => Html::link($file, '/admin/core/template/less?file=' . $file), 
                        'url' => Html::link(Yii::t('core', 'Компилировать'), '/admin/core/database/delete?file=' . $file));
                }
        }
	closedir($handle);
        $data_less = new CArrayDataProvider($data, array(
            'sort' => array(
                'attributes' => array('filename'),
                'defaultOrder' => array('filename' => false),
            ),
                )
        );
        
$this->widget('ext.adminList.GridView', array(//ext.adminList.GridView
    'dataProvider' => $data_less,
    'selectableRows' => false,
    'enableHeader' => false,
    'autoColumns' => false,
    'enablePagination' => true,
    'columns' => array(
        array(
            'name' => 'filename',
            'header' => 'Название файла',
            'type' => 'raw',
            //'value' => 'Html::link(Html::encode($data->filename),"dsadasasd")',
            'htmlOptions' => array('class' => 'textL'),
        ),
        array(
            'name' => 'url',
            'class'=>'SGridIdColumn',
            'header' => Yii::t('core', 'OPTIONS'),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'textC'),
        ),
    )
        )
);








Yii::app()->tpl->openWidget(array(
    'title' => 'Less',
    'htmlOptions' => array('class' => 'fluid')
));
$less = Yii::app()->settings->get('less');

echo Html::form($this->createUrl('/admin/core/template'), 'post', array('id' => 'user-login-form'));

    
$gradient->renderForm();

?>
<div class="formRow">
    <div class="grid3">Цвет начало градиента</div>
    <div class="grid3"><?php
$this->widget('ext.colorpicker.ColorPicker', array(
    'name' => 'Less[btn-default-bgcolor]',
    'value' => $less['btn-default-bgcolor'],
    'selector' => 'Less_btn-default-bgcolor'
));
?></div>
    <div class="grid3">Цвет начало градиента</div>
    <div class="grid3">
        <?php
        $this->widget('ext.colorpicker.ColorPicker', array(
            'name' => 'Less[btn-primary-bgcolor]',
            'value' => $less['btn-primary-bgcolor'],
            'selector' => 'Less_btn-primary-bgcolor'
        ));
        ?>
    </div>
    <div class="grid3">
        <?php
        $this->widget('ext.colorpicker.ColorPicker', array(
            'name' => 'Less[btn-success-bgcolor]',
            'value' => $less['btn-success-bgcolor'],
            'selector' => 'Less_btn-success-bgcolor'
        ));
        ?>
    </div>
    <div class="grid3">
        <?php
        $this->widget('ext.colorpicker.ColorPicker', array(
            'name' => 'Less[btn-info-bgcolor]',
            'value' => $less['btn-info-bgcolor'],
            'selector' => 'Less_btn-info-bgcolor'
        ));
        ?>
    </div>
    <div class="grid3">
        <?php
        $this->widget('ext.colorpicker.ColorPicker', array(
            'name' => 'Less[btn-warning-bgcolor]',
            'value' => $less['btn-warning-bgcolor'],
            'selector' => 'Less_btn-warning-bgcolor'
        ));
        ?>
    </div>
    <div class="grid3">
<?php
$this->widget('ext.colorpicker.ColorPicker', array(
    //'mode'=>'flat',
    'name' => 'Less[btn-danger-bgcolor]',
    'value' => $less['btn-danger-bgcolor'],
    'selector' => 'Less_btn-danger-bgcolor'
));
?>
    </div>
    <div class="clear"></div>
</div>
<?php echo Html::submitButton('save'); ?>
<?php echo Html::endForm(); ?>
<?php Yii::app()->tpl->closeWidget(); ?>