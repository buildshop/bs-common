<?php

class TplMail extends ActiveRecord {

    const MODULE_ID = 'core';

    public $setOptions = array();
    public $emails;
    public $fromName = false;

    public function getForm() {
        Yii::app()->controller->widget('ext.tinymce.TinymceWidget');
        return new CMSForm(array(
                    'attributes' => array(
                        'class' => 'form-horizontal',
                        'id' => __CLASS__,
                    ),
                    'showErrorSummary' => true,
                    'elements' => array(
                        'formkey' => array(
                            'type' => 'text',
                        ),
                        'subject' => array(
                            'type' => 'text',
                        ),
                        'text' => array('type' => 'textarea', 'class' => 'editor'),
                    ),
                    'buttons' => array(
                        'submit' => array(
                            'type' => 'submit',
                            'class' => 'btn btn-success',
                            'label' => Yii::t('app', 'SAVE')
                        )
                    )
                        ), $this);
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{tpl_mail}}';
    }

    public function rules() {
        return array(
            array('subject, text, formkey', 'required'),
        );
    }

    public function attributeLabels() {
        return array(
            'subject' => 'Тема письма',
            'text' => 'Письмо',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('formkey', $this->formkey, true);
        $criteria->compare('subject', $this->subject, true);
        $criteria->compare('text', $this->text, true);

        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function getBody() {
        $defaultParams = array(
            '%CURRENT_DATE%' => date('Y-m-d'),
            '%CURRENT_TIME%' => date('H:i:s'),
        );
        $results = CMap::mergeArray($defaultParams, $this->setOptions);
        return CMS::textReplace($this->text, $results);
    }

    public function getModelCriteria(CDbCriteria $criteria, $model = false) {
        if ($model) {
            $r = $model::model()->find($criteria);
            foreach ($r->getAttributes() as $attrname => $attrvalue) {
                $result['%' . strtoupper($attrname) . '%'] = $attrvalue;
            }
            return $result;
        }
    }

    public function getModelByPk($pk, $model = false) {
        $result = array();
        if ($model) {
            $r = $model::model()->findByPk($pk);
            $attributes = $r->getAttributes();
            unset($attributes['password']);
            foreach ($attributes as $attrname => $attrvalue) {
                $result['%' . strtoupper($attrname) . '%'] = $attrvalue;
            }
            return $result;
        }
    }

    /**
     * Отправка письма.
     * 
     * @param array|string $subject_array
     * @param array|string $body
     */
    public function sendEmail($subject, $body) {
        $request = Yii::app()->request;
        $mailer = Yii::app()->mail;
        $mailer->From = 'noreply@' . $request->serverName;
        $mailer->FromName = ($this->fromName) ? $this->fromName : Yii::app()->settings->get('core', 'site_name');
        $mailer->Subject = (is_array($subject)) ? $this->replace_subject($subject) : $subject;
        $mailer->Body = (is_array($body)) ? $this->replace_text($body) : $body;
        if (is_array($this->emails)) {
            foreach ($this->emails as $email) {
                $mailer->AddAddress($email);
            }
        } else {
            $mailer->AddAddress($this->emails);
        }

        $mailer->AddReplyTo('noreply@' . $request->serverName);
        $mailer->isHtml(true);
        $mailer->Send();
        $mailer->ClearAddresses();
    }

    public function replace_text($array) {
        return CMS::textReplace($this->text, CMap::mergeArray($this->replace_default(), $array));
    }

    public function replace_subject($array) {
        return CMS::textReplace($this->subject, CMap::mergeArray($this->replace_default(), $array));
    }

    private function replace_default() {
        return array(
            '%CURRENT_DATE%' => date('Y-m-d'),
            '%CURRENT_TIME%' => date('H:i:s'),
        );
    }

    public function setEmails($mails) {
        if (is_array($mails)) {
            foreach ($mails as $email)
                $this->emails[] = $email;
        } else {
            $this->emails = $mails;
        }
    }

    public function getEmails() {
        return $this->emails;
    }

    public function setFromName($value) {
        $this->fromName = $value;
    }

    public function getFromName() {
        return $this->fromName;
    }

}