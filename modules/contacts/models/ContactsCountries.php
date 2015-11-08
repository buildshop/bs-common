<?php

/**
 * @package modules.contacts.models
 */
class ContactsCountries extends ActiveRecord {

    const MODULE_ID = 'contacts';

    public function getForm() {
        return new CMSForm(array(
                    'attributes' => array(
                        'class' => 'form-horizontal',
                        'id' => __CLASS__,
                    ),
                    'showErrorSummary' => true,
                    'elements' => array(
                        'name' => array('type' => 'text'),
                        'country_code' => array('type' => 'text'),
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
            'cities' => array(self::HAS_MANY, 'ContactsCities', 'country_id'),
        );
    }

    public function scopes() {
        $alias = $this->getTableAlias(true);
        return array(
            'active' => array(
                'condition' => $alias . '.switch=1',
            ),
        );
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{countries}}';
    }

    public function rules() {
        return array(
            array('name, country_code', 'required'),
            array('name, country_code', 'type', 'type' => 'string'),
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('country_code', $this->country_code, true);

        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}
