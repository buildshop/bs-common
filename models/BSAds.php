<?php

class BSAds extends BSAbstractDb {

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
        return '{{ads}}';
    }

    public function scopes() {
        return array(
            'active' => array(
                'condition' => '`t`.`switch`=:a',
                'params' => array(':a' => 1)
            )
        );
    }

    public function getMessage() {
        $params=array();
        $params['%USER_LOGIN%'] = Yii::app()->user->login;
        return CMS::textReplace($this->message, $params);
    }

    public function afterFind() {
        parent::afterFind();
        if (CMS::time() >= strtotime($this->date_end)) {
            $this->switch = 0;
            $this->save(false);
        }
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('date_registration', 'required', 'on' => 'update'),
            array('date_registration, last_login', 'date', 'format' => array('yyyy-M-d H:m:s', '0000-00-00 00:00:00')),
            array('date_birthday', 'date', 'format' => array('yyyy-M-d', '0000-00-00')),
            array('username, password, email, theme, avatar, login_ip, service, phone, address, timezone', 'length', 'max' => 255),
            array('gender, language, subscribe', 'numerical', 'integerOnly' => true),
            array('id, username, email, date_registration, last_login, banned, avatar, language, address, phone', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('login', $this->login, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('date_registration', $this->date_registration, true);
        $criteria->compare('avatar', $this->avatar, true);
        $criteria->compare('last_login', $this->last_login);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('banned', $this->banned);

        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}
