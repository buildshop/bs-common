<?php

class MaintenanceController extends CExtController {

    public function actionIndex() {
        $result = array();
        $result['message']=Yii::app()->settings->get('core', 'site_close_text').'<br><br><a href="/admin/auth/">Войти</a>';
        $result['title']=Yii::app()->settings->get('core', 'site_name');
        $this->renderPartial('//layouts/core/layout',$result);
    }

}
