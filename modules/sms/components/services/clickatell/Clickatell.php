<?php

//SEE https://github.com/arcturial/clickatell
class Clickatell extends CSMS {

    private static $statusCodes = array(
        "001" => "The message ID is incorrect or reporting is delayed.",
        "002" => "The message could not be delivered and has been queued for attempted redelivery.",
        "003" => "Delivered to the upstream gateway or network (delivered to the recipient).",
        "004" => "Confirmation of receipt on the handset of the recipient.",
        "005" => "There was an error with the message, probably caused by the content of the message itself.",
        "006" => "The message was terminated by a user (stop message command) or by our staff.",
        "007" => "An error occurred delivering the message to the handset. 008 0x008 OK Message received by gateway.",
        "009" => "The routing gateway or network has had an error routing the message.",
        "010" => "Message has expired before we were able to deliver it to the upstream gateway. No charge applies.",
        "011" => "Message has been queued at the gateway for delivery at a later time (delayed delivery).",
        "012" => "The message cannot be delivered due to a lack of funds in your account. Please re-purchase credits.",
        "014" => "Maximum MT limit exceeded The allowable amount for MT messaging has been exceeded.",
    );

    public static function getStatus($code) {
        return isset(self::$statusCodes[$code]) ? self::$statusCodes[$code] : "unknown error";
    }

    const HTTP_GET = "GET";
    const HTTP_POST = "POST";
    const HTTP_DELETE = "DELETE";

    private $secure = false;
    private $agent = "ClickatellPHP/2.1";

    protected function getSendDefaults($parameters) {
        return array_merge(array(
                    'mo' => 1,
                    'callback' => 7), $parameters
        );
    }

    /**
     * Abstract CURL usage. This helps with testing and extendibility
     * accross multiple API types.
     *
     * @param string $uri     The endpoint
     * @param strong $data    POST data or query string
     * @param array  $headers Header array
     * @param string $method  HTTP method
     *
     * @return DecoderClickatell
     */
    protected function curl($uri, $data, $headers = array(), $method = self::HTTP_GET) {
        // This is the clickatell endpoint. It doesn't really change so
        // it's safe for us to "hardcode" it here.
        $host = "api.clickatell.com";
        $uri = ltrim($uri, "/");
        $uri = ($this->secure ? 'https' : 'http') . '://' . $host . "/" . $uri;
        $method == "GET" && $uri = $uri . "?" . $data;
        $curlInfo = curl_version();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->agent . ' curl/' . $curlInfo['version'] . ' PHP/' . phpversion());
        ($method == "POST") && curl_setopt($ch, CURLOPT_POST, 1) && curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return new DecoderClickatell($result, $httpCode);
    }

    /**
     * Set the user agent for the CURL adapter.
     *
     * @param string $agent The agent string
     *
     * @return Clickatell
     */
    public function setUserAgent($agent) {
        $this->agent = $agent;
        return $this;
    }

    public $alias = 'mod.sms.components.services.clickatell';
    private $baseurl = 'http://api.clickatell.com';

    /**
     * Send sms
     *
     * @param $text
     * @param $phones
     */
    public function sendOLD($text, $phones) {

        if ($this->config['api_type'] == 'HTTP') {
            $this->apiHTTP($text, $phones);
        } elseif ($this->config['api_type'] == 'REST') {
            
        }
    }

    protected function get($uri, $args, $method = self::HTTP_GET) {
        $args = array_merge(
                $args, array(
            'user' => $this->config['user'],
            'password' => $this->config['password'],
            'api_id' => $this->config['api_id']
                )
        );
        $query = http_build_query($args);
        return $this->curl($uri, $query, array(), $method)->unwrapLegacy();
    }

    public function send($message, $to, $extra = array()) {
        $extra['to'] = implode(",", (array) $to);
        $extra['text'] = $message;
        $args = $this->getSendDefaults($extra);
        try {
            $response = $this->get('http/sendmsg', $args);
        } catch (Exception $e) {
            $response = array(
                'error' => $e->getMessage(),
                'errorCode' => $e->getCode()
            );
        }
        !is_int(key($response)) && $response = array($response);
        $return = array();
        // We won't throw any exceptions if an error occurs since we could have
        // multiple messages in the packet and not all of them might have failed.
        foreach ($response as $entry) {
            $return[] = (object) array(
                        'id' => (isset($entry['ID'])) ? $entry['ID'] : false,
                        'destination' => (isset($entry['To'])) ? $entry['To'] : $args['to'],
                        'error' => (isset($entry['error'])) ? $entry['error'] : false,
                        'errorCode' => (isset($entry['code'])) ? $entry['code'] : false
            );
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function getBalance() {
        $response = $this->get('http/getbalance', array());
        return (object) array(
                    'balance' => (float) $response['Credit']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function queryMessage($apiMsgId) {
        return $this->getMessageCharge($apiMsgId);
    }

    /**
     * {@inheritdoc}
     */
    public function routeCoverage($msisdn) {
        try {
            $response = $this->get('utils/routeCoverage', array('msisdn' => $msisdn));
            return (object) array(
                        'routable' => true,
                        'destination' => $msisdn,
                        'charge' => $response['Charge']
            );
        } catch (Exception $exception) {
            return (object) array(
                        'routable' => false,
                        'destination' => $msisdn,
                        'charge' => 0
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageCharge($apiMsgId) {
        $response = $this->get('http/getmsgcharge', array('apimsgid' => $apiMsgId));
        return (object) array(
                    'id' => $apiMsgId,
                    'status' => $response['status'],
                    'description' => self::getStatus($response['status']),
                    'charge' => (float) $response['charge']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function stopMessage($apiMsgId) {
        $response = $this->get('http/delmsg', array('apimsgid' => $apiMsgId));
        return (object) array(
                    'id' => $response['ID'],
                    'status' => $response['Status'],
                    'description' => self::getStatus($response['Status']),
        );
    }

    protected function apiHTTP($text, $phones) {

        $text = urlencode($text);
        // auth call
        $url = "$this->baseurl/http/auth?user={$this->config['user']}&password={$this->config['password']}&api_id={$this->config['api_id']}";

        // do auth call
        $ret = file($url);

        // explode our response. return string is on first line of the data returned
        $sess = explode(":", $ret[0]);
        if ($sess[0] == "OK") {
            $sess_id = trim($sess[1]); // remove any whitespace
            if (!is_array($phones)) {
                $phones = array($phones);
            }
            foreach ($phones as $phone) {
                $url = "$this->baseurl/http/sendmsg?session_id=$sess_id&to=$phone&text=$text";

                // do sendmsg call
                $ret = file($url);
                $send = explode(":", $ret[0]);

                if ($send[0] == "ID") {
                    echo "success message ID: " . $send[1];
                } else {
                    echo "send message failed";
                }
            }
        } else {
            echo "Authentication failure: " . $ret[0];
        }
    }

    protected function apiREST() {
        
    }

    public function getConfig() {
        return Yii::app()->settings->get('clickatell.Clickatell');
    }

}

?>
