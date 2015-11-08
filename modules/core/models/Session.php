<?php

class Session extends ActiveRecord {

    public static function online() {
        $session = Session::model()->cache(0)->findAll();
        $result = array();
        if (isset($session)) {
            $t = 0;
            $g = 0;
            $b = 0;
            $u = 0;
            $a = 0;
            foreach ($session as $val) {
                $result['users'][] = array(
                    'id' => $val->user_id,
                    'ip' => $val->ip_address,
                    'user_agent' => $val->user_agent,
                    'avatar' => $val->user_avatar,
                    'type' => $val->user_type
                );
                if ($val->user_type == 1) {
                    $u++;
                } elseif ($val->user_type == 2) {
                    $a++;
                } elseif ($val->user_type == 3) {
                    $b++;
                } else {
                    $g++;
                }
                $t++;
            }
            $result['totals'] = array(
                'all' => $t,
                'guest' => $g,
                'admin' => $a,
                'users' => $u,
                'bot' => $b
            );
        }
        return $result;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{session}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, ip_address, user_agent, user_type, start_expire', 'length', 'max' => 255),
            array('user_id, ip_address, user_agent, user_type, start_expire', 'safe'),
            array('user_id, ip_address, user_agent, user_type, start_expire', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_id' => 'user Name',
            'ip_address' => 'ip_address',
            'user_agent' => 'user_agent',
        );
    }

    public function relations() {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get ActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return ActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('user_type', $this->user_type);
        $criteria->compare('start_expire', $this->start_expire);
        $criteria->compare('user_agent', $this->user_agent,true);
        $criteria->compare('ip_address', $this->ip_address,true);
        $criteria->compare('current_url', $this->current_url,true);

        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Blocks the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
