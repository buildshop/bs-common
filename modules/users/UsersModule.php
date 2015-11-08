<?php

/**
 * Модуль пользователей
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.users
 * @uses BaseModule
 */
class UsersModule extends WebModule {

    const ICON = 'flaticon-users';

    public function init() {
        Yii::trace('Loaded "users" module.');
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
        ));
        parent::init();
    }

    /**
     * Init admin-level models, componentes, etc...
     */
    public function initAdmin() {
        Yii::trace('Init users module admin resources.');
        parent::initAdmin();
    }

    public function getRules() {
        return array(
            'users/login' => 'users/login/login',
            'users/register' => 'users/register/register',
            'users/register/captcha/*' => 'users/register/captcha',
            'users/profile' => 'users/profile/index',
            'users/profile/<user_id:([\d]+)>' => 'users/profile/userInfo',
            'users/profile/orders' => 'users/profile/orders',
            //'users/profile/avatar' => 'users/profile/avatar',
            'users/profile/saveAvatar' => 'users/profile/saveAvatar',
            'users/profile/getAvatars' => 'users/profile/getAvatars',
            'users/logout' => 'users/login/logout',
            'users/remind/activatePassword/<key>' => array('users/remind/activatePassword'),
            'users/favorites/add' => 'users/favorites/add',
            'users/favorites/delete' => 'users/favorites/delete',
            'users/favorites/<action>/<page:([\d]+)>' => 'users/favorites/<action>',
            'users/favorites/<action>' => 'users/favorites/<action>',
            'users/friends/<action>' => 'users/friends/<action>',
            'users/ajax/<action>' => 'users/ajax/<action>',
        );
    }

    public static function getAdminMenu() {
        $c = Yii::app()->controller->id;
        $m = Yii::app()->controller->module->id;
        return array(
            'users' => array(
                'label' => Yii::t('UsersModule.default', 'MODULE_NAME'),
                'icon' => self::ICON,
                'active' => ($m == 'users') ? true : false,
                //   'visible'=> Yii::app()->user->checkAccess('Publisher') || Yii::app()->user->checkAccess('Managers'),
                'items' => array(
                    array(
                        'label' => Yii::t('UsersModule.default', 'MODULE_NAME'),
                        'url' => Yii::app()->createUrl('users/admin/default'),
                        'icon' => 'flaticon-user',
                        'active' => ($c == 'admin/default') ? true : false,
                    ),
                    array(
                        'label' => Yii::t('app', 'Группы пользователей'),
                        'url' => Yii::app()->createUrl('users/admin/group'),
                        'active' => ($c == 'admin/group') ? true : false,
                        'icon' => 'flaticon-user-group',
                    ),
                    array(
                        'label' => Yii::t('app', 'SETTINGS'),
                        'url' => Yii::app()->createUrl('users/admin/settings'),
                        'active' => ($c == 'admin/settings') ? true : false,
                        'icon' => 'flaticon-settings',
                    )
                )
            ),
        );
    }

    public function getAdminSidebarMenu() {
        Yii::import('ext.mbmenu.AdminMenu');
        $mod = new AdminMenu;
        $items = $mod->findMenu('users');
        return $items['items'];
    }

    public static function getInfo() {
        return array(
            'name' => Yii::t('UsersModule.default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '0.1',
            'icon' => self::ICON,
            'url' => Yii::app()->createUrl('/users/admin/default/index'),
            'description' => Yii::t('UsersModule.default', 'MODULE_DESC'),
        );
    }

}
