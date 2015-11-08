<?php

/**
 * Базовый класс для админ контроллеров.
 * 
 * @uses Controller
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package components
 */
Yii::import('mod.core.CoreModule');

class AdminController extends Controller {
    public $isAjax;
    /**
     *
     * @var string 
     */
    public $layout = 'mod.admin.views.layouts.main';

    /**
     *
     * @var array 
     */
    public $menu = array();
    public $pageName;

    /**
     * Отоброжение кнопкок.
     * @var boolean 
     */
    public $topButtons = null;

    /**
     *
     * @var array 
     */
    protected $_addonsMenu = array();

    /**
     *
     * @var array 
     */
    protected $_sidebarWidgets = array();

    public function init() {
        Yii::app()->user->loginUrl = array('/admin/auth');
        $this->module->initAdmin();
        parent::init();
    }

    /**
     * @param CAction $action
     * @return bool
     */
    public function beforeAction($action) {
        // Allow only authorized users access
        if (Yii::app()->user->isGuest && get_class($this) !== 'AuthController') {
            Yii::app()->request->redirect($this->createUrl('/admin/auth'));
        }
        Yii::app()->errorHandler->errorAction = '/admin/errors/error';
        $messagesArray = array(
            'error' => array(
                '404' => Yii::t('error', '404')
            ),
            'cancel' => Yii::t('app', 'CANCEL'),
            'save' => Yii::t('app', 'SAVE'),
            'close' => Yii::t('app', 'CLOSE'),
            'ok' => Yii::t('app', 'OK'),
        );

        $config = CJavaScript::encode($messagesArray);
        Yii::import('mod.core.components.yandexTranslate');
        Yii::app()->clientScript->registerScript('app2', '
            var translate_object_url = "' . Yii::app()->settings->get('core', 'translate_object_url') . '";
            var yandex_translate_apikey = "' . yandexTranslate::API_KEY . '";
            app.langauge="' . Yii::app()->language . '";
            app.token="' . Yii::app()->request->csrfToken . '";
            app.message=' . $config, CClientScript::POS_HEAD);
    

        return true;
    }

    /**
     * action
     */
    public function actionCreate() {
        $this->actionUpdate(true);
    }

    /**
     * Action воостановление настроек по умолчанию
     * @param object $model
     */
    public function actionResetSettings($model) {
        if (isset($model)) {
            $mdl = new $model;
            Yii::app()->settings->set($mdl->getModuleId(), $mdl::defaultSettings());
            $this->setFlashMessage(Yii::t('app', 'SUCCESS_RESET_SETTINGS'));
            $this->redirect(array('/admin/' . $mdl->getModuleId() . '/settings'));
        }
    }

}
