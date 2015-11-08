<?php

class SubscribeAction extends CAction {

    /**
     * Subscribe action
     * @throws CHttpException
     */
    public function run() {
        if (Yii::app()->request->isAjaxRequest) {
            $model = new Delivery();
            if (isset($_POST['Delivery'])) {
                $model->attributes = $_POST['Delivery'];
                if ($model->validate()) {
                    $model->save();
                    Yii::app()->user->setFlash('success', Yii::t('SubscribeWidget.default', 'SUBSCRIBE_SUCCESS'));
                }
            }
            $this->controller->render('mod.delivery.widgets.subscribe.views._subscribe', array('model' => $model));
        } else {
            throw new CHttpException(403);
        }
    }

}