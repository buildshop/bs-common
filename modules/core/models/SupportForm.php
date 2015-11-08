<?php

class SupportForm extends CFormModel {

    /**
     * @var text 
     */
    public $text;

    /**
     * @var string 
     */
    public $theme;
    public $problem;
    public $receiverEmails;
    protected $_problems;
    protected $_config;

    public function getConfig() {
        return array('id' => __CLASS__,
            'showErrorSummary' => false,
            'attributes' => array(
                'class' => 'form-horizontal'
            ),
            'elements' => array(
                'receiverEmails' => array('type' => 'hidden'),
                'theme' => array('type' => 'text'),
                'problem' => array(
                    'type' => 'dropdownlist',
                    'items' => $this->problems
                ),
                'text' => array(
                    'type' => 'textarea',
                    'hint' => 'Опишите Вашу проблему как можно подробней'
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => Yii::t('app', 'SEND')
                )
            )
        );
    }

    public function rules() {
        return array(
            array('text, theme, receiverEmails, problem', 'required'),
        );
    }

    public function sendMail() {
        $server = Yii::app()->request->serverName;
        $body = '
            <b>Проблема:</b> ' . $this->problems[$this->problem] . '<br/>
            <b>Веб-сайт:</b> ' . $server . '<br/>
            <b>Отправитель:</b> ' . Yii::app()->user->login . ' (ID: ' . Yii::app()->user->id . ')<br/>
            <b>Сообщение:</b><br/>============<br/> ' . $this->text;
        $theme = $this->theme;

        $mailer = Yii::app()->mail;
        $mailer->From = Yii::app()->user->email;
        $mailer->FromName = Yii::app()->settings->get('core', 'site_name');
        $mailer->Subject = $theme;
        $mailer->Body = $body;
        foreach (implode(',', $this->receiverEmails) as $mail)
            $mailer->AddAddress($mail['email']);
        $mailer->AddReplyTo('noreply@cms.corner.com.ua');
        $mailer->isHtml(true);
        $mailer->Send();
        $mailer->ClearAddresses();
    }

    public function attributeLabels() {
        return array(
            'text' => 'Сообщение',
            'theme' => 'Тема',
            'problem' => 'Проблема'
        );
    }

    public function getProblems() {
        return array(
            'errorSite' => 'Ошибка в работе сайта',
            'errorModule' => 'Ошибка в работе модуля',
            'other' => 'Другое',
        );
    }

}