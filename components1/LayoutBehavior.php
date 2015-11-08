<?php

class LayoutBehavior extends CBehavior {

    public function initLayout() {
        $owner = $this->getOwner();

        if (empty($owner->layout)) {
            if(isset(Yii::app()->theme)){
                $theme = Yii::app()->theme->getName();
            }else{
                Yii::log(__CLASS__.' Ошибка: Системе не удалось найти указанную тему', 'error');
               // throw new CException(__CLASS__.' Ошибка: Системе не удалось найти указанную тему');
            }
            $module = Yii::app()->controller->module->id;
            $controller = Yii::app()->controller->id;
            $action = Yii::app()->controller->action->id;

            $cacheId = "layout_{$theme}_{$module}_{$controller}_{$action}";

            if (!$owner->layout = Yii::app()->cache->get($cacheId)) {
                $layouts = array(
                    "webroot.themes.{$theme}.views.{$module}.layouts.{$controller}-{$action}",
                    "webroot.themes.{$theme}.views.{$module}.layouts.{$controller}",
                    "webroot.themes.{$theme}.views.{$module}.layouts.default",
                    "webroot.themes.{$theme}.views.layouts.default",
                );

                foreach ($layouts as $layout) {
                    if (file_exists(Yii::getPathOfAlias($layout) . '.php')) {
                        $owner->layout = $layout;
                        break;
                    }
                }
                Yii::log('Cache time: '.Yii::app()->settings->get('core','cache_time'), 'info');
                Yii::app()->cache->set($cacheId, $owner->layout, (int)Yii::app()->settings->get('core','cache_time'));
            }
        }
    }
}