<?php

/**
 * PanelWidget 
 * 
 * @property array $menu Массив меню
 * @uses CWidget
 * @access superuser aka Admin
 */
class PanelWidget extends CWidget {

    public $menu = array();

    public function init() {
        if (Yii::app()->user->isSuperuser)
            $this->registerScripts();
    }

    public function run() {
        if (Yii::app()->user->isSuperuser) {
            Yii::import('application.modules.admin.widgets.EngineMainMenu');
            $modules = EngineMainMenu::findMenu();
            $this->menu = array(
                array(
                    'label' => 'Система',
                    'url' => 'javascript:void(0)',
                    'icon' => 'icon-wrench',
                    'items' => Yii::app()->getModule('core')->adminMenu['system']['items']
                ),
                array(
                    'label' => 'Модули',
                    'url' => 'javascript:void(0)',
                    'icon' => 'icon-menu',
                    'items' => $modules['modules']['items']
                ),
                $modules['orders'],
                $modules['shop']
            );
            $this->render('view', array('menu' => $this->menu));
        }
    }

    private function registerScripts() {
        $assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.admin.sitePanel.assets'), false, -1, YII_DEBUG
        );
        $cs = Yii::app()->clientScript;
        $cs->registerCssFile($assetsUrl . '/admin_panel.css');
        $cs->registerScriptFile($assetsUrl . '/admin_panel.js');
        $cs->registerCoreScript('cookie');
    }

}

?>
