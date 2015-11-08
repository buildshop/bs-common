<?php

class GridColumns extends ActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{grid_columns}}';
    }

    public function rules() {
        return array(
            array('grid_id', 'safe', 'on' => 'search'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_id' => 'user Name',
            'ip_address' => 'ip_address',
            'user_agent' => 'user_agent',
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
