<?php

class TemplateController extends AdminController {

    public $topButtons = false;

    public function actionUi() {
        Yii::import('mod.core.models.less.*');
        if (isset($_POST['Less'])) {
            $path = Yii::getPathOfAlias('webroot.themes.default.less');

            Yii::import('app.phpless.lessc');
            $less = new lessc;
            $param = array();
            foreach ($_POST['Less'] as $key => $val) {
                $param[$key] = $val;
            }
            Yii::app()->settings->set('less', $param);
            // $less->setVariables($param);
            $less->compileFile($path . "/ui.less", Yii::getPathOfAlias('webroot.themes.default.assets.css') . "/ui-less.css");
        }
        $this->render('ui');
    }

    public function actionLess() {
        Yii::import('mod.core.models.less.*');
        if (isset($_POST['Less'])) {
            $path = Yii::getPathOfAlias('webroot.themes.default.less');

            Yii::import('app.phpless.lessc');
            $less = new lessc;
            $param = array();
            foreach ($_POST['Less'] as $key => $val) {
                $param[$key] = $val;
            }
            Yii::app()->settings->set('less', $param);
            $less->setVariables($param);
            /* $less->setVariables(array(
              'btn-default-bgcolor' => '#e0e0e0', //#e0e0e0
              'btn-primary-bgcolor' => '#265a88',
              'btn-success-bgcolor' => '#419641',
              'btn-info-bgcolor' => '#2aabd2',
              'btn-warning-bgcolor' => '#eb9316',
              'btn-danger-bgcolor' => '#c12e2a',
              )); */
            $less->compileFile($path . "/bootstrap-theme.less", Yii::getPathOfAlias('webroot.themes.default.assets.css') . "/bootstrap-theme.css");
        }
        $this->render('less', array(
            'gradient' => new LessGradient
                )
        );
    }

    public function actionOperation() {
        Yii::import('mod.core.components.fs');
        if (isset($_GET['operation'])) {

            //$fs = new fs(dirname(__FILE__) . DS . 'data' . DS . 'root' . DS);
            $fs = new fs(Yii::getPathOfAlias('webroot.themes'));
            try {
                $rslt = null;
                switch ($_GET['operation']) {
                    case 'get_node':
                        $node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : '/';
                        $rslt = $fs->lst($node, (isset($_GET['id']) && $_GET['id'] === '#'));
                        break;
                    case "get_content":
                        $node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : '/';
                        $rslt = $fs->data($node);
                        break;
                    case 'create_node':
                        $node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : '/';
                        $rslt = $fs->create($node, isset($_GET['text']) ? $_GET['text'] : '', (!isset($_GET['type']) || $_GET['type'] !== 'file'));
                        break;
                    case 'rename_node':
                        $node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : '/';
                        $rslt = $fs->rename($node, isset($_GET['text']) ? $_GET['text'] : '');
                        break;
                    case 'delete_node':
                        $node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : '/';
                        $rslt = $fs->remove($node);
                        break;
                    case 'move_node':
                        $node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : '/';
                        $parn = isset($_GET['parent']) && $_GET['parent'] !== '#' ? $_GET['parent'] : '/';
                        $rslt = $fs->move($node, $parn);
                        break;
                    case 'copy_node':
                        $node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : '/';
                        $parn = isset($_GET['parent']) && $_GET['parent'] !== '#' ? $_GET['parent'] : '/';
                        $rslt = $fs->copy($node, $parn);
                        break;
                    default:
                        throw new Exception('Unsupported operation: ' . $_GET['operation']);
                        break;
                }
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($rslt);
            } catch (Exception $e) {
                header($_SERVER["SERVER_PROTOCOL"] . ' 500 Server Error');
                header('Status:  500 Server Error');
                echo $e->getMessage();
            }
            die();
        }
    }

    public function actionIndex() {
        $this->pageName = 'Шаблон';
        if (isset($_POST['content'])) {
            if (!@file_put_contents(Yii::getPathOfAlias('webroot.themes') . DS . $_POST['file'], $_POST['content'])) {
                throw new CException(Yii::t('admin', 'Error write modules setting in {file}...', array('{file}' => $_POST['file'])));
            }
        }
        $this->render('index', array('themes' => $themes));
    }

    public function getAddonsMenu() {
        return array(
            array(
                'label' => Yii::t('CoreModule.admin', 'Шаблоны писем'),
                'url' => Yii::app()->createUrl('/admin/core/tplmail/index'),
                'icon' => 'icon-list',
                'visible' => Yii::app()->user->isSuperuser
            ),
        );
    }

}
