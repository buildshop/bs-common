
<?php
Yii::app()->tpl->openWidget(array(
    'title' => 'Управлние переводами',
    'htmlOptions' => array('class' => 'fluid')
));
?>
<form method="post" action="/" id="translate-choose-form">
    <div class="formRow grid3 noBorderB">
        <?php
        echo CHtml::dropDownList('type', '', array(
            'core' => 'Системные', 'modules' => 'Модули'), array(
            'empty' => '--- Выбор переводов ---',
            'onchange' => 'ajaxTranslate("#typeID","ajaxGet"); return false;'));
        ?>

    </div>
    <div id="typeID"></div>
</form>
<?php
Yii::app()->tpl->closeWidget();
?>


<div id="translateContainer"></div>







<script>
    function ajaxTranslate(selector,action){
        $.ajax({
            type:'POST',
            url:'/admin/core/translates/'+action,
            data:$('#translate-choose-form').serialize(),
            success:function(result){
                $(selector).html(result);
                init_uniform();
            },
            error:function(){
                $('#translateContainer').html('');
                $(selector).html('');
                $.jGrowl('Ошибка');

            },
            beforeSend:function(){
                $('#translateContainer').html('');
               $.jGrowl('Загрузка...');
               $(selector).text('Загрузка...');
            }
        });

    }
</script>