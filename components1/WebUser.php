<?php

// this file must be stored in:
// protected/components/WebUser.php

class WebUser extends CWebUser {


    public function checkAccess($operation, $params = array()) {
        if (empty($this->id)) {
            // Not identified => no rights
            return false;
        }

        $role = $this->getState("roles");
        if ($role === 'admin' && $operation != 'banned') {
            return true; // admin role has access to everything
        }
        if ($role === 'moderator' && ($operation != 'banned' || $operation != 'admin')) {
            return true; // admin role has access to everything
        }
        // allow access if the operation request is the current user's role
        return ($operation === $role);
    }

    /*public function renewAuthTimeout($timeOut) {
        $this->authTimeout = $timeOut;
        $this->updateAuthStatus();
    }

    public function init() {
        $this->authTimeout = $this->getState(CWebUser::AUTH_TIMEOUT_VAR);
        parent::init();
    }*/

}

?>