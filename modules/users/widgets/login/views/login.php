<?php
Yii::import('mod.users.UsersModule');
if (Yii::app()->user->isGuest) {
    if (Yii::app()->settings->get('users', 'registration')) {
        echo Html::openTag('li');
        $iconReg = (isset($this->icon_register)) ? '<i class="' . $this->icon_register . '"></i>' : '';
        echo Html::link($iconReg . Yii::t('UsersModule.default', 'REGISTRATION'), array('/users/register'), array('class' => 'frst'));
        echo Html::closeTag('li');
    }
    $iconLogin = (isset($this->icon_login)) ? '<i class="' . $this->icon_login . '"></i>' : '';
    echo Html::openTag('li');
    echo Html::ajaxLink($iconLogin . Yii::t('UsersModule.default', 'ENTER'), Yii::app()->createUrl('/users/login/'), array(
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
$('#login-form').keypress(function(e) {
    if (e.keyCode == $.ui.keyCode.ENTER) {
          login();
    }
});
},
close:function(){

},
            /*buttons: [
            {
                text: '" . Yii::t('UsersModule.default', 'BTN_LOGIN') . "',
                'class':'btn btn-success',
                click: function() {
                    login();
                }
            }

            ]*/
        
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
    echo Html::closeTag('li');
} else {
    ?>
    <li class="dropdown dropdown-small">
        <a href="#" class="dropdown-toggle" data-hover="dropdown" data-toggle="dropdown"><i class="icon fa fa-user"></i><?= $this->username ?> <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li><?= Html::link('<span class="icon-user"></span>' . Yii::t('default', 'PROFILE'), array('/users/profile/')); ?></li>
            <li><?= Html::link('<span class="icon-cart-3"></span>' . Yii::t('default', 'Мои заказы'), array('/users/profile/orders/')); ?></li>

            <?php
            if (Yii::app()->user->isSuperuser) {
                echo Html::openTag('li');
                echo Html::link('<span class="icon-wrench"></span>' . Yii::t('default', 'ADMIN_PANEL'), array('/admin/'));
                echo Html::closeTag('li');
            }
            ?>
            <li><?= Html::link('<span class="icon-exit"></span>' . Yii::t('app', 'LOGOUT'), Yii::app()->createUrl('/users/logout/')); ?></li>
        </ul>
    </li>
    <?php
}
?>

