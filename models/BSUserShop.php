<?php

/**
 * This is the model class for table "user".
 * The followings are the available columns in table 'user':
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @uses BSAbstractDb
 * @package modules.users.models
 * @property integer $id
 * @property string $username Имя пользовотеля
 * @property string $login Логин
 * @property string $password sha1(Пароль)
 * @property string $email Почта
 * @property integer $last_login
 * @property string $login_ip IP-адрес входа пользователя
 * @property string $recovery_key Password recovery key
 * @property string $recovery_password
 * @property boolean $banned
 */
class BSUserShop extends BSAbstractDb {

    public function expired($days = 1) {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'DATE(expired) = CURDATE() + INTERVAL :day DAY',
            'params' => array(':day' => $days)
        ));
        return $this;
    }

    public function getIsDemo() {
        $twoWeek = date('Y-m-d H:i:s', strtotime('+2 week', strtotime($this->date_create)));
        if (strtotime($this->expired) > strtotime($twoWeek)) {
            return false;
        } else {
            return true;
        }
    }

    protected $_MODULENAME = 'users';

    public function getSubdomainUrl() {
        return 'http://' . $this->subdomain . '.' . Yii::app()->request->serverName;
    }

    public function getSubdomainFull() {
        return $this->subdomain . '.' . Yii::app()->request->serverName;
    }

    public function getDomainUrl() {
        return 'http://' . $this->domain;
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
        return '{{user_shop}}';
    }

    public static function getPlansList() {
        return array(
            'lite' => 'Lite',
            'basic' => 'Basic',
            'standart' => 'Standart',
            'pro' => 'Proffesion',
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {

        return array(
            array('uid', 'required'),
            array('subdomain', 'match', 'pattern' => '/^[\w\s,]+$/', 'message' => 'Домен a-Z'),
            array('expired', 'date', 'format' => array('yyyy-M-d H:m:s', '0000-00-00 00:00:00')),
            array('domain', 'checkDomainAliasExist', 'on' => 'update'),
            array('domain, subdomain', 'unique'),
            array('uid', 'numerical', 'integerOnly' => true),
            array('domain, subdomain', 'length', 'min' => 4, 'max' => 255),
            array('id, subdomain', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Запись псевдонимов доменна.
     * @param string $attr
     * TODO: тут наверное нужно будет сделать проверку на NS сервера (еще уточняется.)
     */
    public function checkDomainAliasExist($attr) {

        $aliases = (!empty($this->$attr)) ? array($this->$attr) : null;

        Yii::import('app.hosting_api.*');
        $api = new APIHosting('hosting_site_config_ws', 'edit', array(
                    'host' => CMS::domain($this->getSubdomainUrl()),
                    'aliases' => $aliases
                ));
        $result = $api->callback(false);
        $data = (array) $result->response;

        if (isset($data['status'])) {
            if ($data['status'] == 'error')
                $this->addError($attr, $data['message']); //$data->notes[0]
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);

        $criteria->compare('avatar', $this->avatar, true);
        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}
