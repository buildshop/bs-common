<div class="formRow grid3 noBorderB">
    <?php echo CHtml::dropDownList('locale', '', $tree, array('empty' => '--- Выбор ---', 'onchange' => 'ajaxTranslate("#localeID","ajaxGetLocaleFile"); return false;')); ?>
</div>
<div id="localeID"></div>
