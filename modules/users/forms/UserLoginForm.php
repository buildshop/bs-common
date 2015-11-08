<?php

/**
 * User login form
 */
class UserLoginForm extends FormModel {

    public $login;
    public $password;
    public $rememberMe = false;
    private $_identity;
    protected $_mid = 'users';

    /**
     * @return array
     */
    public function rules() {
        return array(
            array('login, password', 'required'),
            array('login', 'email', 'on' => 'adminAuth'),
            array('password', 'authenticate'),
            array('rememberMe', 'boolean'),
        );
    }

    /**
     * Try to authenticate user
     */
    public function authenticate() {
        if (!$this->hasErrors()) {
            $this->_identity = new EngineUserIdentity($this->login, $this->password);
            if (!$this->_identity->authenticate()) {
                if ($this->_identity->errorCode === EngineUserIdentity::ERROR_PASSWORD_INVALID) {
                    $this->addError('password', Yii::t('app', 'INCORRECT_LOGIN_OR_PASS'));
                }
            }

            if ($this->scenario == 'adminAuth') {
                $model = User::model()->findByAttributes(array(
                    'login' => $this->login,
                    'password' => User::encodePassword($this->password)
                        ));
                if (!$model)
                    $this->addError('password', Yii::t('app', 'INCORRECT_LOGIN_OR_PASS'));
            }
        }
    }

    /**
     * @return mixed
     */
    public function getIdentity() {
        return $this->_identity;
    }

    public function login() {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->login, $this->password);
            $this->_identity->authenticate();
        }
        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            $duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
            Yii::app()->user->login($this->_identity, $duration);
            return true;
        }
        else
            return false;
    }

}
