<?php

class PaymentController extends AdminController {
    /* Settings Privat24 */

    const PRIVAT24_MERCHANT = 110541;
    const PRIVAT24_PASS = 'H4okGc9PeM1T30TEMQWl88pfqqfFz0X4';

    public function actionGetPayment() {
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['system'])) {
                $system = $_POST['system'];
                if ($system == 'privat24') {
                    $this->privat24();
                }
            }
        }
    }

    private function privat24() {
        $this->render('privat24', array(
            'price' => $_POST['price']
        ));
    }

}