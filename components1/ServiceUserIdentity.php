<?php

class ServiceUserIdentity extends EngineUserIdentity {

    const ERROR_NOT_AUTHENTICATED = 3;

    /**
     * @var EAuthServiceBase the authorization service instance.
     */
    protected $service;

    /**
     * Constructor.
     * @param EAuthServiceBase $service the authorization service instance.
     */
    public function __construct($service) {
        $this->service = $service;
    }

    /**
     * Authenticates a user based on {@link username}.
     * This method is required by {@link IUserIdentity}.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        $login = $this->service->serviceName . $this->service->getAttribute('id');

        if ($this->service->isAuthenticated) {
            $app_user = User::model()->findByAttributes(array('login' => $login));
            //если пользователя ещё нет - создаём
            if (!$app_user) {
                $app_user = $this->createUser($login);
            } else { //Обновляем информацию о пользователе
                $app_user = $this->refreshUser($login);
            }
//die(print_r($app_user));
            //die('auth: ' . print_r($this->service->getAttributes()));
            $this->applyUser($app_user);

            $this->errorCode = self::ERROR_NONE;
        } else {
            $this->errorCode = self::ERROR_NOT_AUTHENTICATED;
        }
        return !$this->errorCode;
    }

    private function applyUser($user) {

        $this->_id = $user->id;
        $this->setState('id', $user->id);
        $this->setState('username', $this->service->getAttribute('name'));
        $this->setState('service', $user->service);
    }

    private function refreshUser($login) {
        $model = User::model()->findByAttributes(array('login' => $login));
        $model->username = $this->service->getAttribute('username');
        $model->service = $this->service->serviceName;
        $model->avatar = $this->service->getAttribute('photo_small');
        $model->timezone = $this->service->getAttribute('timezone');
        $model->gender = $this->service->getAttribute('gender');
        $model->last_login = date('Y-m-d H:i:s');
        $model->save(false,false,false);
        return $model;
    }

    private function createUser($login) {

        $model = new User;
        $tmpname = array();
        preg_match('/^([^\s]+)\s*(.*)?$/', $this->service->getAttribute('name'), $tmpname); //разделение имени по запчастям
        //$newUser->firstname = $tmpname[1];
        //$newUser->lastname = $tmpname[2];
        $model->login = $login;
        $model->username = $this->service->getAttribute('username');
        $model->avatar = $this->service->getAttribute('photo_small');
        $model->timezone = $this->service->getAttribute('timezone');
        $model->gender = $this->service->getAttribute('gender');
        $model->service = $this->service->serviceName;
        $model->subscribe = 0;
        $model->active = true;
        $model->last_login = date('Y-m-d H:i:s');
        $model->date_registration = date('Y-m-d H:i:s');
        if($model->validate()){
             $model->save(false,false);
        }else{
            print_r($model->getErrors());
                    die;
        }
       

        return $model;
    }

}