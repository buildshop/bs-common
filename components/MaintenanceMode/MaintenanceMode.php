<?php

class MaintenanceMode extends CComponent {

    public $enabledMode = false;
    public $capUrl = 'maintenance/index';
    //public $users = array('admin',);
    public $roles = array('admin',);
    //public $ips = array(); //allowed IP
    public $urls = array('admin/auth', 'users/login');

    public function init() {
        $config = Yii::app()->settings->get('core');
        if ($config['site_close']) {
            $users = explode(',', $config['site_close_allowed_users']);
            $ips = explode(',', $config['site_close_allowed_ip']);
            $disable = in_array(Yii::app()->user->name, $users);
            foreach ($this->roles as $role) {
                $disable = $disable || Yii::app()->user->checkAccess($role);
            }

            $disable = $disable || in_array(Yii::app()->request->getPathInfo(), $this->urls);
            $disable = $disable || in_array(CMS::getip(), $ips); //check "allowed IP"

            if (!$disable) {
                if ($this->capUrl === 'maintenance/index') {
                    Yii::app()->controllerMap['maintenance'] = 'application.components.MaintenanceMode.MaintenanceController';
                }

                Yii::app()->catchAllRequest = array($this->capUrl);
            }
        }
    }

}
