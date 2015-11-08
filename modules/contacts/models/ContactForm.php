<?php

class ContactForm extends FormModel {

    const MODULE_ID = 'contacts';

    public $name;
    public $email;
    public $msg;
    public $phone;
    public $verifyCode;

    public function rules() {
        return array(
            array('name, email, msg, verifyCode', 'required'),
            array('email', 'match', 'pattern' => '/^[\da-z][-_\d\.a-z]*@(?:[\da-z][-_\da-z]*\.)+[a-z]{2,5}$/iu'),
            // array('verifyCode', 'required', 'on' => 'insert', 'message' => Yii::t('default', 'message.verifyCode.required')),
            array('verifyCode', 'captcha', 'allowEmpty' => !extension_loaded('gd')),
        );
    }

    public function attributeLabels() {
        $this->_attrLabels = array(
            'name' => Yii::t('ContactsModule.default', 'FIELD_NAME'),
            'email' => Yii::t('ContactsModule.default', 'FIELD_EMAIL'),
            'msg' => Yii::t('ContactsModule.default', 'FIELD_MSG'),
            'phone' => Yii::t('ContactsModule.default', 'FIELD_PHONE'),
        );
        return CMap::mergeArray($this->_attrLabels, parent::attributeLabels());
    }

    public function sendMessage() {
        $tplMail = TplMail::model()->findByAttributes(array('formkey' => 'CONTACT_FEEDBACK'));
        if (!$tplMail)
            throw new CHttpException(404, 'Не могу найти шаблон письма.');

        $config = Yii::app()->settings->get('contacts');
        $fromName = Yii::t('ContactsModule.default', 'FB_FROM_MESSAGE', array('{name}' => CHtml::encode($this->name)));
        $tplMail->emails=explode(',', $config['form_emails']);
        $tplMail->fromName=$fromName;
        $tplMail->sendEmail2(
                $this->replaceArray(), $this->replaceArray()
        );
    }

    protected function replaceArray() {
        $array = array();
        $array['%SENDER_NAME%'] = $this->name;
        $array['%SENDER_EMAIL%'] = $this->email;
        $array['%SENDER_MESSAGE%'] = CHtml::encode($this->msg);
        return $array;
    }

}