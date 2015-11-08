<div class="formRow grid3 noBorderB">
    <?php echo CHtml::dropDownList('module', '', $tree, array('empty' => '--- Выбор модуля ---', 'onchange' => 'ajaxTranslate("#localeID","ajaxGetLocale"); return false;')); ?>
</div>
<div id="localeID"></div>
