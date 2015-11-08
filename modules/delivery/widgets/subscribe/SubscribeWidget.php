<?php

/**
 * Install to main/AjaxController actions 
 * public function actions() {
 *      return array(
 *          ...
 *          'subscribe.' => 'mod.delivery.widgets.subscribe.SubscribeWidget',
 *      );
 *  }
 * @return URL /ajax/subscribe.action
 * 
 */
class SubscribeWidget extends CWidget {

    /**
     * Action
     */
    public static function actions() {
        return array(
            'action' => 'mod.delivery.widgets.subscribe.actions.SubscribeAction',
        );
    }

    /**
     * Run widget
     */
    public function run() {
        Yii::import('mod.delivery.models.Delivery');
        $model = new Delivery();
        if (Yii::app()->user->isGuest && Yii::app()->hasModule('delivery'))
            $this->render($this->skin, array('model' => $model));
    }

}

?>
