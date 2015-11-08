<?php

/**
 * Cart Widget
 * Display is module shop installed
 * @uses Widget 
 */
class SysinfoWidget extends BlockWidget {

    public function getTitle() {
        return 'Системная информация';
    }

    public function run() {
        $globals = (ini_get('register_globals') == 1) ? "<font color=\"red\">" . Yii::t('core', 'ON', 0) . "</font>" : "<font color=\"green\">" . Yii::t('core', 'OFF', 0) . "</font>";
        $safe_mode = (ini_get('safe_mode') == 1) ? "<font color=\"green\">" . Yii::t('core', 'ON', 0) . "</font>" : "<font color=\"red\">" . Yii::t('core', 'OFF', 0) . "</font>";
        $magic_quotes = (ini_get('magic_quotes_gpc') == 1) ? "<font color=\"red\">" . Yii::t('core', 'ON', 0) . "</font>" : "<font color=\"green\">" . Yii::t('core', 'OFF', 0) . "</font>";

        $p_max = CMS::files_size(str_replace("M", "", ini_get('post_max_size')) * 1024 * 1024);
        $u_max = CMS::files_size(str_replace("M", "", ini_get('upload_max_filesize')) * 1024 * 1024);
        $m_max = CMS::files_size(str_replace("M", "", ini_get('memory_limit')) * 1024 * 1024);
        $phpver = phpversion();
        $gd = (extension_loaded('gd')) ? "<font color=\"green\">" . Yii::t('core', 'ON', 0) . "</font>" : "<font color=\"red\">" . Yii::t('core', 'OFF', 0) . "</font>";
        $pdo = (extension_loaded('pdo')) ? "<font color=\"green\">" . Yii::t('core', 'ON', 0) . "</font>" : "<font color=\"red\">" . Yii::t('core', 'OFF', 0) . "</font>";
        $php = ($phpver >= "5.3") ? "<font color=\"green\">$phpver</font> (" . @php_sapi_name() . ")" : "<font color=\"red\">$phpver</font> (" . @php_sapi_name() . ")";


        $this->render($this->skin, array(
            'globals' => $globals,
            'safe_mode' => $safe_mode,
            'magic_quotes' => $magic_quotes,
            'p_max' => $p_max,
            'u_max' => $u_max,
            'm_max' => $m_max,
            'phpver' => $phpver,
            'gd' => $gd,
            'php' => $php,
            'pdo' => $pdo
        ));
    }

}
