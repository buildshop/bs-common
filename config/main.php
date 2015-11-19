<?php

//core path
$commonPath = dirname(__DIR__) . DS . '..' . DS . '..' . DS.'common';
$path = dirname(__DIR__);

Yii::setPathOfAlias('baseController', Yii::getPathOfAlias('application.controllers'));
return array(
     //'basePath' => dirname(__FILE__) . DS . '..',

    'language' => 'ru',
    'name' => 'CORNER CMS',
    'preload' => array('log', 'maintenanceMode', 'ipblocker', 'package'),
    'import' => array(
        'common.components.*',
        'common.components.helpers.*',
        'common.components.validators.*',
        'common.modules.core.models.*',
        'common.models.*',
        'common.modules.users.models.User',
        'common.modules.users.models.UserGroup',
        'common.components.integration.forums.*',
        'common.components.forms.*',
    ),
    'controllerMap' => array(
       'site' => 'common.controllers.SiteController'
   ),
    'defaultController' => 'site',
    'aliases' => array(
       // 'core' => $path,
        'common' => $path,
        'mod' => 'common.modules',
        'app' => 'common.components',
        'ext' => 'common.extensions',
        'themes' => 'webroot.themes',
    ),
    'components' => array(
        'currency' => array(
            'class' => 'mod.shop.components.CurrencyManager',
        ),
        'cart' => array(
            'class' => 'mod.cart.components.Cart',
        ),
        'less' => array(
            'class' => 'app.less.LessCompiler',
        ),
        'lessnew' => array(
            'class' => 'app.lessnew.LessComponent',
        ),
        'package' => array('class' => 'app.Package'),
        'messages' => array(
            'basePath' => $path . DS . 'messages'
        ),
        // 'runtimePath' => dirname(__DIR__) . '/../runtime',
        'dbUser' => require($path . '/config/_db.php'),
        'session' => array(
            'class' => 'app.DbHttpSession',
            'connectionID' => 'db',
            'autoCreateSessionTable' => true
        ),
        'clientScript' => array(
            'scriptMap' => array(
                'jquery-ui.css' => false,
            )
        ),
        'maintenanceMode' => array('class' => 'app.MaintenanceMode.MaintenanceMode'),
        'user' => array(
            'allowAutoLogin' => true,
            'class' => 'BaseUser',
            'loginUrl' => '/users/login'
        ),
        'request' => array(
            'class' => 'app.HttpRequest',
            'enableCsrfValidation' => true,
            'csrfTokenName' => 'token',
            'enableCookieValidation' => true,
            'noCsrfValidationRoutes' => array(
                '/admin/ajax/autocomplete',
                '/comments/edit',
                '/ajax/like',
                '/admin',
                '/users/profile/getAvatars',
                '/users/profile/saveAvatar',
                '/users/login',
                '/comments/create',
                '/ajax/rating',
                '/users/favorites',
                '/cart/payment',
                '/cart/recount',
                '/processPayment',
                '/exchange1c',
                '/notify',
            //  '/ajax/uploadify/upload'
            )
        ),
        'errorHandler' => array('errorAction' => 'site/error'),
        'authManager' => array(
            'class' => 'CDbAuthManager',
            'connectionID' => 'db',
        // 'defaultRoles' => array('Guest'),
        ),
        'geoip' => array(
            'class' => 'app.geoip.CGeoIP',
            'mode' => YII_DEBUG === true ? 'STANDARD' : 'MEMORY_CACHE',
        ),
        'mail' => array(
            'class' => 'ext.mailer.EMailer',
            'CharSet' => 'utf-8',
        ),
        'img' => array('class' => 'app.addons.CImageHandler'),
        'ipblocker' => array('class' => 'app.bannedIP.BannedIP'),
        'cache' => array(
            'class' => 'CFileCache', //CFileCache,CDbCache,CDummyCache
        //'connectionID' => 'db',
        //'cacheTableName' => '{{cache}}'
        ),
        /* 'cache' => array(
          'class' => 'CMemCache',
          'useMemcached' => false,
          'serializer' => false,
          'keyPrefix' => 'CORNER_CMS',
          'servers' => array(
          array('host' => 'localhost', 'port' => 11211, 'weight' => 60),
          ),
          ), */
        'languageManager' => array('class' => 'app.managers.CManagerLanguage'),
        'database' => array('class' => 'app.managers.CManagerDatabase'),
        'access' => array('class' => 'app.managers.CManagerAccess'),
        'settings' => array('class' => 'app.managers.CManagerSettings'),
        'tpl' => array('class' => 'app.managers.CManagerTemplater'),
        'blocks' => array('class' => 'app.managers.CManagerBlocks'),
        'widgets' => array('class' => 'app.managers.CManagerFinderWidgets'),
        'urlManager' => require($path . '/config/_urlManager.php'),
        'curl' => array('class' => 'app.addons.Curl'),
        'log' => array(
            'class' => 'CLogRouter',
            'enabled' => true, //YII_DEBUG
            'routes' => array(
                array(
                    'class' => 'ext.loganalyzer.LALogRoute',
                    'levels' => 'info, error, warning, sql, sms',
                ),
                array(
                    'class' => 'ext.debug-toolbar.YiiDebugToolbarRoute',
                    'ipFilters' => array('127.0.0.1', '195.78.247.104'),
                    'enabled' => true && Yii::app()->user->isSuperuser
                ),
            ),
        ),
    ),
    //'params'=>array(
    //    'support_email'=>array('andrew.panix@gmail.com')
   // )
);
