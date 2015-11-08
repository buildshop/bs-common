<?php
$value = (isset($_GET['q']))?$_GET['q']:'';
?>

<div id="search-box" class="float-r">
    <?php echo CHtml::form(Yii::app()->controller->createUrl('/shop/category/search'),'post',array('id'=>'search-form')) ?>
    <input type="text" value="<?=$value?>" placeholder="Поиск..." name="q" id="searchQuery" />
    <span class="icon-medium icon-search"></span>
    <?php echo CHtml::endForm() ?>

</div>
<script>
    $(function(){
        $('#searchQuery').keydown(function(event){ 
            if (event.which == 13) {
                $('#search-form').submit();
            }
        });
    });
</script>