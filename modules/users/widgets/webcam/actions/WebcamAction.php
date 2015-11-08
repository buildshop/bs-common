<?php

/**
 * @author Andrew (panix) S. <andrew.panix@gmail.com>
 * 
 * @param array $receiverMail Массив получатилей.
 * @uses CAction
 * @uses CallbackForm Модель формы
 */
Yii::import('mod.users.widgets.webcam.Webcam');

class WebcamAction extends CAction {

    public function run() {

        Yii::app()->request->enableCsrfValidation = false;
        //if(Yii::app()->request->isAjaxRequest){

        $this->controller->render('mod.users.widgets.webcam.views.render');
        // }else{
        // throw new CHttpException(401);
        // }
    }

}