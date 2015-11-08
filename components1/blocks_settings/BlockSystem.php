<?php

class BlockSystem extends CComponent {

    /**
     * @return string
     */
    public function renderSubmit() {
        return '<input type="submit" class="btn btn-success" value="' . Yii::t('core', 'Оплатить') . '">';
    }

    /**
     * @param $paymentMethodId
     * @param $data
     */
    public function setSettings($paymentMethodId, $data) {
        Yii::app()->settings->set($paymentMethodId, $data);
    }

    /**
     * @param $paymentMethodId
     */
    public function getSettings($paymentMethodId) {
        return Yii::app()->settings->get($paymentMethodId);
    }

}