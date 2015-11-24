<?php

class RemindPasswordForm extends FormModel {

   const MODULE_ID = 'users';

    /**
     * @var string
     */
    public $email;

    /**
     * @var User
     */
    public $user;

    /**
     * @return array
     */
    public function rules() {
        return array(
            array('email','required'),
            array('email', 'email'),
            array('email', 'validateEmail'),
        );
    }

    /**
     * Validate user email and send email message
     */
    public function validateEmail() {
        $this->user = User::model()->findByAttributes(array(
            'email' => $this->email
                ));

        if ($this->user)
            return true;
        else
            $this->addError('email', $this->t('ERROR_VALID_EMAIL'));
    }

    /**
     * Send recovery email
     */
    public function sendRecoveryMessage() {
        $this->user->recovery_key = $this->generateKey(10);
        $this->user->recovery_password = $this->generateKey(15);
        $this->user->save(false);
        $host=Yii::app()->request->serverName;
        $mailer = Yii::app()->mail;
        $mailer->From = 'noreply@' . $host;
        $mailer->FromName = Yii::app()->settings->get('core', 'site_name');
        $mailer->Subject = Yii::t('UsersModule.default', 'Восстановление пароля');
        $mailer->Body = $this->body;
        $mailer->AddReplyTo('noreply@' . $host);
        $mailer->isHtml(false);
        $mailer->AddAddress($this->email);
        $mailer->Send();
    }

    /**
     * Get email message body
     */
    public function getBody() {
        $content = "Здравствуйте, " . $this->user->username . "!

Вы получили это письмо потому, что вы (либо кто-то, выдающий себя за вас)
попросили выслать новый пароль для вашей учётной записи.
Если вы не просили выслать пароль, то не обращайте внимания на
это письмо, если же подобные письма будут продолжать приходить, обратитесь
к администратору сайта.

Перейдите по ссылке для активации нового пароля " . Yii::app()->createAbsoluteUrl('/users/remind/activatePassword', array('key' => $this->user->recovery_key)) . "


Ваш новый пароль: " . $this->user->recovery_password . "";
        return $content;
    }

    /**
     * Generate key and password
     * @return string
     */
    public function generateKey($size) {
        $result = '';
        $chars = '1234567890qweasdzxcrtyfghvbnuioplkjnm';
        while (mb_strlen($result, 'utf8') < $size)
            $result .= mb_substr($chars, rand(0, mb_strlen($chars, 'utf8')), 1);

        if (User::model()->countByAttributes(array('recovery_key' => $result)) > 0)
            $this->generateKey($size);

        return strtoupper($result);
    }

}
