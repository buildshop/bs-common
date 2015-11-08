<?php

class PollModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id . '.components.*',
            $this->id . '.models.*',
        ));
    }

    public function afterInstall() {
        Yii::app()->settings->set($this->id, SettingsPollForm::defaultSettings());
        Yii::app()->database->import($this->id);
        return parent::afterInstall();
    }

    public function afterUninstall() {
        Yii::app()->settings->clear($this->id);

        $db = Yii::app()->db;
        $tablesArray = array(
            PollChoice::model()->tableName(),
            PollVote::model()->tableName(),
            Poll::model()->tableName(),
        );

        foreach ($tablesArray as $table) {
            $db->createCommand()->truncateTable($table);
            $db->createCommand()->dropTable($table);
        }
        return parent::afterUninstall();
    }

    public function getRules() {
        return array(
            'poll/<id:\d+>' => 'poll/default/view',
        );
    }

    public static function getAdminMenu() {
        $c = Yii::app()->controller->id;
        return array(
            'modules' => array(
                'items' => array(
                    array(
                        'label' => Yii::t('PollModule.default', 'MODULE_NAME'),
                        'url' => array('/admin/poll'),
                        'icon' => 'icon-paragraph-left',
                        'active' => ($c == 'admin/default') ? true : false,
                        'visible' => Yii::app()->user->isSuperuser,
                    ),
                ),
            ),
        );
    }

    public function getAdminSidebarMenu() {
        $c = Yii::app()->controller->id;
        return array(
            $this->adminMenu['modules']['items'][0],
            array(
                'label' => Yii::t('core', 'SETTINGS'),
                'url' => array('/admin/poll/settings'),
                'active' => ($c == 'admin/settings') ? true : false,
                'icon' => 'icon-cogs',
                'visible' => Yii::app()->user->isSuperuser
            )
        );
    }

    public static function getInfo() {
        return array(
            'name' => Yii::t('PollModule.default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '1.0',
            'icon' => 'fa-paragraph-left',
            'config_url' => Yii::app()->createUrl('/admin/poll/default/index'),
            'description' => Yii::t('PollModule.default', 'MODULE_DESC'),
            'url' => Yii::app()->createUrl('/poll/'),
        );
    }

}
