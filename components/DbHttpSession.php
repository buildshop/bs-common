<?php

class DbHttpSession extends CDbHttpSession {

    public $sessionTableName = '{{session}}';




    /**
     * Creates the session DB table.
     * @param CDbConnection $db the database connection
     * @param string $tableName the name of the table to be created
     */
    public function createSessionTable($db, $tableName) {
        switch ($db->getDriverName()) {
            case 'mysql':
                $blob = 'LONGBLOB';
                break;
            case 'pgsql':
                $blob = 'BYTEA';
                break;
            case 'sqlsrv':
            case 'mssql':
            case 'dblib':
                $blob = 'VARBINARY(MAX)';
                break;
            default:
                $blob = 'BLOB';
                break;
        }
        $db->createCommand()->createTable($tableName, array(
            'id' => 'CHAR(32) PRIMARY KEY',
            'expire' => 'integer',
            'data' => $blob,
            'user_id' => 'integer',
            'start_expire' => 'integer',
            'user_agent' => 'text',
            'user_type' => 'integer',
            'user_avatar' => 'string',
            'ip_address' => 'string',
            'current_url' => 'text',
        ));
    }

    /**
     * Session write handler.
     * Do not call this method directly.
     * @param string $id session ID
     * @param string $data session data
     * @return boolean whether session write is successful
     */
    public function writeSession($id, $data) {
        $ip = Yii::app()->request->userHostAddress;
        $agent = Yii::app()->request->userAgent;
        $user = Yii::app()->getComponent('user', false);
        $userId = empty($user) ? (int) 0 : $user->getId();
        $now = time();
        $expire = $now + Yii::app()->settings->get('core', 'session_time');
        // exception must be caught in session write handler
        // http://us.php.net/manual/en/function.session-set-save-handler.php
        //if (isset($user->avatarPath)) {
        try {



            $db = $this->getDbConnection();
            if ($db->getDriverName() == 'sqlsrv' || $db->getDriverName() == 'mssql' || $db->getDriverName() == 'dblib')
                $data = new CDbExpression('CONVERT(VARBINARY(MAX), ' . $db->quoteValue($data) . ')');

            $sq = $db->createCommand()
                    ->select('id')
                    ->from($this->sessionTableName)
                    ->where('id=:id', array(
                        ':id' => $id,
                    ))
                    ->queryScalar();
          //  Yii::log($this->sessionID, 'info', 'session');
            if ($sq === false) {
                $db->createCommand()->insert($this->sessionTableName, array(
                    'id' => $id,
                    'data' => $data,
                    'expire' => $expire,
                    'user_id' => $userId,
                    'start_expire' => $now,
                    'ip_address' => $ip,
                    'user_type' => $this->checkUserType(),
                    'user_agent' => $agent,
                    'user_avatar' => $user->avatarPath,
                    'current_url' => $this->requestUrl(),
                ));
            } else {
                $db->createCommand()->update($this->sessionTableName, array(
                    'data' => $data,
                    'expire' => $expire,
                    'ip_address' => $ip,
                    'user_id' => $userId,
                    'user_agent' => $agent,
                    'user_type' => $this->checkUserType(),
                    'user_avatar' => $user->avatarPath,
                    'current_url' => $this->requestUrl(),
                        ), 'id=:id', array(':id' => $id));
            }
        } catch (Exception $e) {
            if (YII_DEBUG)
                echo $e->getMessage();
            // it is too late to log an error message here
            return false;
        }
        // }
        return true;
    }

    private function checkUserType() {
        $user = Yii::app()->getComponent('user', false);
        if ($user->isGuest) {
            return (CMS::isBot()) ? 3 : 0;
        } else {
            return ($user->isSuperuser) ? 2 : 1;
        }
    }

    private function requestUrl(){
            return htmlspecialchars(Yii::app()->request->requestUri);
    }
}
