<?php

class Package extends CComponent {

    const CACHE_ID = 'client';

    public $value;

    public function init() {
        $result = array();
        $this->value = Yii::app()->cache->get(self::CACHE_ID);
        if ($this->value === false) {

            $user = BSUser::model()->findByPk(Yii::app()->params['client_id']);
            $result['user_active'] = $user->active;
            if (isset($user->shop)) {
                foreach ($user->shop as $shop) {
                    $result['shop'][] = array(
                        'expired' => $shop->expired,
                        'plan' => $shop->plan,
                        'isdemo' => $shop->getIsDemo(),
                        'id' => $shop->id
                    );
                }
            }
            Yii::app()->cache->set(self::CACHE_ID, (object) $result);
        }

        //  $this->access();
        $this->blocked();
    }

    private function blocked() {
        if ((strtotime($this->value->shop[0]['expired']) < time()) || !$this->value->user_active) { // || $this->result['isdemo']
            Yii::app()->controllerMap['blocked'] = 'app.MaintenanceMode.BlockedController';
            $capUrl = ($this->value->shop[0]['isdmo']) ? 'blocked/index' : 'blocked/expired';
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
