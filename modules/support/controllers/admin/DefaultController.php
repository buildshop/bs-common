<?php

class DefaultController extends AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('SupportModule.default', 'MODULE_NAME');
        $ticket = new Ticket('search');
        $ticket->unsetAttributes();
        if (!empty($_GET['Ticket']))
            $ticket->attributes = $_GET['Ticket'];

        $this->breadcrumbs = array(
            $this->pageName
        );

        $this->render('index', array('ticket' => $ticket));
    }

    public function actionCreate() {
        $ticket = new Ticket;
        $this->pageName = Yii::t('SupportModule.default', 'MODULE_NAME');
        if (Yii::app()->request->isPostRequest) {
            $ticket->attributes = $_POST['Ticket'];
            $ticket->client_id = Yii::app()->params['client_id'];
            if ($ticket->validate()) {
                // print_r($ticket);
                if ($ticket->save()) {
                    $this->sendEmail($ticket);
                    Yii::app()->user->setFlash('success', 'Спасибо за обращение, в ближайшие время оператор вам ответить');

                    //$this->redirect(array('view','id'=>$ticket->id));
                }
            }
        }

        $this->render('create', array('model' => $ticket));
    }

    public function sendEmail(Ticket $model) {
        $config = Yii::app()->settings->get('core');
        $request = Yii::app()->request;
        $user = Yii::app()->user;
        $browser = CMS::detectBrowser(CMS::getagent());
        $platform = CMS::detectPlatform(CMS::getagent());
        $body = "
            


<p>Тема: {$model->name}</p>
<p>Сообщение: {$model->text}</p>

    <br><br>
<center><a style=\"font-size:24px;text-decoration:none;color:#fff;padding:10px;border:1px solid #333;background-color:#999;\" href=\"http://builshop.net/admin/support/?view={$model->id}\">Перейки к обращению</a></center>
<hr />
<p>Пользователь: {$user->login} #{$user->id}</p>
<p>Браузер: {$browser}</p>
<p>ПО: {$platform}</p>
<p>IP: {$request->userHostAddress}</p>

";
        $mailer = Yii::app()->mail;
        $mailer->From = 'noreply@' . $request->serverName;
        $mailer->FromName = $request->serverName;
        $mailer->Subject = 'Обращение в техподдержку';
        $mailer->Body = $body;
        foreach (Yii::app()->params['support_email'] as $email) {
            $mailer->AddAddress($email);
        }
        $mailer->AddReplyTo($config['admin_email']);
        $mailer->isHtml(true);
        $mailer->Send();
        $mailer->ClearAddresses();
    }

    public function actionView($id) {
        $ticket = Ticket::model()->with('messages')->findByPk($id);

        if (!$ticket)
            throw new CHttpException(404);
        if ($ticket->client_id != Yii::app()->params['client_id'])
            throw new CHttpException(401);
        $this->pageName = Yii::t('SupportModule.default', 'MODULE_NAME');


        $modelForm = new TicketMessage();
        if (Yii::app()->request->isPostRequest && $ticket->status != 0) {
            $modelForm->attributes = $_POST['TicketMessage'];
            $modelForm->ticket_id = $ticket->id;
            $modelForm->user_id = Yii::app()->params['client_id'];
            if ($modelForm->validate()) {
                $modelForm->save(false);
                Yii::app()->user->setFlash('success', Yii::t('app', 'SUCCESS_MSG_SAND'));
                $this->refresh();
            }
        }
        $this->render('view', array('model' => $ticket, 'modelForm' => $modelForm));
    }

}
