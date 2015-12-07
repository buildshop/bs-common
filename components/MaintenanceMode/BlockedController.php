<?php

class BlockedController extends CExtController {

    public function actionIndex() {
        $result = array();
        $result['message']=Yii::t('app','BLOCKED_DEMO_MESSAGE');
        $result['title']=Yii::app()->settings->get('core', 'site_name');
        $this->renderPartial('common.views.layouts.blocked',$result); //root.

    }
    
    public function actionExpired() {
        $result = array();
        $result['message']=Yii::t('app','Время истекло');
        $result['title']=Yii::app()->settings->get('core', 'site_name');
        $this->renderPartial('common.views.layouts.blocked',$result); //root.

    }

}
