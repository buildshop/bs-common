<?php

/**
 * Базовый класс контроллеров.
 * 
 * @author Andrew (Panix) Semenov <andrew.panix@gmail.com>
 * @uses RController
 * @package components
 */
//Yii::import('mod.users.UsersModule');

class Controller extends CController {

    public $backend = false;
    protected $_assetsUrl = false;
    private $_baseAssetsUrl;
    protected $_edit_mode = false;
    protected $_messages;
    public $breadcrumbs = array();
    public $pageKeywords;
    public $pageName;
    public $pageDescription;
    private $_pageTitle;
    public $layout = '';
    public $currentModule;
    public $license;

    //public $timezone;


    public function getCacheTime() {
        return !YII_DEBUG ? 0 : 3600;
    }

    public function getBaseAssetsUrl() {
        if ($this->_baseAssetsUrl === null) {
            $this->_baseAssetsUrl = Yii::app()->getAssetManager()->publish(
                    Yii::getPathOfAlias('app.assets'), false, -1, YII_DEBUG
            );
        }
        return $this->_baseAssetsUrl;
    }


    /**
     * @return string Показывает информацию о сгенерируемой страницы.
     */
    public function getPageGen() {
        $sql_stats = YII::app()->db->getStats();
        return Yii::t('app', 'PAGE_GEN', array(
                    '{TIME}' => number_format(Yii::getLogger()->getExecutionTime(), 3, '.', ' '),
                    '{MEMORY}' => round(memory_get_peak_usage() / (1024 * 1024), 2),
                    '{DB_QUERY}' => $sql_stats[0],
                    '{DB_TIME}' => number_format($sql_stats[1], 2, '.', ' '),
                ));
    }

    /**
     * 
     * @return array
     */
    public function behaviors() {
        return array_merge(parent::behaviors(), array('LayoutBehavior' => array('class' => 'LayoutBehavior')));
    }

    public function getEdit_mode() {
        return $this->_edit_mode;
    }

    protected function setEdit_mode() {
        if (Yii::app()->user->isSuperuser && isset($_REQUEST['edit_mode'])) {
            $mode = $_REQUEST['edit_mode'];
            if ($mode == 1) {
                Yii::app()->session['edit_mode'] = true;
            } else {
                unset(Yii::app()->session['edit_mode']);
            }
        }
        $this->_edit_mode = Yii::app()->session['edit_mode'];
    }


    /**
     * 
     * @param type $view
     * @return type
     */
    protected function beforeRender($view) {
        $this->initLayout();
        $cs = Yii::app()->clientScript;
        if ($this->getEdit_mode()) {
            $this->widget('ext.tinymce.TinymceWidget');
            $cs = Yii::app()->clientScript;
            $cs->registerScriptFile($this->getBaseAssetsUrl() . '/js/edit_mode.js');
            $cs->registerCssFile($this->getBaseAssetsUrl() . '/css/edit_mode.css');
        }
        $cs->registerMetaTag('CORNER CMS ' . Yii::app()->version, 'author');
        $cs->registerMetaTag(($this->pageDescription !== null) ? $this->pageDescription : '', 'description');
        $cs->registerMetaTag(($this->pageKeywords !== null) ? $this->pageKeywords : '', 'keywords');
        return parent::beforeRender($view);
    }

    /**
     * 
     * @param type $message
     */
    public function addFlashMessage($message) {
        $currentMessages = Yii::app()->user->getFlash('messages');

        if (!is_array($currentMessages))
            $currentMessages = array();

        Yii::app()->user->setFlash('messages', CMap::mergeArray($currentMessages, array($message)));
    }

    public function setPageTitle($title) {
        $this->_pageTitle = $title;
    }

    /**
     * Register assets file of theme
     * @return string
     */
    private function registerAssets() {
        $assets = Yii::getPathOfAlias('webroot') . '/themes/' . Yii::app()->settings->get('core', 'theme') . '/assets';
        $url = Yii::app()->getAssetManager()->publish($assets, false, -1, YII_DEBUG);
        $this->_assetsUrl = $url;
    }

    /**
     * @return string
     */
    public function getAssetsUrl() {
        return $this->_assetsUrl;
    }

    /**
     * 
     * @return string Timezone 
     */
    public function getTimezone() {
        $user = Yii::app()->user;
        $config = Yii::app()->settings->get('core');
        if (!$user->isGuest) {
            if ($user->timezone) {
                $tz = $user->timezone;
            } elseif (isset(Yii::app()->session['timezone'])) {
                $tz = Yii::app()->session['timezone'];
            } else {
                $tz = $config['default_timezone'];
            }
        } else {
            if (isset(Yii::app()->session['timezone'])) {
                $tz = Yii::app()->session['timezone'];
            } else {
                $tz = $config['default_timezone'];
            }
        }
        return $tz;
    }

