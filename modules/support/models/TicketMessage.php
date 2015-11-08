<?php

class TicketMessage extends BSAbstractDb {

    const MODULE_ID = 'support';
    public function getForm() {

        return new CMSForm(array(
                    'showErrorSummary' => false,
                    'attributes' => array(
                        'class' => 'form-horizontal',
                        'enctype' => 'multipart/form-data',
                        'id' => __CLASS__,
                    ),
                    'elements' => array(

                        'text' => array(
                            'type' => 'textarea',
                        ),
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

    public function attributeLabels() {
        return array(
            'text'=>Yii::t('SupportModule.default','TEXT')
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
        return '{{ticket_message}}';
    }
    public function relations() {
        return array(
            'user' => array(self::BELONGS_TO, 'BSUser', 'user_id'),

        );
    }
    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {


        return array(
            array('text', 'required'),
            /*  array('banned, message', 'boolean'),
              array('email', 'email'),
              array('date_registration', 'required', 'on' => 'update'),
              array('date_registration, last_login', 'date', 'format' => array('yyyy-M-d H:m:s', '0000-00-00 00:00:00')),
              array('date_birthday', 'date', 'format' => array('yyyy-M-d', '0000-00-00')),
              array('username, password, email, theme, avatar, login_ip, service, phone, address, timezone', 'length', 'max' => 255),
              array('new_password', 'length', 'min' => $config['min_password']),
              array('password', 'length', 'min' => $config['min_password']),
              array('gender, language, subscribe', 'numerical', 'integerOnly' => true), */
            array('id, text', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('text', $this->text, true);


        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}
