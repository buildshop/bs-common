<?php

class Ticket extends BSAbstractDb {

    const MODULE_ID = 'support';

    //public $text;

    public function getForm() {

        return new CMSForm(array(
                    'showErrorSummary' => false,
                    'attributes' => array(
                        'class' => 'form-horizontal',
                        'enctype' => 'multipart/form-data',
                        'id' => __CLASS__,
                    ),
                    'elements' => array(
                        'name' => array(
                            'type' => 'text',
                        ),
                        'text' => array(
                            'type' => 'textarea',
                        ),
                    ),
                    'buttons' => array(
                        'submit' => array(
                            'type' => 'submit',
                            'class' => 'btn btn-success',
                            'label' => Yii::t('app', 'SEND')
                        ),
                    )
                        ), $this);
    }

    public function relations() {
        return array(
            'messages' => array(self::HAS_MANY, 'TicketMessage', 'ticket_id'),
            'countMessages' => array(self::STAT, 'TicketMessage', 'ticket_id'),
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
        return '{{ticket}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {


        return array(
            array('name, text', 'required'),
            /*  array('banned, message', 'boolean'),
              array('email', 'email'),
              array('date_registration', 'required', 'on' => 'update'),
              array('date_registration, last_login', 'date', 'format' => array('yyyy-M-d H:m:s', '0000-00-00 00:00:00')),
              array('date_birthday', 'date', 'format' => array('yyyy-M-d', '0000-00-00')),
              array('username, password, email, theme, avatar, login_ip, service, phone, address, timezone', 'length', 'max' => 255),
              array('new_password', 'length', 'min' => $config['min_password']),
              array('password', 'length', 'min' => $config['min_password']),
              array('gender, language, subscribe', 'numerical', 'integerOnly' => true), */
            array('id, name, text', 'safe', 'on' => 'search'),
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
        $criteria->compare('text', $this->text, true);


        return new CActiveDataProvider('Ticket', array(
                    'criteria' => $criteria,
                ));
    }

    public function getStatusByName() {
        if ($this->status == 1) {
            return Yii::t('SupportModule.default', 'STATUS_LABEL', 1);
        } elseif ($this->status == 2) {
            return Yii::t('SupportModule.default', 'STATUS_LABEL', 2);
        } else {
            return Yii::t('SupportModule.default', 'STATUS_LABEL', 0);
        }
    }

    public function getStatusByCssClass() {
        if ($this->status == 1) {
            return 'default';
        } elseif ($this->status == 2) {
            return 'success';
        } else {
            return 'danger';
        }
    }

    public function getStatusByHtml() {
        return Html::tag('span', array('class' => 'label label-lg2 label-' . $this->getStatusByCssClass()), $this->getStatusByName(), true);
    }

    public function attributeLabels() {
        return array(
            'status' => Yii::t('SupportModule.default', 'STATUS'),
            'text' => Yii::t('SupportModule.default', 'TEXT'),
            'name' => Yii::t('SupportModule.default', 'NAME'),
        );
    }

}
