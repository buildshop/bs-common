<?php

/**
 * clickatell.com
 * 
 * @example 
 * 
 * <code>
 * $sms = new SMS;
 * $sms->to='PHONE NUMBER';
 * $sms->text='TEXT';
 * if($sms->send()){
 *     echo 'success';
 * }else{
 *     echo 'fail';
 * }
 * </code>
 */
class SMS {

    /**
     * API requred options
     * @var $api_id API ID
     * @var $password API password
     * @var $user API user
     * @var $baseurl url
     */
    private $api_id = 3530150;
    private $password = 'cZbBNSRbcIJfMA';
    private $user = 'panix1988';
    private $baseurl = 'http://api.clickatell.com';

    /**
     * Номер получателя
     * @var int|string
     */
    public $to;

    /**
     * Текст сообщение
     * @var type string
     */
    public $text;

    /**
     * @return boolean 
     * @throws CException
     */
    public function send() {

        if (isset($this->to) && isset($this->text)) {
            $url = "$this->baseurl/http/auth?user=$this->user&password=$this->password&api_id=$this->api_id";

            // do auth call
            $ret = file($url);

            // explode our response. return string is on first line of the data returned
            $sess = explode(":", $ret[0]);
            if ($sess[0] == "OK") {
                $sess_id = trim($sess[1]); // remove any whitespace
                $url = "$this->baseurl/http/sendmsg?session_id=$sess_id&to=" . $this->to . "&text=" . $this->text . "";

                // do sendmsg call
                $ret = file($url);
                $send = explode(":", $ret[0]);
                if ($send[0] == "ID") {
                    return true;
                    //echo "successnmessage ID: " . $send[1];
                } else {
                    return false;
                    //echo "send message failed";
                }
            } else {
                throw new CException('SMS: Authentication failure: ' . $ret[0]);
            }
        } else {
            throw new CException('SMS: не найден параметр to/text');
        }
    }

}

?>