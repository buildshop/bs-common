<?php
/* @var $this AdminController */
/* @var $model News */
/* @var $form CActiveForm */
?>



<?php
Yii::app()->clientScript->registerScript('search', "

$('#search-form').submit(function(){
	$('#news-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'id' => 'search-form',
    'method' => 'get',
        ));
?>
    <div class="formRow fluid">
        <div class="grid2"><?php echo Yii::t('admin', 'Search') ?></div>
        <div class="grid10"><?php echo $form->textField($model, 'title_ru', array('placeholder' => Yii::t('admin', 'What search?'))); ?></div>
        <div class="clear"></div>
    </div>


<?php $this->endWidget(); ?>


