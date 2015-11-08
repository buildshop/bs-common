
<div class="formRow grid3 noBorderB">
    <?php echo CHtml::dropDownList('locale', '', $array, array('empty' => '--- Выбор языка ---', 'onchange' => 'ajaxTranslate("#filesID","ajaxGetLocaleFile"); return false;')); ?>
</div>
<div id="filesID"></div>

