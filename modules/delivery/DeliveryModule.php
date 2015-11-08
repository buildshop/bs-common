<?php

class DeliveryModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*',
        ));
    }

    public function afterInstall() {
        Yii::app()->database->import($this->id);
        Yii::app()->widgets->set('module', array(
            'alias_wgt' => 'mod.delivery.widgets.subscribe.SubscribeWidget',
            'name' => 'Подписатся на рассылку'
        ));
        return parent::afterInstall();
    }

    public function afterUninstall() {
        Yii::app()->db->createCommand()->dropTable('delivery');
        //Yii::app()->unsetWidget('mod.delivery.widgets.delivery.DeliveryWidget');
        return parent::afterUninstall();
    }

    public static function getAdminMenu() {
        return array(
            'modules' => array(
                'items' => array(
                    array(
                        'label' => Yii::t('DeliveryModule.default', 'MODULE_NAME'),
                        'url' => Yii::app()->createUrl('/admin/delivery/default/index'),
                        'icon' => 'flaticon-maillatters',
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                ),
            ),
        );
    }

    public static function getAllDelivery() {
        $delivery = Delivery::model()->findAll();
        $mails = array();
        $users = User::model()->subscribe()->findAll();
        if (count($users)) {
            foreach ($users as $user) {
                $mails[] = $user->email;
            }
        }
        if (count($delivery)) {
            foreach ($delivery as $subscriber) {
                $mails[] = $subscriber->email;
            }
        }
        return $mails;
    }

    public static function getInfo() {
        return array(
            'name' => Yii::t('DeliveryModule.default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '0.1',
            'icon' => 'flaticon-maillatters',
            'description' => Yii::t('DeliveryModule.default', 'MODULE_DESC'),
        );
    }

    public function rules() {
        return array(
            'delivery/' => 'delivery/default/index',
            'delivery/subscribe' => 'delivery/default/subscribe',
        );
    }

}
