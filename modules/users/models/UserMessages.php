<?php

/**
 * Модель сообщений пользователя
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.users.models
 * @uses ActiveRecord
 */
class UserMessages extends ActiveRecord {

    public $new_password;
    public $verifyCode;
    public $_config;
    protected $_avatarPath;

    public function getConfig() {
        Yii::import('zii.widgets.jui.CJuiDatePicker');
        return array('id' => 'userUpdateForm',
            'enctype' => 'multipart/form-data',
            'showErrorSummary' => true,
            'elements' => array(
                'to_user' => array('type' => 'hidden', 'value' => 2),
                'email' => array('type' => 'text'),
                'date_registration' => array(
                    'type' => 'CJuiDatePicker',
                    'options' => array(
                        'dateFormat' => 'yy-mm-dd ' . date('H:i:s'),
                    ),
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'label' => ($this->isNewRecord) ? Yii::t('core', 'CREATE') : Yii::t('core', 'SAVE')
                )
            )
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user_messages}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('text', 'required'),
            array('date_create', 'date', 'format' => array('yyyy-M-d H:m:s', '0000-00-00 00:00:00')),
            //array('username, password, email, discount, avatar, gender', 'length', 'max' => 255),
            // Search
            array('id, to_user, from_user, text, date_create', 'safe', 'on' => 'search'),
        );
    }

    public function beforeSave() {
        if (parent::beforeSave()) {
            // echo CMS::gen(40);
///die();
            return true;
        } else {
            return false;
        }
    }

    public function beforevalidate() {
        if (parent::beforevalidate()) {
            $this->from_user = Yii::app()->user->id;
            return true;
        } else {
            return false;
        }
    }

    public function relations() {
        return array(
            //Получатель
            'receiver' => array(self::BELONGS_TO, 'User', 'to_user'),
            //Отправитель
            'sender' => array(self::BELONGS_TO, 'User', 'from_user'),
        );
    }

    public function attributeLabels() {
        return array(
            'to_user' => 'кому',
            'from_user' => 'От',
            'text' => 'text',
        );
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('to_user', $this->to_user);
        $criteria->compare('from_user', $this->from_user);
        $criteria->compare('text', $this->text, true);
        $criteria->compare('date_create', $this->date_create, true);

        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}
