<?php

/**
 * HttpAuthFilter performs authorization checks using http authentication
 *
 * By enabling this filter, controller actions can be limited to a couple of users.
 * It is very simple, supply a list of usernames and passwords and the controller actions 
 * will be restricted to only those. Nothing fancy, it just keeps out users.
 * 
 * To specify the authorized users specify the 'users' property of the filter
 * Example:
 * <pre>
 *
 * 	public function filters()
 * 	{
 * 		return array(
 *           array(
 * 			'HttpAuthFilter',
 *                'users'=>array('admin'=>'admin'), 
 *                'realm'=>'Admin section'
 *                  )  
 *            );
 * 	}
 * The default section for the users property is 'admin'=>'admin' change it
 *
 */
class HttpAuthFilter extends CFilter {

    /**
     * @return array list of authorized users/passwords
     */
    public $users = array('admin' => 'admin');

    /**
     * @return string authentication realm
     */
    public $realm = 'Authentication needed';

    /**
     * Performs the pre-action filtering.
     * @param CFilterChain the filter chain that the filter is on.
     * @return boolean whether the filtering process should continue and the action
     * should be executed.
     */
    protected function preFilter($filterChain) {




        if (!$this->isAuthenticated()) {

            $this->authenticate();
        }


        //header("WWW-Authenticate: Basic realm=\"" . $this->realm . "[" . $this->users[$username] . "|" . $password . "]\"");
        //throw new CHttpException(401, Yii::t('yii', 'You are not authorized to perform this action.'));
    }

    protected function authenticate() {
        header("WWW-Authenticate: Basic realm=\"" . $this->realm . "\"");
        //header('HTTP/1.0 401 Unauthorized');
        throw new CHttpException(401, Yii::t('yii', 'You are not authorized to perform this action.'));
        exit;
    }

    protected function isAuthenticated() {
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            $httpd_username = filter_var($_SERVER['PHP_AUTH_USER'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH | FILTER_FLAG_ENCODE_LOW);
            $httpd_password = filter_var($_SERVER['PHP_AUTH_PW'], FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH | FILTER_FLAG_ENCODE_LOW);
            if ($this->users[$httpd_username] === $httpd_password) {
                return true;
            } else {
                //die('FAFA');
                return false;
            }
        }
        return false;
    }

}

