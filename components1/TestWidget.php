<?php

/**
 * class TestWidget
 * 
 * @version 1.0
 * @author Andrew S. <andrew.panix@gmail.com>
 * @link http://cms.corner.com.ua CORNER CMS
 * @copyright (c) 2015, Andrew S.
 * 
 * @name $cs CClientScript
 * @name $assetsUrl assetManager path
 * @name $assetsPath assets alias path
 * @name $renderOptions render options array
 */
class TestWidget extends CWidget {

    private $_config = array();
    protected $cs;
    protected $assetsUrl;
    protected $assetsPath;
    protected $renderOptions = array();
    public $test;

    public function __construct($owner = null) {
        $this->test = $owner;
        parent::__construct($owner);
    }

    public function init() {
        $settings = CMap::mergeArray($this->getSettings(), $this->getDefaultSettings());
        $this->configure($settings);
        $assetManager = Yii::app()->assetManager;
        $this->cs = Yii::app()->clientScript;
        $this->assetsUrl = $assetManager->publish(Yii::getPathOfAlias($this->assetsPath . '.assets'), false, -1, YII_DEBUG);
    }

    public function run() {
        $this->render($this->skin, $this->renderOptions);
    }

    public static function t($message, $params = array()) {
        return Yii::t(get_class($this) . '.default', $message, $params);
    }

    /**
     * Default settings
     * @return array
     */
    public function getDefaultSettings() {
        return array();
    }

    /**
     * Widget settings
     * @return array
     */
    public function getSettings() {
        return Yii::app()->settings->get('news');
    }

    public function __get($name) {
        if (array_key_exists($name, $this->_config))
            return $this->_config[$name];
        else
            return parent::__get($name);
    }

    public function __set($name, $value) {
        try {
            parent::__set($name, $value);
        } catch (CException $e) {
            $this->_config[$name] = $value;
        }
    }

    /**
     * Configure widget
     * @param array $config
     */
    public function configure($config) {
        if (is_array($config)) {
            foreach ($config as $key => $value)
                $this->$key = $value;
        }
    }

}

?>
