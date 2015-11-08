<?php

class BannedIPController extends CExtController {

    public function actionIndex() {
        $component = Yii::app()->getComponent('ipblocker');
        $c = $component->params;
        $msg = 'Время блокировки: <b>'.$c['banned_time'].' ('.$c['left_time'].')</b><br>Причина: '.$c['reason'].'';

        $result = array();
        $result['message']=$msg;
        $result['title']='Ваш IP адрес '.$component->userIP.' заблокирован!';
        $this->renderPartial('//layouts/core/layout',$result);
    }

}
