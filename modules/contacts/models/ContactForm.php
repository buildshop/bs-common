<?php

class ContactForm extends FormModel {

    const MODULE_ID = 'contacts';

    public $name;
    public $email;
    public $msg;
    public $phone;
    public $verifyCode;

    public function myCaptcha($attr, $params) {
        if (Yii::app()->request->isAjaxRequest)
            return;

        CValidator::createValidator('captcha', $this, $attr, $params)->validate($this);
    }

    public function rules() {
        $rules = array();
        if (Yii::app()->settings->get('contacts', 'enable_captcha')) {
            $rules['captcha'][] = array('verifyCode', 'required');
            $rules['captcha'][] = array('verifyCode', 'required', 'on' => 'insert', 'message' => Yii::t('default', 'message.verifyCode.required'));
            $rules['captcha'][] = array('verifyCode', 'myCaptcha', 'allowEmpty' => !extension_loaded('gd'));
        }
        return CMap::mergeArray(array(
                    array('email, msg, phone, name', 'required'),
                    array('email', 'match', 'pattern' => '/^[\da-z][-_\d\.a-z]*@(?:[\da-z][-_\da-z]*\.)+[a-z]{2,5}$/iu'),
                        ), $rules['captcha']);
    }

    public function sendMessage() {
        $config = Yii::app()->settings->get('contacts');
        $mails = explode(',', $config['form_emails']);
        $tempArray = array(
            '{sender_name}' => $this->name,
            '{sender_email}' => $this->email,
            '{sender_message}' => CHtml::encode($this->msg),
            '{sender_phone}' => $this->phone
        );
        $mailer = Yii::app()->mail;
        $mailer->From = 'noreply@' . Yii::app()->request->serverName;
        $mailer->FromName = Yii::t('ContactsModule.default', 'FB_FORM_NAME');
        $mailer->Subject = Yii::t('ContactsModule.default', 'FB_FROM_MESSAGE', array(
                    '{name}' => (isset($this->name)) ? Html::encode($this->name) : $this->email,
                    '{site_name}' => Yii::app()->settings->get('core', 'site_name')
        ));
        $mailer->Body = Html::text(CMS::textReplace($config['tempMessage'], $tempArray));
        foreach ($mails as $mail) {
            $mailer->AddAddress($mail);
        }
        $mailer->isHtml(true);
        $mailer->AddReplyTo($this->email);
        $mailer->Send();
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] == 'contact_form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
