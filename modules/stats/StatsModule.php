<?php

class StatsModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
        ));
    }

    public function afterInstall() {

        Yii::app()->settings->set('stats', array(
            'param' => 'param',
        ));
        Yii::app()->database->import($this->id);
        return parent::afterInstall();
    }

    public function afterUninstall() {
        Yii::app()->settings->clear('stats');
        Yii::app()->db->createCommand()->dropTable(StatsSurf::model()->tableName());
        return parent::afterUninstall();
    }

    public function getRules() {
        return array(
                // '/yandex-market.xml' => '/yandexMarket/default/index',
        );
    }

    public static function getAdminMenu() {
        $c = Yii::app()->controller->module->id;
        return array(
            'modules' => array(
                'items' => array(
                    array(
                        'label' => Yii::t('StatsModule.default', 'MODULE_NAME'),
                        'url' => Yii::app()->createUrl('/admin/stats'),
                        'active' => ($c == 'stats') ? true : false,
                        'icon' => 'flaticon-stats',
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                ),
            ),
        );
    }

    public function getAdminSidebarMenu() {
         $c = Yii::app()->controller->id;
          return array(
              array(
                  'label'=>Yii::t('StatsModule.default', 'MODULE_NAME'),
                  'url'=>Yii::app()->createUrl('/admin/stats'),
                  'active' => ($c == 'admin/default') ? true : false,
                  'icon'=>'flaticon-stats'
              ),
              array(
                  'label'=>Yii::t('StatsModule.default', 'BROWSERS'),
                  'url'=>Yii::app()->createUrl('/admin/stats/browsers'),
                  'active' => ($c == 'admin/browsers') ? true : false,
                  'icon'=>'flaticon-stats'
              ),
              array(
                  'label'=>Yii::t('StatsModule.default', 'TIMEVISIT'),
                  'url'=>Yii::app()->createUrl('/admin/stats/timevisit'),
                  'active' => ($c == 'admin/timevisit') ? true : false,
                  'icon'=>'flaticon-stats'
              ),
              array(
                  'label'=>Yii::t('StatsModule.default', 'PAGEVISIT'),
                  'url'=>Yii::app()->createUrl('/admin/stats/pagevisit'),
                  'active' => ($c == 'admin/pagevisit') ? true : false,
                  'icon'=>'flaticon-stats'
              ),
          ); 
    }

    public static function getInfo() {
        return array(
            'name' => Yii::t('StatsModule.default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '1.0',
            'icon' => 'flaticon-stats',
            'description' => Yii::t('StatsModule.default', 'MODULE_DESC'),
            'config_url' => Yii::app()->createUrl('/admin/stats'),
            'url' => Yii::app()->createUrl('/shop/'),
        );
    }

}
