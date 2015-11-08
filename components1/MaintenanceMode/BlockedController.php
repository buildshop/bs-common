<?php

class BlockedController extends CExtController {

    public function actionExpired() {
        $result = array();
        $result['message'] = Yii::t('app', 'BLOCKED_EXPIRED_MESSAGE');
        $result['title'] = Yii::app()->settings->get('core', 'site_name');
        $this->renderPartial("root.core.views.layouts.blocked", $result);
    }

    public function actionDemo() {
        $result = array();
        $result['message'] = Yii::t('app', 'BLOCKED_DEMO_MESSAGE');
        $result['title'] = Yii::app()->settings->get('core', 'site_name');
        $this->renderPartial("root.core.views.layouts.blocked", $result);
    }

}
