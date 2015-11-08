<?php

class LicenseCMS extends CComponent {

    public $timeout = 320; //curl timeout
    private static $run = null;

    public static function run() {
        static $rub = null;
        if ($rub === null) {
            $rub = new LicenseCMS();
        }
        return($rub);
    }

    public function check() {
        $domain = Yii::app()->request->serverName;
        if (!Yii::app()->request->isAjaxRequest) {
            $license = $this->config['license_key'];
            if (file_exists($this->filePathLicense)) {
                $tmp = file_get_contents($this->filePathLicense);
                if ($tmp == md5(date('Ymd') . $domain . $license)) {
                    return true;
                } else {
                    $this->connect();
                }
            } else {
                $this->connect();
            }
        }
    }

    /**
     * Чтение временого файла.
     * @return array
     */
    public function readData() {
        if (file_exists($this->filePathData)) {
            $data = file_get_contents($this->filePathData);
            return unserialize($data);
        } else {
            return null;
        }
    }

    /**
     * Connecting to CMS server 
     */
    protected function connect() {
        $domain = Yii::app()->request->serverName;
        $license = $this->config['license_key'];
        $serverUrl = 'http://corner-cms.com/license';
        if (Yii::app()->hasComponent('curl')) {
            $curl = Yii::app()->curl;
            $curl->options = array(
                'timeout' => 320,
                'setOptions' => array(
                    CURLOPT_HEADER => false
                ),
            );
            $connent = $curl->run($serverUrl, array(
                'format' => 'json',
                'key' => $license,
                'domain' => $domain,
                'v' => Yii::app()->version,
                'locale' => Yii::app()->language,
                'email' => $this->config['admin_email']
                    ));
            if (!$connent->hasErrors()) {
                $result = CJSON::decode($connent->getData());
                //if (isset($result)) {
                if ($result['status'] == 'success') {
                    $this->writeFile($this->filePathLicense, md5(date('Ymd') . $domain . $license));
                    if (isset($result['data'])) {
                        $this->writeFile($this->filePathData, serialize($result['data']));
                    }
                } else {

                    // Yii::app()->settings->set('core', array('site_close' => 1));
                    // Yii::app()->settings->set('core', array('site_close_text' => $result['message']));
                }
                if (isset($result['message'])) {
                    Yii::app()->controller->setFlashMessage($result['message']);
                    // $this->getAlertLicense($result['message']);
                }
                //}else {
                //     die('fatal error');
                // }
            } else {
                $error = $connent->getErrors();
                Yii::app()->controller->setFlashMessage('Connect error: ' . $error->code . ': ' . $error->message);
                print_r($error);
            }
        } else {
            throw new Exception('Component "curl" not found.');
        }
    }

    protected function getFilePathData() {
        return Yii::getPathOfAlias('webroot.protected.runtime') . "/tmp_data.txt";
    }

    protected function getFilePathLicense() {
        return Yii::getPathOfAlias('webroot.protected.runtime') . "/tmp_license.txt";
    }

    protected function getConfig() {
        return Yii::app()->settings->get('core');
    }

    public function getAlertLicense($msg) {
        Yii::app()->tpl->alert('danger', $msg, false);
    }

    protected function writeFile($file, $content) {
        $fp = fopen($file, "wb");
        fwrite($fp, $content);
        fclose($fp);
    }

}
