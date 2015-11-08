<?php

/**
 * Приложение системы.
 * 
 * @uses CWebApplication
 * @author Andrew (Panix) Semenov <andrew.panix@gmail.com>
 * @package components
 */
class Application extends CWebApplication {

    const VERSION = '1.0.0';

    private $_theme = null;

    public function getVersion() {
        return self::VERSION;
    }

    /**
     * @param null $config
     */
    public function __construct($config = null) {
        parent::__construct($config);
    }

    /**
     * Initialize component
     */
    public function init() {

        Yii::setPathOfAlias('root', dirname(Yii::getPathOfAlias('webroot')));

        $this->setModules(Yii::app()->user->plan['modules']);
        parent::init();
    }

    /**
     * @return CTheme
     */
    public function getTheme() {
        $globConfig = Yii::app()->settings->get('core');
        if ($this->_theme === null) {
            if (Yii::app()->settings->get('users', 'change_theme')) {
                if (isset(Yii::app()->user->theme)) {
                    $theme = Yii::app()->user->theme;
                } else {
                    $theme = $globConfig['theme'];
                }
            } else {
                $theme = $globConfig['theme'];
            }
            $this->_theme = $this->getThemeManager()->getTheme($this->eTheme($theme));
        }
        return $this->_theme;
    }

    private function eTheme($theme) {
        $c = Yii::app()->settings->get('core');
        if (!empty($c['etheme'])) {
            $now = time();
            $timeStart = strtotime($c['etheme_start']);
            $timeEnd = strtotime($c['etheme_end']);
            if ($timeStart < $now) {
                if ($timeEnd < $now) {
                    $t = $theme;
                } else {
                    $t = $c['etheme'];
                }
                return $t;
            }
        } else {
            return $theme;
        }
    }

}
