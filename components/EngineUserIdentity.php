<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class EngineUserIdentity extends CUserIdentity {

    protected $_id;
    protected $_login;


    public function authenticate() {

        $record = User::model()->with('group')->findByAttributes(array('login' => $this->username));
        if ($record === null)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else if ($record->banned === '1')
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else if ($record->password !== User::encodePassword($this->password))
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else {
            $this->_id = $record->id;
            $this->_login = $record->login;
            $record->last_login = date('Y-m-d H:i:s');
            $record->login_ip = CMS::getip();
            $record->save(false,false,false);
            $this->setState('id', $record->id);
            $this->setState('username', $record->login);
            $this->setState('roles', $record->group->alias);

            $this->errorCode = self::ERROR_NONE;
        }
        return !$this->errorCode;
    }

    public function getId() {
        return $this->_id;
    }

    public function getLogin() {
        return $this->_login;
    }

}