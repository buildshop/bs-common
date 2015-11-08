<?php

class BSUser extends BSAbstractDb {


    public function relations() {
        return array(
            'plan' => array(self::BELONGS_TO, 'UserGroup', 'plan'),
            'shop' => array(self::HAS_MANY, 'BSUserShop', 'uid'),
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
        return '{{user}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        $config = Yii::app()->settings->get('users');

        return array(
            //Регистрация
            array('username', 'checkBadName', 'on' => 'register'),
            array('domain', 'checkDomainExist', 'on' => 'register'),
            array('login', 'on' => 'register'),
            array('login, password, confirm_password, domain', 'required', 'on' => 'register'),
            array('password, confirm_password', 'checkPasswords', 'on' => 'register'),
            //array('login', 'email', 'on' => 'register'),
            //array('verifyCode', 'captcha', 'on' => 'register', 'allowEmpty' => YII_DEBUG),
            array('login', 'required'),
            array('login', 'checkIfAvailable'),
            array('banned, message', 'boolean'),
            array('avatar', 'file',
                'types' => $config['users'],
                'allowEmpty' => true,
                'maxSize' => $config['upload_size'],
                'wrongType' => Yii::t('core', 'WRONG_TYPES', array('{TYPES}' => $config['upload_types']))
            ),
            array('email', 'email'),
            array('date_registration', 'required', 'on' => 'update'),
            array('date_registration, last_login', 'date', 'format' => array('yyyy-M-d H:m:s', '0000-00-00 00:00:00')),
            array('date_birthday', 'date', 'format' => array('yyyy-M-d', '0000-00-00')),
            array('username, password, email, theme, avatar, login_ip, service, phone, address, timezone', 'length', 'max' => 255),
            array('new_password', 'length', 'min' => $config['min_password']),
            array('password', 'length', 'min' => $config['min_password']),
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

    public function avatarUrl($size = false) {
        if ($size === false) {
            $size = Yii::app()->settings->get('users', 'avatar_size');
        }
        $ava = $this->avatar;
        if (!preg_match('/(http|https):\/\/(.*?)$/i', $ava)) {
            $r = true;
        } else {
            $r = false;
        }
        // if (!is_null($this->service)) {
        //     return $this->avatar;
        // }
        if ($size !== false && $r !== false) {
            $thumbPath = Yii::getPathOfAlias('webroot.assets.user_avatar') . DS . $size;
            if (!file_exists($thumbPath)) {
                mkdir($thumbPath, 0777, true);
            }
            // Path to source image
            $fullPath = Yii::getPathOfAlias('webroot.uploads.users.avatar') . DS . $ava;

            // Path to thumb
            $thumbPath = $thumbPath . DS . $ava;
            if (!file_exists($thumbPath)) {
                // Resize if needed
                Yii::import('ext.phpthumb.PhpThumbFactory');
                $sizes = explode('x', $size);
                $thumb = PhpThumbFactory::create($fullPath);
                $thumb->resize($sizes[0], $sizes[1])->save($thumbPath);
            }
            return (empty($ava)) ? '/uploads/users/avatars/user.png' : '/assets/user_avatar/' . $size . '/' . $ava;
        } else {
            return $ava;
        }
    }

}
