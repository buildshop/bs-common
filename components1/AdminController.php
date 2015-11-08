<?php

/**
 * Базовый класс для админ контроллеров.
 * 
 * @uses Controller
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package components
 */
//Yii::import('mod.core.CoreModule');

class AdminController extends Controller {

    public $backend = true;

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

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
         /*   array('allow',
                'users' => array('@'),
                'roles' => array('admin'),
                'message' => 'Access Denied.',
            ),*/
            array('allow',
                'users' => array('@'),
                'roles' => array('admin'),
                'message' => 'Access Denied.',
               // 'expression'=>function ($user, $rule){
              //       $this->allowAccessPanel($user,$rule);
              //  }
            ),
            array('deny', // deny all users
                'users' => array('*'),
                'message' => 'Access Denied.',
            ),
        );
    }
    public function allowAccessPanel($user,$rule){
      //  print_r($user->id);
      //  die();

    }
    protected function getIsAjax() {
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_REQUEST['_ajax']) && $_REQUEST['_ajax']) {
                $assets = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.mbmenu.source'), false, -1, YII_DEBUG);
                Yii::app()->getClientScript()->registerScriptFile($assets . '/ajax.js', CClientScript::POS_HEAD);
                return true;
            }
            return false;
        } else {
            return false;
        }
    }

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

        $this->checkAccessPlan();
        return true;
    }

    private function checkAccessPlan() {
        
        //Yii::app()->package->access();
        
        $id = $this->id; // Controller ID
        $cid = $this->action->id; //Action ID
        $mid = $this->module->id; // module ID
        $plan = Yii::app()->user->plan;
        if ($id == 'admin/products' && $cid == 'create') {
            if ($plan['productLimit'] != '*') {
                $countCheck = ShopProduct::model()->count();
                if ($countCheck >= $plan['productLimit']) {
                    Yii::app()->user->setFlash('error', Yii::t('plan','ERROR_PRODUCTLIMIT'));
                    $this->redirect(array('index'));
                }
            }
       /** } elseif ($mid == 'xml') {
            if (!$plan[$mid]) {
                Yii::app()->user->setFlash('error', Yii::t('plan','ERROR_ACCESS_MODULE',array(
                    '{module}'=>$mid
                )));
                $this->redirect('/admin/?d=1');
            }
        } elseif ($mid == 'csv') {
            if (!$plan[$mid]) {
                Yii::app()->user->setFlash('error', Yii::t('plan','ERROR_ACCESS_MODULE',array(
                    '{module}'=>$mid
                )));
                $this->redirect('/admin/?d=1');
            }*/
        }
    }

    /**
     * create action for all modules
     */
    public function actionCreate() {
        $this->actionUpdate(true);
    }

    /**
     * action refresh settings model
     */
    public function actionResetSettings($model) {
        if (isset($model)) {
            $mdl = new $model;
            Yii::app()->settings->set($mdl->getModuleId(), $mdl::defaultSettings());
            Yii::app()->user->setFlash('success', Yii::t('app', 'SUCCESS_RESET_SETTINGS'));
            $this->redirect(array('/admin/' . $mdl->getModuleId() . '/settings'));
        }
    }

    /**
     * 
     * @return array
     */
    protected function getSidebarWidgets() {
        return array(
            'bottom' => array(
                array('alias' => 'mod.admin.widgets.online.OnlineWidget', 'params' => array())
            ),
            'top' => array(
                array('alias' => 'ext.nowOnlineAdmin.nowOnlineAdmin', 'params' => array()),
                array('alias' => 'ext.nowOnlineAdmin.nowOnlineAdmin', 'params' => array())
            ),
            'static' => array(
                array('alias' => 'admin.widgets.todayIncome', 'params' => array())
            ),
        );
    }

}
