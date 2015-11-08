<?php

class TranslatesController extends AdminController {

    const PATH_MOD = 'webroot.protected.modules';
    const PATH_APP = 'webroot.protected.messages';

    public $topButtons = false;

    public function actionTester() {

        $this->generateMessagesModules('fr');
    }

    public function generateMessagesModules($locale) {
        $modules = ModulesModel::getModules();
        $t = new yandexTranslate;
        $result = array();
        $num = -1;
        $params = array();
        foreach ($modules as $key => $mod) {
            $pathDefault = Yii::getPathOfAlias(self::PATH_MOD . '.' . $key . '.messages.ru');
            $listfile = CFileHelper::findFiles($pathDefault, array(
                        'fileTypes' => array('php'),
                        'absolutePaths' => false
                    ));
            $path = Yii::getPathOfAlias(self::PATH_MOD . '.' . $key . '.messages' . DS . $locale);
            //CFileHelper::createDirectory($path, 0777);


            CFileHelper::copyDirectory($pathDefault, $path, array(
                'fileTypes' => array('php'),
                'level' => 1,
            ));

            foreach ($listfile as $file) {
                //   $file = str_replace('.php', '', $file);
                $openFileContent = self::PATH_MOD . ".{$key}.messages.{$locale}";
                $contentList = include(Yii::getPathOfAlias($openFileContent) . DS . $file);
                // foreach($contentList as $pkey=>$value){

                foreach ($contentList as $pkey => $val) {
                    $params[] = $val;
                    $num++;
                    $spec[$num] = $pkey;
                }

                $response = $t->translate(array('ru', $locale), $contentList);
                foreach ($response['text'] as $k => $v) {
                    $result[$spec[$k]] = $v;
                }



                if (!@file_put_contents($path . DS . $file, '<?php

/**
 * Message translations. (auto translate)
 * 
 * Each array element represents the translation (value) of a message (key).
 * If the value is empty, the message is considered as not translated.
 * Messages that no longer need translation will have their translations
 * enclosed between a pair of \'@@\' marks.
 * 
 * @author Andrew (Panix) Semenov <andrew.panix@gmail.com>
 * @package modules.messages.' . $locale . '
 */
return ' . var_export($result, true) . ';')) {
                    throw new CException(Yii::t('admin', 'Error write modules setting in {file}...', array('{file}' => $file)));
                }
            }
            die('finish '.$key);
        }
        die('Complate');
        // $locale
    }

    public function actionIndex() {
        $this->pageName = Yii::t('core', 'TRANSLATES');
        $this->breadcrumbs = array(Yii::t('core', 'SYSTEM') => array('admin/index'), $this->pageName);

        $this->render('index');
    }

    public function actionAjaxGet() {
        $type = $_POST['type'];
        $tree = array();
        if ($type == 'core') {
            $view = '_ajaxGetCore';
            $tree = $this->getArray(Yii::getPathOfAlias(self::PATH_APP));
        } else {
            $view = '_ajaxGetModules';
            $tree = ModulesModel::getModules();
        }
        $this->render($view, array('tree' => $tree));
    }

    public function actionAjaxGetLocale() {
        $type = $_POST['type'];
        $mod = $_POST['module'];
        $array = array();
        if ($type == 'core') {
            $dir = Yii::getPathOfAlias(self::PATH_APP);
        } else {
            $dir = Yii::getPathOfAlias(self::PATH_MOD . '.' . $mod . '.messages');
        }
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file != "." && $file != "..")
                $array[$file] = $file;
        }
        $this->render('_ajaxGetLocale', array('array' => $this->getArray($dir)));
    }

    public function actionAjaxGetLocaleFile() {
        $mod = $_POST['module'];
        $locale = $_POST['locale'];
        $type = $_POST['type'];
        if ($type == 'core') {
            $dir = Yii::getPathOfAlias(self::PATH_APP . '.' . $locale);
        } else {
            $dir = Yii::getPathOfAlias(self::PATH_MOD . '.' . $mod . '.messages.' . $locale);
        }
        $this->render('_ajaxGetLocaleFile', array('tree' => $this->getArray($dir)));
    }

    public function actionAjaxOpen() {
        $mod = $_POST['module'];
        $locale = $_POST['locale'];
        $file = $_POST['file'];
        $addonsLang = $_POST['lang'];
        $type = $_POST['type'];
        if ($type == 'modules') {
            $fullPath = self::PATH_MOD . '.' . $mod . '.messages';
            $dir = Yii::getPathOfAlias($fullPath . '.' . $locale) . DS . $file;
        } else {
            $fullPath = self::PATH_APP;
            $dir = Yii::getPathOfAlias($fullPath . '.' . $locale) . DS . $file;
        }

        if (isset($_POST['TranslateForm'])) {
            $trans = array();
            foreach ($_POST['TranslateForm'] as $key => $val) {
                if (is_array($val)) {
                    $param = array();
                    foreach ($val as $key2 => $value) {
                        $param[] = $key2 . '#' . $value;
                    }
                    $trans[stripslashes($key)] = implode('|', $param);
                } else {
                    $trans[stripslashes($key)] = $val;
                }
            }


            if (!empty($addonsLang)) {

                $this->createFileLanguage(Yii::getPathOfAlias($fullPath), $file, array($locale, $addonsLang), $trans);
            }


            if (!@file_put_contents($dir, '<?php

/**
 * Message translations.
 * 
 * Each array element represents the translation (value) of a message (key).
 * If the value is empty, the message is considered as not translated.
 * Messages that no longer need translation will have their translations
 * enclosed between a pair of \'@@\' marks.
 * 
 * @author Andrew (Panix) Semenov <andrew.panix@gmail.com>
 * @package modules.messages.' . $locale . '
 */
return ' . var_export($trans, true) . ';')) {
                throw new CException(Yii::t('admin', 'Error write modules setting in {file}...', array('{file}' => $dir)));
            }
        }
        $return = include($dir);
        $this->render('_ajaxOpen', array('return' => $return, 'module' => $mod, 'locale' => $locale, 'file' => $file, 'type' => $type));
    }

    public function getArray($path) {
        $files = scandir($path);
        $tree = array();
        foreach ($files as $file) {
            if ($file != "." && $file != "..")
                $tree[$file] = $file;
        }
        return $tree;
    }

    public function createFileLanguage($fullpath, $filename, $langs = array(), $content = array()) {
        $newLangPath = $fullpath . DS . $langs[1];
        if (!is_dir($newLangPath)) {
            mkdir($newLangPath, 0750);
        }
        $t = new yandexTranslate;
        $fh = fopen($newLangPath . DS . $filename, "w");
        if (!is_resource($fh)) {
            return false;
        }
        fclose($fh);
        $params = array();
        $result = array();
        $spec = array();
        $num = -1;
        foreach ($content as $key => $val) {
            $params[] = $val;
            $num++;
            $spec[$num] = $key;
        }
        $response = $t->translate($langs, $params);
        foreach ($response['text'] as $k => $v) {
            $result[$spec[$k]] = $v;
        }
        if (!@file_put_contents($newLangPath . DS . $filename, '<?php

/**
 * Message translations. (auto translate)
 * 
 * Each array element represents the translation (value) of a message (key).
 * If the value is empty, the message is considered as not translated.
 * Messages that no longer need translation will have their translations
 * enclosed between a pair of \'@@\' marks.
 * 
 * @author Andrew (Panix) Semenov <andrew.panix@gmail.com>
 * @package modules.messages.' . $langs[1] . '
 */
return ' . var_export($result, true) . ';')) {
            throw new CException(Yii::t('admin', 'Error write modules setting in {file}...', array('{file}' => $filename)));
        }
        // return true;
    }

}