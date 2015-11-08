<?php

class ContactsModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*'
        ));
    }

    public function initAdmin() {
        $this->publishAdminAssets();
        parent::initAdmin();
    }

    public function afterInstall() {

        Yii::app()->database->import($this->id);

        Yii::app()->settings->set($this->id, array(
            'form_emails' => 'andrew.panix@gmail.com',
            'tempMessage' => '<p>Имя отправителя: <strong>[SENDER_NAME]</strong></p>
<p>Email отправитиля: <strong>[SENDER_EMAIL]</strong></p>
<p>Телефон: <strong>[SENDER_PHONE]</strong></p>
<p>==============================</p>
<p><strong>Сообщение:</strong></p>
<p>[SENDER_MESSAGE]</p>',
            'address' => '',
            'phone' => '',
            'skype' => '',
            'seo_title' => 'Контактная информация',
            'seo_keywords' => 'Контактная информация',
            'seo_description' => 'Контактная информация',
            'yandex_map_zoomControl' => 1,
            'yandex_map_zoomControl_top' => 40,
            'yandex_map_zoomControl_left' => 5,
            'yandex_map_typeSelector' => 1,
            'yandex_map_typeSelector_top' => 5,
            'yandex_map_typeSelector_right' => 5,
            'yandex_map_mapTools' => 1,
            'yandex_map_mapTools_top' => 5,
            'yandex_map_mapTools_left' => 5,
            'yandex_map_zoom' => 17,
            'yandex_map_width' => 150,
            'yandex_map_height' => 350,
            'yandex_map_center' => '46.4886, 30.7353',
            'yandex_map_balloon_content' => 'content my balloon',
        ));
        Yii::app()->intallComponent('contact', 'mod.contacts.components.ContactComponent');
        return parent::afterInstall();
    }

    public function afterUninstall() {
        Yii::app()->settings->clear('contacts');
        Yii::app()->unintallComponent('contact');
        $db = Yii::app()->db;
        $db->createCommand()->dropTable(ContactsOffice::model()->tableName());
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
                        'icon' => 'flaticon-telephone',
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
                'active' => ($c == 'admin/contacts') ? true : false,
                'icon' => 'flaticon-telephone',
                'visible' => Yii::app()->user->isSuperuser
            ),
            array(
                'label' => Yii::t('ContactsModule.admin', 'OFFICE', 1),
                'url' => Yii::app()->createUrl('contacts/admin/office/index'),
                'active' => ($c == 'admin/office') ? true : false,
                'icon' => 'flaticon-map-2',
                'visible' => Yii::app()->user->isSuperuser
)

        );
    }

    public static function getInfo() {
        return array(
            'name' => Yii::t('ContactsModule.default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '2.0',
            'icon' => 'flaticon-telephone',
            'description' => Yii::t('ContactsModule.default', 'MODULE_DESC'),
            'url' => Yii::app()->createUrl('/contacts/admin/default/index'),
        );
    }

    public function publishAdminAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
        if (is_dir($assets)) {
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/admin_contacts.js', CClientScript::POS_HEAD);
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

}
