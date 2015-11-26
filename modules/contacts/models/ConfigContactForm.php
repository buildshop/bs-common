<?php

class ConfigContactForm extends FormModel {

    const MODULE_ID = 'contacts';

    public $sendMail;
    public $address;
    public $tempMessage;
    public $phone;
    public $skype;
    public $form_emails;
    public $enable_captcha;

    public static function defaultSettings() {
        return array(
            'form_emails' => 'andrew.panix@gmail.com',
            'tempMessage' => '<p>Имя отправителя: <strong>{sender_name}</strong></p>
                <p>Email отправитиля: <strong>{sender_email}</strong></p>
                <p>Телефон: <strong>{sender_phone}</strong></p>
                <p>==============================</p>
                <p><strong>Сообщение:</strong></p>
                <p>{sender_message}</p>',
            'address' => '',
            'phone' => '',
            'skype' => '',
            'enable_captcha'=>1
        );
    }

    public function getForm() {
        Yii::app()->controller->widget('ext.tinymce.TinymceWidget');
        Yii::import('ext.TagInput');
        return new TabForm(array(
                    'attributes' => array(
                        'id' => __CLASS__,
                        'class' => 'form-horizontal',
                    ),
                    'showErrorSummary' => true,
                    'elements' => array(
                        'general' => array(
                            'type' => 'form',
                            'title' => self::t('TAB_GENERAL'),
                            'elements' => array(
                                'skype' => array('type' => 'text'),
                                'phone' => array('type' => 'text'),
                                'address' => array('type' => 'text'),
                            ),
                        ),
                        'form_feedback' => array(
                            'type' => 'form',
                            'title' => self::t('TAB_FB'),
                            'elements' => array(
                                'form_emails' => array('type' => 'TagInput'),
                                'tempMessage' => array('type' => 'textarea', 'class' => 'editor'),
                                  'enable_captcha' => array('type' => 'checkbox'),
                            ),
                        ),
                    ),
                    'buttons' => array(
                        'submit' => array(
                            'type' => 'submit',
                            'label' => Yii::t('app', 'SAVE'),
                            'class' => 'btn btn-success',
                        )
                    )
                        ), $this);
    }

    public function init() {
        $this->attributes = Yii::app()->settings->get('contacts');
    }

    public function rules() {
        return array(
                        array('enable_captcha', 'boolean'),
            array('form_emails, tempMessage', 'required'),
            array('tempMessage, address, phone, skype', 'type', 'type' => 'string'),
            array('sendMail', 'match', 'pattern' => '/^[\da-z][-_\d\.a-z]*@(?:[\da-z][-_\da-z]*\.)+[a-z]{2,5}$/iu'),
        );
    }

    public function save($message = true) {
        Yii::app()->settings->set('contacts', $this->attributes);
        parent::save($message);
    }

}

?>