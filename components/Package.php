<?php

class Package extends CComponent {

    public $cacheID = 'client';
    public $expired;
    public $plan;
    public $service;
    public $demo = true;
    public $user_active = 0;

    // public $capUrl = 'blocked/expired';

    public function init() {
        $result = array();
        $value = Yii::app()->cache->get($this->cacheID);
        if ($value === false) {

            $user = BSUser::model()->findByPk(Yii::app()->params['client_id']);

            $result['expired'] = $user->shop[0]->expired;
            $result['plan'] = $user->shop[0]->plan;
            $result['isdemo'] = $user->shop[0]->getIsDemo();
            $result['user_active'] = $user->active;
            $this->expired = $result['expired'];
            $this->plan = $result['plan'];
            $this->demo = $result['isdemo'];
            $this->user_active = $result['user_active'];

            Yii::app()->cache->set($this->cacheID, $result);
        } else {
            $rusCache = $this->getResult();
            $this->expired = $rusCache['expired'];
            $this->demo = $rusCache['isdemo'];
            $this->plan = $rusCache['plan'];
            $this->user_active = $rusCache['user_active'];
        }

        //  $this->access();
        $this->blocked();
    }

    public function getResult() {
        return Yii::app()->cache->get($this->cacheID);
    }


    private function blocked() {

        if ((strtotime($this->expired) < time()) || !$this->result['user_active']) { // || $this->result['isdemo']
            Yii::app()->controllerMap['blocked'] = 'app.MaintenanceMode.BlockedController';
            $capUrl = ($this->result['isdemo']) ? 'blocked/index' : 'blocked/expired';
            Yii::app()->catchAllRequest = array($capUrl);
        }
    }

    public function access() {
        $modules = Yii::app()->getModules();
        unset($modules['core'], $modules['admin']);
        foreach ($modules as $mid => $module) {
            if (array_key_exists($mid, Yii::app()->user->planListPro)) {
                if (!Yii::app()->user->planPro[$mid]) {
                    Yii::app()->user->setFlash('error', Yii::t('plan', 'ERROR_ACCESS_MODULE', array(
                                '{module}' => $mid
                            )));
                    Yii::app()->controller->redirect('/admin/?d=1');
                }
            }
        }
    }

}

?>
