<?php

/**
 * Базовый модуль.
 * 
 * @uses CWebModule
 * @package components
 */
class BaseModule extends CWebModule {

    public $_rules = array();
    private $_config = array();
    public $aliasAdminSidebarMenu = 'mod.admin.components.AdminModuleMenu';
    public $_assetsUrl = null;
    public $baseModel;
    public $license;
    protected $_info = array();
    protected $_adminMenu = array();

    public function getAdminSidebarMenu() {
        return false;
    }
/*
    public function beforeControllerAction($controller, $action) {

        if (parent::beforeControllerAction($controller, $action)) {

            if (!Yii::app()->access->check($controller->module->access)) {
                throw new CHttpException(401);
            }

            return true;
        } else {
            return false;
        }
    }
*/
    public function getRules() {
        return $this->_rules;
        // return array();
    }

    /**
     *
     * @var array
     */
    public $sidebar = true;

    public function installColumns($columns = array()) {
        /* В проэкте. */
    }

    /**
     * 
     * @return array
     */
    public static function getInfo() {
        return array('name' => 'unknown',
            'author' => 'unknown',
            'version' => 'unknown',
            'description' => Yii::t('core', 'Описание недоступно'),
            'url' => ''
        );
    }

    public function initAdmin() {
        $this->setImport(array(
            'admin.models.*',
            'admin.components.*',
            'admin.widgets.*',
        ));
        $this->defaultController = 'admin';
    }

    /**
     * Publish admin stylesheets,images,scripts,etc.. and return assets url
     *
     * @access public
     * @return string Assets url
     */
    public function getAssetsUrl() {
        if ($this->_assetsUrl === null) {
            $this->_assetsUrl = Yii::app()->assetManager->publish(
                    Yii::getPathOfAlias('mod.' . $this->id . '.assets'), false, -1, YII_DEBUG
            );
        }
        return $this->_assetsUrl;
    }

    /**
     * Set assets url
     *
     * @param string $url
     * @access public
     * @return void
     */
    public function setAssetsUrl($url) {
        $this->_assetsUrl = $url;
    }

    /**
     * Method will be called after module installed
     */
    public function afterInstall() {
        Yii::app()->cache->flush();
        Yii::app()->widgets->clear();
        return true;
    }

    /**
     * Method will be called after module removed
     */
    public function afterUninstall() {
        Yii::app()->cache->flush();
        Yii::app()->widgets->clear();
        return true;
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

}
