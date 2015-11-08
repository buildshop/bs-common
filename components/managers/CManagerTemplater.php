<?php

class CManagerTemplater {
    public $types =array('info', 'warning', 'success', 'failure', 'danger');
    public function init() {
        
    }

    public function openWidget($var = array()) {
        $this->render('_openWidget', $var);
    }

    public function closeWidget($var = array()) {
        $this->render('_closeWidget', $var);
    }

    public function alert($type, $text, $close = true) {
        $id = 'alert' . md5($type . CMS::translit($text));
        if (!isset($_COOKIE[$id])) {
            $str = implode(', ', $this->types);
            if (in_array($type, $this->types)) {
                $this->render('_' . __FUNCTION__, array('type' => $type, 'message' => $text, 'close' => $close));
            } else {
                throw new CHttpException(403, Yii::t('app', 'ERROR_TPL_' . strtoupper(__FUNCTION__), array(
                            '{tpl}' => __FUNCTION__,
                            '{type}' => $type,
                            '{types}' => CHtml::encode($str)
                                )
                ));
            }
        }
    }

    private function render($tpl, $array = array()) {

        $module = Yii::app()->controller->module->id;
        $controller = Yii::app()->controller->id;
        if ($module == 'install') {
            $theme = 'default';
        } else {
            $theme = Yii::app()->theme->getName();
        }
        if (Yii::app()->controller instanceof AdminController) {
            $layouts = array(
                "mod.{$module}.views.layouts." . $tpl . "_{$controller}",
                "mod.{$module}.views.layouts." . $tpl,
                "mod.admin.views.layouts." . $tpl,
            );
        } else {
            $layouts = array(
                "mod.{$module}.views.layouts." . $tpl,
                "webroot.themes.{$theme}.views.{$module}.layouts." . $tpl,
                "webroot.themes.{$theme}.views.layouts." . $tpl,
            );
        }

        foreach ($layouts as $layout) {
            if (file_exists(Yii::getPathOfAlias($layout) . '.php')) {
                $render = $layout;
                break;
            }
        }

        Yii::app()->controller->renderPartial($render, $array, false, false);
    }

}