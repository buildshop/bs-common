<?php

class ContactsModule extends WebModule {

    const MODULE_ICON = 'flaticon-phone-call';

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*'
        ));
    }

    public function afterInstall() {
        Yii::app()->database->import($this->id);
        Yii::app()->settings->set($this->id, ConfigContactForm::defaultSettings());
        return parent::afterInstall();
    }

    public function afterUninstall() {
        Yii::app()->settings->clear('contacts');
        $db = Yii::app()->db;
        $db->createCommand()->dropTable(ContactsMaps::model()->tableName());
        $db->createCommand()->dropTable(ContactsMarkers::model()->tableName());
        $db->createCommand()->dropTable(ContactsRouter::model()->tableName());
        $db->createCommand()->dropTable(ContactsRouterTranslate::model()->tableName());
        return parent::afterUninstall();
    }

    public function getRules() {
        return array(
            'contacts' => 'contacts/default/index',
            'contacts/captcha' => 'contacts/default/captcha',
        );
    }

    public static function getAdminMenu() {
        return array(
            'modules' => array(
                'items' => array(array(
                        'label' => Yii::t('ContactsModule.default', 'MODULE_NAME'),
                        'visible' => Yii::app()->user->isSuperuser,
                        'url' => array('/contacts/admin/default'),
                        'icon' => self::MODULE_ICON,
                    ),
                )
            )
        );
    }

    public function getAdminSidebarMenu() {
        $c = Yii::app()->controller->id;
        return array(
            array(
                'label' => Yii::t('ContactsModule.default', 'MODULE_NAME'),
                'url' => Yii::app()->createUrl('contacts/admin/default'),
                'active' => $this->hasActive('admin/contacts'),
                'icon' => self::MODULE_ICON,
                'visible' => Yii::app()->user->isSuperuser
            ),
            array(
                'label' => Yii::t('ContactsModule.default', 'MAPS'),
                'url' => Yii::app()->createUrl('contacts/admin/maps/index'),
                'active' => $this->hasActive('admin/maps'),
                'icon' => 'flaticon-map',
                'visible' => Yii::app()->user->isSuperuser
            ),
            array(
                'label' => Yii::t('ContactsModule.default', 'MARKERS'),
                'url' => Yii::app()->createUrl('contacts/admin/markers/index'),
                'active' => $this->hasActive('admin/markers'),
                'icon' => 'flaticon-map-2',
                'visible' => Yii::app()->user->isSuperuser
            ),
            array(
                'label' => Yii::t('ContactsModule.default', 'ROUTER'),
                'url' => Yii::app()->createUrl('contacts/admin/router/index'),
                'active' => $this->hasActive('admin/router'),
                'icon' => 'flaticon-location-route',
                'visible' => Yii::app()->user->isSuperuser
            ),
        );
    }

    public static function getInfo() {
        return array(
            'name' => Yii::t('ContactsModule.default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '3.0',
            'icon' => self::MODULE_ICON,
            'description' => Yii::t('ContactsModule.default', 'MODULE_DESC'),
            'url' => Yii::app()->createUrl('/contacts/admin/default/index'),
        );
    }

}
