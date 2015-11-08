<?php

/**
 * Контролле регистрации пользовотеля
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.users.controllers
 * @uses Controller
 */
class RegisterController extends Controller {

    public function allowedActions() {
        return 'register';
    }

    /**
     * Дополнительные действия
     * @return array
     */
    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
                'transparent' => true,
                'testLimit' => 1,
                'padding' => 0,
                'height' => 40
            //'foreColor' => 0x348017
            ),
        );
    }

    /**
     * Действие регистрации пользователя.
     */
    public function actionRegister() {
        $config = Yii::app()->settings->get('users');
        if (!Yii::app()->user->isGuest)
            Yii::app()->request->redirect('/');

        $user = new User('register');
        $this->pageName = Yii::t('UsersModule.default', 'REGISTRATION');
        $this->pageTitle = $this->pageName;
        $this->breadcrumbs = array($this->pageName);
        $view = 'register';
        if (Yii::app()->request->isPostRequest && isset($_POST['User'])) {
            $user->attributes = $_POST['User'];
            if (Yii::app()->settings->get('core', 'forum') == null)
                $user->email = $user->login;
            $user->active = ($config['register_nomail']) ? 1 : 0;
            if ($user->validate()) {
                
                if ($user->save()) {
                    Yii::app()->authManager->assign('Authenticated', $user->id);
                    CIntegrationForums::instance()->register($user->login, $_POST['User']['password'], $user->email);
                }
                // Add user to authenticated group
                
                $this->addFlashMessage(Yii::t('UsersModule.default', 'REG_SUCCESS'));
                // Authenticate user
                $identity = new EngineUserIdentity($user->login, $_POST['User']['password']);
                if ($identity->authenticate()) {

                    Yii::app()->user->login($identity, Yii::app()->user->rememberTime);
                    if ($config['register_nomail']) {
                        Yii::app()->request->redirect($this->createUrl('/users/profile/index'));
                    } else {
                        //  $this->sendMail($_POST['User']['email']);
                        $view = 'success_register';
                    }
                } else {
                    die('authenticate(): Error');
                }
            } else {
                //die(print_r($user->getErrors()));
            }
        }

        $this->render($view, array(
            'user' => $user
        ));
    }

    /**
     * Отправка уведомление зарегистрированному пользователю
     * @param string $tomail
     */
    private function sendMail($tomail) {
        $site_name = Yii::app()->settings->get('core', 'site_name');
        $host = $_SERVER['HTTP_HOST'];
        $theme = Yii::t('admin', '{site_name} уведомляет о наличии интересующего Вас продукта', array(
                    '{site_name}' => $site_name
        ));
        $mailer = Yii::app()->mail;
        $mailer->From = 'noreply@' . $host;
        $mailer->FromName = $site_name;
        $mailer->Subject = $theme;
        $mailer->Body = '<html>
<body>

Здравствуйте!<br>
<p>
    Магазин ' . $sitename . ' уведомляет Вас о том,
    что появился в наличии продукт <a href="<?= $product->absoluteUrl ?>"><?=$product->name?></a>.
</p>

<p>
    Будем рады обслужить Вас и ответить на любые вопросы!
</p>

</body>
</html>';
        $mailer->AddAddress($tomail);
        $mailer->AddReplyTo(Yii::app()->params['adminEmail']);
        $mailer->isHtml(true);
        $mailer->Send();
        $mailer->ClearAddresses();
    }
    
    public function addUser(User $user){
        
    }

}
