<?php

class CTimeline extends CComponent {

    public function init() {
        
    }

    public function set($message, $params = array()) {
        $browser = new Browser();

        $model = new Timeline;
        $model->user_id = Yii::app()->user->id;
        $model->message = Yii::t('timeline', $message, $params);
        $model->user_agent = $browser->getUserAgent();
        $model->ip = CMS::getip();
        $model->user_platform = $browser->getPlatform();
        $model->save(false, false, false);
    }

}
