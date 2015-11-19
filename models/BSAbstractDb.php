<?php

abstract class BSAbstractDb extends CActiveRecord {

    public static $server_id = 'User';
    public static $master_db;

    public function getDbConnection() {
        self::$master_db = Yii::app()->dbUser;
        if (self::$master_db instanceof DbConnection) {
            self::$master_db->setActive(true);
            return self::$master_db;
        }
        else
            throw new CDbException(Yii::t('yii', 'Active Record requires a "db" DbConnection application component.'));
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            //create
            if ($this->isNewRecord) {
                if (isset($this->tableSchema->columns['ip_create'])) {
                    //Текущий IP адресс, автора добавление
                    $this->ip_create = Yii::app()->request->userHostAddress;
                }
                if (isset($this->tableSchema->columns['user_id'])) {
                    $this->user_id = (Yii::app()->user->isGuest) ? 0 : Yii::app()->user->id;
                }
                if (isset($this->tableSchema->columns['user_agent'])) {
                    $this->user_agent = Yii::app()->request->userAgent;
                }
                if (isset($this->tableSchema->columns['date_create'])) {
                   // $this->date_create = date('Y-m-d H:i:s');
                    $this->date_create = date('Y-m-d H:i:s',CMS::time());
                }
                if (isset($this->tableSchema->columns['ordern'])) {
                    if (!isset($this->ordern)) {
                        $row = $this->model()->find(array('select' => 'max(ordern) AS maxOrdern'));
                        $this->ordern = $row['maxOrdern'] + 1;
                    }
                }
            } else {
                //update
                if (isset($this->tableSchema->columns['date_update'])) {
                    $this->date_update = date('Y-m-d H:i:s');
                }
            }
            return true;
        } else {
            return false;
        }
    }

}