<?php

class ComponentsModel extends ActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{components}}';
    }

    public function rules() {
        return array(
            array('grid_id', 'safe', 'on' => 'search'),
        );
    }

    public function defaultScope() {

        return array(
            'condition' => '`t`.`switch`=1',
        );
    }

    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}
