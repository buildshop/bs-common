<?php

class Turbosms extends CSMS {

    public $alias = 'mod.sms.components.services.turbosms';

    public function getConfig() {
        return Yii::app()->settings->get('turbosms.Turbosms');
    }

    /**
     * @var bool
     */
    protected $client;

    /**
     * @var string
     */
    protected $wsdl = 'http://turbosms.in.ua/api/wsdl.html';

    /**
     * Send sms
     *
     * @param $text
     * @param $phones
     */
    public function send($text, $phones) {
        if (!self::DEBUG || !$this->client) {
            $this->connect();
        }
        if (!is_array($phones)) {
            $phones = array($phones);
        }
        foreach ($phones as $phone) {
            $message = 'Сообщения успешно отправлено';
            if (!self::DEBUG) {
                $result = $this->client->SendSMS(array(
                    'sender' => $this->config['sender'],
                    'destination' => $phone,
                    'text' => $text
                        ));
                if ($result->SendSMSResult->ResultArray[0] != 'Сообщения успешно отправлены') {
                    $message = 'Сообщения не отправлено (ошибка: "' . $result->SendSMSResult->ResultArray[0] . '")';
                }
            }
            $this->saveToDb($text, $phone, $message);
        }
        self::log($message);
    }

    /**
     * Save sms to db
     *
     * @param $text
     * @param $phone
     * @param $message
     */
    public function saveToDb($text, $phone, $message) {
        $model = new SMSHistoryTurbo();
        $model->text = $text;
        $model->phone = $phone;
        $model->status = $message . (self::DEBUG ? ' (тестовый режим)' : '');
        $model->save();
    }

    /**
     * @return SoapClient
     * @throws CHttpException
     */
    protected function connect() {
        if ($this->client) {
            return $this->client;
        }

        $client = new SoapClient($this->wsdl);
        if (!$this->config['login'] || !$this->config['password']) {
            throw new CHttpException(500, 'Enter login and password from Turbosms');
        }
        $result = $client->Auth([
            'login' => $this->config['login'],
            'password' => $this->config['password'],
                ]);
        if ($result->AuthResult . '' != 'Вы успешно авторизировались') {
            throw new CHttpException(500, $result->AuthResult);
        }
        $this->client = $client;
        return $this->client;
    }

    /**
     * Get balance
     *
     * @return int
     */
    public function getBalance() {
        if (!$this->client)
            $this->connect();
        $result = $this->client->GetCreditBalance();
        return intval($result->GetCreditBalanceResult);
    }

    /**
     * @param $messageId
     *
     * @return mixed
     */
    public function getMessageStatus($messageId) {
        if (!$this->client)
            $this->connect();
        $result = $this->client->GetMessageStatus(array('MessageId' => $messageId));
        return $result->GetMessageStatusResult;
    }

}

?>
