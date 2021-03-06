<?php

class DefaultController extends AdminController {

    public function actions() {
        return array(
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
        );
    }

    public function actionIndex() {
        $this->pageName = Yii::t('DeliveryModule.default', 'MODULE_NAME');

        $this->topButtons = array(
            array(
                'label' => Yii::t('DeliveryModule.default', 'CREATE_DELIVERY'),
                'url' => $this->createUrl('createDelivery'),
                'htmlOptions' => array('class' => 'buttonS bGreen')
            ),
            array(
                'label' => Yii::t('DeliveryModule.default', 'CREATE_DELIVERY_MAIL'),
                'url' => $this->createUrl('create'),
                'htmlOptions' => array('class' => 'buttonS bGreen')
            )
        );

        $deliveryRecord = new Delivery('search');
        $deliveryRecord->unsetAttributes();  // clear any default values    
        if (isset($_GET['Delivery'])) {
            $deliveryRecord->attributes = $_GET['Delivery'];
        }
        $this->render('index', array('deliveryRecord' => $deliveryRecord));
    }

    public function actionCreate() {
        $this->actionUpdate(true);
    }

    public function actionUpdate($new = false) {
        $this->topButtons = false;
        if ($new === true) {
            $model = new Delivery;
            $model->unsetAttributes();
            $this->pageName = $model->t('PAGE_NAME',0);
        } else {
            $model = $this->loadModel($_GET['id']);
            $this->pageName = $model->t('PAGE_NAME',1);
        }
        $this->breadcrumbs = array(
            Yii::t('DeliveryModule.default', 'MODULE_NAME') => array('index'),
            $this->pageName
        );
        if (isset($_POST['Delivery'])) {
            $model->attributes = $_POST['Delivery'];
            //$this->performAjaxValidation($model);
            if ($model->validate()) {
                $model->save();
                $this->redirect(array('index'));
            }
        }
        $this->render('update', array('model' => $model));
    }

    public function actionCreateDelivery() {
        $this->topButtons = false;
        $model = new DeliveryForm;
        $delivery = Delivery::model()->findAll();
        $mails = array();
        $users = User::model()->subscribe()->findAll();
        $render = 'create';
        if (isset($_POST['DeliveryForm'])) {
            $model->attributes = $_POST['DeliveryForm'];
            //$this->performAjaxValidation($model);
            if ($model->validate()) {

                if ($model->from == 'all') {
                    foreach ($users as $user) {
                        $mails[] = $user->email;
                    }
                    //if (isset($delivery)) {
                    foreach ($delivery as $subscriber) {
                        $mails[] = $subscriber->email;
                    }
                    //} else {
                    //    $mails_subscriber = array();
                    //}
                    // $mails = array_merge($mails_users, $mails_subscriber);
                } elseif ($model->from == 'users') {
                    foreach ($users as $user) {
                        $mails[] = $user->email;
                    }
                } else {
                    foreach ($delivery as $subscriber) {
                        $mails[] = $subscriber->email;
                    }
                }


                if (Yii::app()->request->isAjaxRequest) {
                    $render = 'send';
                } else {
                    $render = 'create';
                }
            } else {
                if (Yii::app()->request->isAjaxRequest) {
                    $render = 'form';
                } else {
                    $render = 'create';
                }
                //Stops the request from being sent.
                //throw new CHttpException(404, 'Model has not been saved');
            }
        }


        $this->breadcrumbs = array(
            Yii::t('deliveryModule.default', 'MODULE_NAME') => array('index'),
            Yii::t('deliveryModule.default', 'CREATE_DELIVERY')
        );

        $this->render($render, array('users' => $users, 'deliveryRecord' => $deliveryRecord, 'delivery' => $delivery, 'model' => $model, 'mails' => $mails));
    }

    public function actionSendmail() {

        Yii::app()->request->enableCsrfValidation = false;
        if (Yii::app()->request->isAjaxRequest) {
            $host = $_SERVER['HTTP_HOST'];
            $mailer = Yii::app()->mail;
            $mailer->From = 'robot@' . $host;
            $mailer->FromName = Yii::app()->settings->get('core', 'site_name');
            $mailer->Subject = $_POST['themename'];
            $mailer->Body = $_POST['text'];
            $mailer->AddAddress($_POST['email']);
            $mailer->AddReplyTo('robot@' . $host);
            $mailer->isHtml(true);
            $mailer->Send();
            $mailer->ClearAddresses();
        }
    }

    public function actionSendNewProduct() {
        $products = ShopProduct::model()->newToDay()->findAll();
        if (count($products)) {
            foreach ($products as $product) {
                print_r($product->name);
                echo '<br>';
            }
            $this->setFlashMessage(Yii::t('core', 'Сообщение оправлено подписчикам.'));
        } else {
            $this->setFlashMessage(Yii::t('core', 'Новых товаров за сегодня небыло добавлено!'));
        }
        $this->redirect(array('index'));
    }

    public function loadModel($id) {
        $model = Delivery::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Дополнительное меню Контроллера.
     * @return array
     */
    public function getAddonsMenu() {
        return array(
            array(
                'label' => Yii::t('core', 'Отправить новые товары'),
                'url' => Yii::app()->createUrl('/admin/delivery/default/sendNewProduct'),
                'icon' => 'icon-envelope'
            ),
        );
    }

}
