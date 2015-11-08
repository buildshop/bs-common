<?php

class Delivery extends ActiveRecord {

    const MODULE_ID = 'delivery';

    public function getForm() {
        return new CMSForm(array(
                    'id' => __CLASS__,
                    'elements' => array(
                        'email' => array(
                            'type' => 'text',
                        ),
                    ),
                    'buttons' => array(
                        'submit' => array(
                            'type' => 'submit',
                            'class' => 'buttonS bGreen',
                            'label' => ($this->isNewRecord) ? Yii::t('core', 'CREATE', 0) : Yii::t('core', 'SAVE')
                        )
                    ),
                        ), $this);
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{delivery}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('email', 'required'),
            array('email', 'validateUserEmail'),
            array('email', 'validateEmail'),
            array('email', 'email'),
            array('email', 'match', 'pattern' => '/^[\da-z][-_\d\.a-z]*@(?:[\da-z][-_\da-z]*\.)+[a-z]{2,5}$/iu'),
            array('id, email', 'safe', 'on' => 'search'),
        );
    }
    /**
     * Проверяем наличие пользователя с такой же почтой
     * @param type $attr
     */
    public function validateUserEmail($attr) {
        $check = User::model()->countByAttributes(array(
            'login' => $this->$attr,
                ), 't.id != :id AND subscribe=:subscribe', array(':id' => (int) $this->id, ':subscribe' => 1));
        if ($check > 0)
            $this->addError($attr, Yii::t('DeliveryModule.default', 'SUBSCRIBE_USER_ERROR', array('{attr}' => $this->$attr)));
    }
    /**
     * Проверяем наличие почты статичной базы рассылки.
     * @param type $attr
     */
    public function validateEmail($attr) {
        $check = self::model()->countByAttributes(array('email' => $this->$attr));
        if ($check > 0)
            $this->addError($attr, Yii::t('DeliveryModule.default', 'SUBSCRIBE_ERROR', array('{attr}' => $this->$attr)));
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('date_create', $this->date_create, true);
        $criteria->compare('switch', $this->switch);

        return new ActiveDataProvider($this, array('criteria' => $criteria));
    }

}
