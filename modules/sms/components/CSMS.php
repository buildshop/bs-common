<?php

class CSMS extends CWidget {

    public $alias;

    const DEBUG = false;

    public static function log($message) {
        Yii::log(Yii::app()->settings->get('sms', 'service') . ': ' . $message, 'sms', 'application');
    }

    public function getTplByKey($key) {
        $tpl = TplSMS::model()->findByAttributes(array('alias_key' => $key));
        if (!$tpl)
            self::log('Unknown tpl sms');
        return $tpl;
    }

    public function getUsersByAdmin() {
        $users = User::model()->groupBy(1)->findAll();
        if (!$users)
            self::log('Unknown users admin sms');
        return $users;
    }

    protected function replace($model, $content) {
        if ($model instanceof Order) {
            $array['%ORDER_ID%'] = $model->id;
            $array['%ORDER_KEY%'] = $model->secret_key;
            $array['%TOTAL_PRICE%'] = ShopProduct::formatPrice(Yii::app()->currency->convert($model->full_price));
            $array['%USER_NAME%'] = $model->user_name;
            $array['%USER_PHONE%'] = $model->user_phone;
            $array['%USER_EMAIL%'] = $model->user_email;
            $array['%USER_ADDRESS%'] = $model->user_address;
            $array['%ORDER_URL%'] = $model->getAbsoluteUrl();
        }
        $array['%CURRENT_CURRENCY%'] = Yii::app()->currency->active->symbol;
        return CMS::textReplace($content, $array);
    }

    /**
     * Оправляет всем Администратором с указаным телефоном, уведомление о новом заказе.
     * @param Order $model
     */
    public function ADMIN_ORDER_BEFORE(Order $model) {
        $tpl = $this->getTplByKey(__FUNCTION__);
        if ($tpl->switch) {
            $phones = array();
            foreach ($this->getUsersByAdmin() as $user) {
                if (isset($user->phone)) {
                    $phones[] = $user->phone;
                }
            }
            if (!self::DEBUG) {
                $this->send($this->replace($model, $tpl->text), $phones);
            } else {
                self::log($this->replace($model, $tpl->text));
            }
        }
    }

    /**
     * Оправляет клиенту на указанный при оформелние заказа телефон, уведомление.
     * @param Order $order
     */
    public function CLIENT_ORDER_BEFORE(Order $model) {
        $tpl = $this->getTplByKey(__FUNCTION__);
        if ($tpl->switch) {
            $phones = array();
            $phones[] = $model->user_phone;
            if (!self::DEBUG) {
                $this->send($this->replace($model, $tpl->text), $phones);
            } else {
                self::log($this->replace($model, $tpl->text));
            }
        }
    }

}

?>
