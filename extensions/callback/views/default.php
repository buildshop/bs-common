
<?php

//index.php?r=main/ajax/widget.actionCallback
echo CHtml::ajaxLink(Yii::t('CallbackWidget.default', 'CALLBACK_BUTTON'), '/ajax/callback.action', array(
    'type' => 'GET',
    'beforeSend' => "function(){
              $.jGrowl('Загрузка...');
              $('body').append('<div id=\"callback-dialog\"></div>');
              }
    ",
    'success' => "function( data ){
        var result = data;
        $('#callback-dialog').dialog({
        model:true,
       // autoOpen: true,
        resizable: false,
        height: 'auto',
        minHeight: 95,
        title:'" . Yii::t('CallbackWidget.default', 'CALLBACK_TITLE') . "',
        width: 350,
        modal: true,
        open:function(){},
        close:function(){
            $('#callback-dialog').remove();
            $('#jGrowl').jGrowl('shutdown').remove();
        },
            buttons: false
        
        });
           $('#callback-dialog').html(result); 
        }",
    // 'data' => array('val1' => '1', 'val2' => '2'), // посылаем значения
    'cache' => 'false' // если нужно можно закэшировать
        ), array(// самое интересное
    // 'href' => Yii::app()->createUrl('ajax/new_link222'), // подменяет ссылку на другую
    'class' => "callback-button" // добавляем какой-нить класс для оформления
        )
);