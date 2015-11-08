
<form action="" class="fluid">
    <div class="formRow">
        <div class="grid8"><label for="ShopProduct_sku"><?php echo Yii::t('ShopModule.admin', 'Изображения') ?></label></div>
        <div class="grid2"><input type="checkbox" name="copy[]" value="images" class="check" checked/></div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <div class="grid8"><label for="ShopProduct_sku"><?php echo Yii::t('ShopModule.admin', 'Варианты') ?></label></div>
        <div class="grid2"><input type="checkbox" name="copy[]" value="variants" class="check" checked/></div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <div class="grid8"><label for="ShopProduct_sku"><?php echo Yii::t('ShopModule.admin', 'Сопутствующие продукты') ?></label></div>
        <div class="grid2"><input type="checkbox" name="copy[]" value="related" class="check" checked/></div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <div class="grid8"><label for="ShopProduct_sku"><?php echo Yii::t('ShopModule.admin', 'Характеристики') ?></label></div>
        <div class="grid2"><input type="checkbox" name="copy[]" value="attributes" class="check" checked/></div>
        <div class="clear"></div>
    </div>
    <div class="formRow">
        <div class="grid8"><a href="javascript:void(0)" style="color: #309bbf" onclick="return checkAllDuplicateAttributes(this);">Отметить все</a></div>
        <div class="grid2"><input type="checkbox" value="1" class="check" checked="checked"/></div>
        <div class="clear"></div>
    </div>

</form>
<script>
    function checkAllDuplicateAttributes(el){
        if($(el).prev().attr('checked')){
            $('#duplicate_products_dialog form input').attr('checked', false);
            $(el).prev().attr('checked', false);
        }else{
            $('#duplicate_products_dialog form input').attr('checked', true);
            $(el).prev().attr('checked', true);
        }
    }
    init_uniform();
</script>