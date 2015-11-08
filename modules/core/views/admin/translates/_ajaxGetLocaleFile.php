
<div class="formRow grid3 noBorderB">
    <?php echo CHtml::dropDownList('file', '', $tree, array('empty' => '--- Выбор файла перевода ---', 'onchange'=>'ajaxTranslate("#translateContainer","ajaxOpen"); return false;')); ?>
</div>
