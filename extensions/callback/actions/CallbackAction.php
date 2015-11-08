<?php

/**
 * @author Andrew (panix) S. <andrew.panix@gmail.com>
 * 
 * @param array $receiverMail Массив получатилей.
 * @uses CAction
 * @uses CallbackForm Модель формы
 */
Yii::import('ext.callback.CallbackForm');

class CallbackAction extends CAction {

    public $receiverMail = array('andrew.panix@gmail.com');

    public function run() {
        if (Yii::app()->request->isAjaxRequest) {
            $model = new CallbackForm();
            $sended = false;
            if (isset($_POST['CallbackForm'])) {
                $model->attributes = $_POST['CallbackForm'];
                if ($model->validate()) {
                    $sended = true;
                    $this->sendMessage($model);
                    $model->unsetAttributes();
                }
            }
            $this->controller->render('ext.callback.views._form', array(
                'model' => $model,
                'sended' => $sended
            ));
        } else {
            throw new CHttpException(403);
        }
    }

    /**
     * Оптравка письма на почту получателей.
     * @param CallbackForm $model
     */
    public function sendMessage($model) {

        $request = Yii::app()->request;
        $body = '<html>
            <body>
            <p>Телефон: <b>' . $model->phone . '</b></p>
            <p>Дата отправки: <b>' . date('Y-m-d H:i:s') . '</b></p>
            <br/>
            <br/>
            <p>IP-address: <b>' . $request->userHostAddress . '</b></p>
            <p>User agent: <b>' . $request->userAgent . '</b></p>
            </body>
            </html>';


        $config = Yii::app()->settings->get('core');
        $mailer = Yii::app()->mail;
        $mailer->From = 'noreply@' . $request->serverName;
        $mailer->FromName = $config['site_name'];
        $mailer->Subject = Yii::t('CallbackWidget.default', 'CALLBACK_TITLE');
        $mailer->Body = $body;
        foreach ($this->receiverMail as $mail) {
            $mailer->AddAddress($mail);
        }
        $mailer->AddReplyTo('noreply@' . $request->serverName);
        $mailer->isHtml(true);
        $mailer->Send();
        $mailer->ClearAddresses();
    }

}