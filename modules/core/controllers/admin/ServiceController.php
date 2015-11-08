<?php

class ServiceController extends AdminController {

    public $topButtons = false;

    public function actionSuccess() {
        $request = Yii::app()->request;
        $pass = 'H4okGc9PeM1T30TEMQWl88pfqqfFz0X4';
        if (isset($_POST['payment'])) {
            parse_str($request->getPost('payment'), $payments);
        }

        $signature = sha1(md5($request->getParam('payment') . $pass));

        if ($signature != $request->getParam('signature')) {
            die('signature error');
            return false;
        }
        echo date('Y-m-d H:i:s', $payments['date']);
        print_r($payments);
        die('ok');
    }

    public function actionIndex() {
        $data = LicenseCMS::run()->readData();

        $supportForm = new SupportForm;
        $this->breadcrumbs = array(Yii::t('app', 'SYSTEM') => array('admin/index'), Yii::t('app', 'SVC'));
        if (isset($_POST['SupportForm'])) {
            $supportForm->attributes = $_POST['SupportForm'];
            if ($supportForm->validate()) {
                $supportForm->sendMail();
                $this->setFlashMessage(Yii::t('app', 'SUCCESS_MSG_SAND'));
                $this->redirect(array('index'));
            }
        }





        if (!isset($data['proffers'])) {
            $data['proffers'] = array();
        }
        $providerProffers = new CArrayDataProvider($data['proffers'], array(
                    'sort' => array(
                        'attributes' => array('title'),
                        'defaultOrder' => array('title' => false),
                    ),
                        )
        );


        $this->render('index', array(
            'supportForm' => $supportForm,
            'providerProffers' => $providerProffers
        ));
    }

}
