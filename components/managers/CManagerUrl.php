<?php

/**
 * Manager urls
 */
class CManagerUrl extends CUrlManager {

    /**
     * Init
     * @access public
     */
    public function init() {
        $this->_loadModuleUrls();
        parent::init();
    }

    /**
     * Create url based on current language.
     * @param mixed $route
     * @param array $params
     * @param string $ampersand
     * @param boolean $respectLang
     * @access public
     * @return string
     */
    public function createUrl($route, $params = array(), $ampersand = '&', $respectLang = true) {
        $result = parent::createUrl($route, $params, $ampersand);

        if ($respectLang === true) {
            $langPrefix = Yii::app()->languageManager->getUrlPrefix();
            if ($langPrefix)
                $result = '/' . $langPrefix . $result;
        }

        return $result;
    }

    /**
     * @access protected
     */
    protected function _loadModuleUrls() {
        $cacheKey = 'url_manager';
        $rules = Yii::app()->cache->get($cacheKey);
        if (YII_DEBUG || !$rules) {
            $rules = array();
            $modules = Yii::app()->getModules();

            foreach ($modules as $mid=>$module) {

                $moduleClass = Yii::app()->getModule($mid);
                if (isset($moduleClass->rules)) {

                    $rules = array_merge($moduleClass->rules, $rules);
                }
            }
            Yii::app()->cache->set($cacheKey, $rules, 3600*24);
        }
        $this->rules = array_merge($rules, $this->rules);
    }

}
