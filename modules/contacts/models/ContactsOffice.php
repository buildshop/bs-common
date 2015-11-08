<?php

/**
 * @package modules.contacts.models
 */
class ContactsOffice extends ActiveRecord {

    const MODULE_ID = 'contacts';

    public function getForm() {
        return new CMSForm(array(
                    'attributes' => array(
                        'class' => 'form-horizontal',
                        'id' => __CLASS__,
                    ),
                    'showErrorSummary' => true,
                    'elements' => array(
                        'coordx' => array('type' => 'text'),
                        'coordy' => array('type' => 'text'),
                        'phones' => array('type' => 'text'),
                        'address' => array('type' => 'textarea'),
                        'showInMap' => array('type' => 'checkbox')
                    ),
                    'buttons' => array(
                        'submit' => array(
                            'type' => 'submit',
                            'class' => 'btn btn-success',
                            'label' => $this->isNewRecord ? Yii::t('app', 'CREATE', 0) : Yii::t('app', 'SAVE')
                        )
                    )
                        ), $this);
    }

    public function relations() {
        return array(
            //'manager' => array(self::HAS_MANY, 'ContactsManagers', 'office_id'),
        );
    }

    public function scopes() {
        $alias = $this->getTableAlias(true);
        return array(
            'active' => array(
                'condition' => $alias . '.switch=1',
            ),
            'inMap' => array(
                'condition' => $alias . '.showInMap=1',
            )
        );
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{contacts_office}}';
    }

    public function rules() {
        return array(
            array('address', 'required'),
            array('showInMap, switch', 'numerical', 'integerOnly' => true),
            array('coordx, coordy, phones, address', 'type', 'type' => 'string'),
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('coordy', $this->coordy, true);
        $criteria->compare('coordy', $this->coordy, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('phones', $this->phones, true);

        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}
