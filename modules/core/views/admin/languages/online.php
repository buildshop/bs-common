<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => 'fluid123 ')));
?>
<div class="fluid body">
    <div class="grid5"><?php echo Html::dropDownList('from', 'ru', yandexTranslate::onlineLangs(), array('empty' => Yii::t('app', 'EMPTY_DROPDOWNLIST', 1))); ?>
        <br/>
        Введите текст:
        <br />
        <?php echo Html::textArea('text', null, array('class' => 'noresize')); ?></div>
    <div class="grid2 textC"><?php echo Html::button('Перевести >>', array('id' => 'submit', 'class' => 'buttonS bGreen', 'style' => 'margin-top:80px')); ?></div>
    <div class="grid5"><?php echo Html::dropDownList('to', 'en', yandexTranslate::onlineLangs(), array('empty' => Yii::t('app', 'EMPTY_DROPDOWNLIST', 1))); ?>
        <br/>
        Результат:
        <br />
        <?php echo Html::textArea('result', null, array('class' => 'noresize')); ?>
    </div>
    <div class="clear"></div>
</div>


<?php Yii::app()->tpl->closeWidget(); ?>


<script>
    $(function(){
        $('#submit').click(function(){
            $.ajax({
                url: "/admin/core/languages/ajaxOnlineTranslate",
                type:'POST',
                data: {
                    token:token,
                    text:$('#text').val(),
                    lang:[$('#from').val(),$('#to').val()]
                },
                beforeSend:function(){
                    $('#submit').attr('disabled',true).val('Загрузка...');
                },
                success: function(response) {
                    $('#result').val(response);
                    $('#submit').attr('disabled',false).val('Перевести >>');
                }
            });
        });
    });
</script>
