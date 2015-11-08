<?php

/**
 * This is the model class for table "user".
 * The followings are the available columns in table 'user':
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @uses ActiveRecord
 * @package modules.users.models
 * @property integer $id
 * @property string $username Имя пользовотеля
 * @property string $login Логин
 * @property string $password sha1(Пароль)
 * @property string $email Почта
 * @property integer $date_registration Дата регистрации
 * @property integer $last_login
 * @property string $login_ip IP-адрес входа пользователя
 * @property string $recovery_key Password recovery key
 * @property string $recovery_password
 * @property boolean $banned
 */
class UserGroup extends ActiveRecord {

    const MODULE_ID = 'users';

    public function getGridColumns() {
        return array(
            array(
                'name' => 'name',
                'type' => 'raw',
                'value' => '$data->name',
            ),
            array(
                'name' => 'alias',
                'type' => 'raw',
                'value' => '$data->alias',
            ),
            'DEFAULT_CONTROL' => array(
                'class' => 'ButtonColumn',
                'template' => '{update}{delete}',
                'hidden' => array(
                    'delete' => array(1, 2, 3),
                    'update' => array(1)
                )
            ),
        );
    }

    public function getForm() {
        return new CMSForm(array(
                    'attributes' => array(
                        'class' => 'form-horizontal',
                        'id' => __CLASS__,
                    ),
                    'enctype' => 'multipart/form-data',
                    'showErrorSummary' => false,
                    'elements' => array(
                        'name' => array('type' => 'text'),
                        'alias' => array('type' => 'text'),
                    ),
                    'buttons' => array(
                        'submit' => array(
                            'type' => 'submit',
                            'class' => 'btn btn-success',
                            'label' => ($this->isNewRecord) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'SAVE')
                        ),
                    )
                        ), $this);
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
        return '{{user_group}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {


        return array(
            array('name, alias', 'required', 'on' => 'register'),
            array('name, alias', 'length', 'max' => 255),
            array('access', 'type', 'type' => 'string'),
            array('id, name, alias, access_delete', 'safe', 'on' => 'search'),
        );
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('alias', $this->alias, true);

        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}
