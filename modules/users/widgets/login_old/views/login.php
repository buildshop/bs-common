<?php

Yii::import('mod.users.UsersModule');
if (Yii::app()->user->isGuest) {
    if (Yii::app()->settings->get('users', 'registration')) {
        echo CHtml::openTag('li');
        echo CHtml::link(Yii::t('UsersModule.default', 'REGISTRATION'), array('/users/register'), array('class' => 'frst'));
        echo CHtml::closeTag('li');
    }
    echo CHtml::openTag('li');
    echo CHtml::ajaxLink(Yii::t('UsersModule.default', 'ENTER'), Yii::app()->createUrl('/users/login/'), array(
        'type' => 'GET',
        'success' => "function( data ){
        var result = data;
        $('#login-form').dialog({
        model:true,
        // autoOpen: false,
height: 'auto',
title:'Авторизация',
width: 350,
modal: true,
resizable: false,
open:function(){

},
close:function(){

},
            buttons: [{
                text: 'Войти',
                click: function() {
                    ajaxButtonSubmit();
                }
            }]
        
        });
           $('#login-form').html(result); 
        }",
        // 'data' => array('val1' => '1', 'val2' => '2'), // посылаем значения
        'cache' => 'false' // если нужно можно закэшировать
            ), array(// самое интересное
            // 'href' => Yii::app()->createUrl('ajax/new_link222'), // подменяет ссылку на другую
            // 'class' => "sadfsadfsadclass" // добавляем какой-нить класс для оформления
            )
    );
    echo CHtml::closeTag('li');
} else {
    $name = !empty(Yii::app()->user->email) ? Yii::app()->user->email : Yii::app()->user->username;
    echo CHtml::openTag('li', array('id' => 'userString'));
    echo CHtml::link($name, Yii::app()->createUrl('/users/profile/'));
    echo CHtml::closeTag('li');
    echo CHtml::openTag('li');
    echo CHtml::link(Yii::t('core', 'LOGOUT'), Yii::app()->createUrl('/users/logout/'));
    echo CHtml::closeTag('li');

    if (Yii::app()->user->isSuperuser) {
        echo CHtml::openTag('li');
        echo CHtml::link(Yii::t('default', 'ADMIN_PANEL'), array('/admin/'));
        echo CHtml::closeTag('li');
    }
}
?>