    public function init() {
        $user = Yii::app()->user;
        //  unserialize($user->db);
        /*  $db = array(
          'host'=>'corner.mysql.ukraine.com.ua',
          'dbname'=>'corner_shopcms',
          'username'=>'corner_shopcms',
          'password'=>'v4chxvhp',
          );
          echo serialize($db); */

        /* $userdb = array(
          'class' => 'app.DbConnection',
          'connectionString' => 'mysql:host=corner.mysql.ukraine.com.ua;dbname='.$user->db['dbname'].'',
          'username' => $user->db['username'],
          'password' => $user->db['password'],
          'tablePrefix' => 'panix_',
          'charset' => 'utf8',

          'enableProfiling' => YII_DEBUG,
          'enableParamLogging' => YII_DEBUG,
          'schemaCachingDuration' => !YII_DEBUG ? 0 : 3600,
          ); */


        // Yii::app()->setComponent('dbUser', $userdb);
        Yii::app()->language = Yii::app()->languageManager->active->code;
        // $this->timezone = $this->getTimezone();
        $this->setEdit_mode();
        $this->addComponents();
        $this->currentModule = $this->module->id;
        //$this->checkLicense();
        //LicenseCMS::run()->check();
        $config = Yii::app()->settings->get('core');
        Yii::setPathOfAlias('currentTheme',  Yii::getPathOfAlias("themes.{$config['theme']}"));
        $this->backup();
        $this->registerAssets();
        parent::init();
        if ($config['auto_detect_language']) {
            CMS::autoDetectLanguage();
        }
    }

    private function addComponents() {
        $components = ComponentsModel::model()
                // ->cache($this->cacheTime)
                ->findAll();
        $compArray = array();
        foreach ($components as $component) {
            $compArray[$component->name] = array(
                'class' => $component->class
            );
        }
        Yii::app()->setComponents($compArray);
    }

    private function backup() {
        $security = Yii::app()->settings->get('security');
        if ($security['backup_db'] && Yii::app()->user->isSuperuser) {
            if ($security['backup_time_cache'] < time()) {
                /* Записываем новое текущие время + указанное время */
                Yii::app()->settings->set('security', array('backup_time_cache' => time() + $security['backup_time']));
                /* Делаем Backup */
                Yii::app()->database->export();
            }
        }
    }

    /**
     * 
     * @return string
     */
    public function getPageTitle() {
        $title = Yii::app()->settings->get('core', 'site_name');
        if (!empty($this->_pageTitle)) {
            $title = $this->_pageTitle.=' / ' . $title;
        }
        return $title;
    }

    public function setEmView($view) {
        if (file_exists($this->getViewFile($view))) {
            if ($this->getEdit_mode()) {
                return (file_exists($this->getViewFile($view . '_em'))) ? $view . '_em' : $view;
            } else {
                return $view;
            }
        } else {
            return $view;
        }
    }

    /**
     * 
     * @param string $view
     * @param array $data
     * @param boolean $return
     * @param boolean $processOutput
     */
    public function render($view, $data = null, $return = false, $processOutput = true) {
        if (Yii::app()->request->isAjaxRequest === true)
            parent::renderPartial($view, $data, $return, $processOutput);
        else
            parent::render($this->setEmView($view), $data, $return);
    }

    /**
     * 
     * @param type $message
     */
    public function setFlashMessage($message) {
        $currentMessages = Yii::app()->user->getFlash('messages');
        if (!is_array($currentMessages))
            $currentMessages = array();

        Yii::app()->user->setFlash('messages', CMap::mergeArray($currentMessages, array($message)));
    }

    /*
      protected function checkLicense() {

      $domain = Yii::app()->request->serverName;
      $license = Yii::app()->settings->get('core', 'license_key');
      $filePath = Yii::getPathOfAlias('webroot.protected.runtime') . "/tmp_license.txt";
      if (file_exists($filePath)) {
      $tmp = file_get_contents($filePath);
      if ($tmp == md5(date('Ymd') . $domain . $license)) {
      return true;
      } else {
      $this->connect();
      }
      } else {
      $this->connect();
      }
      } */
    /*
      private function connect() {
      $domain = Yii::app()->request->serverName;
      $license = Yii::app()->settings->get('core', 'license_key');
      $serverUrl = 'http://cms.corner.com.ua/license';
      $filePath = Yii::getPathOfAlias('webroot.protected.runtime') . "/tmp_license.txt";
      $fileTmpData = Yii::getPathOfAlias('webroot.protected.runtime') . "/tmp_data.txt";
      $curl = Yii::app()->curl;
      $curl->options = array(
      'timeout' => 320,
      'setOptions' => array(
      CURLOPT_HEADER => false
      )
      );
      $connent = $curl->run($serverUrl, array(
      'format' => 'json',
      'license' => $license,
      'domain' => $domain,
      'v' => Yii::app()->version,
      'locale' => Yii::app()->language,
      'email' => Yii::app()->settings->get('core', 'admin_email')
      ));
      if (!$connent->hasErrors()) {
      $result = CJSON::decode($connent->getData());
      if ($result['code'] == 0) {
      $content = md5(date('Ymd') . $domain . $license);
      $fp = fopen($filePath, "wb");
      fwrite($fp, $content);
      fclose($fp);


      $fpData = fopen($fileTmpData, "wb");
      fwrite($fpData, serialize($result['data']));
      fclose($fpData);
      } elseif ($result['code'] == 1) {
      //вариант 1
      Yii::app()->settings->set('core', array('site_close' => 1));
      Yii::app()->settings->set('core', array('site_close_text' => $result['message']));
      }
      if (isset($result['message']))
      $this->setFlashMessage($result['message']);
      print_r($result);
      } else {
      $error = $connent->getErrors();
      $this->setFlashMessage('Connect error: ' . $error->code . ': ' . $error->message);
      print_r($error);
      }
      } */
}
