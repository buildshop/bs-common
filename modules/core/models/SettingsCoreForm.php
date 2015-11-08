<?php

/**
 * @author Andrew (Panix) Semenov <andrew.panix@gmail.com>
 * @package Settings
 * @copyright (c) 2014, Andrew S.
 * @version 1.0
 * @link http://cms.corner.com.ua CORNER CMS
 * 
 * @property string $site_name Site name
 * @property string $theme Theme name
 * @property string $etheme Event theme name
 * @property datetime $etheme_start Start date event theme
 * @property datetime $etheme_end End date event theme
 * @property string $btn_grid_size
 * @property boolean $site_close Close site
 * @property text $site_close_text Close site text
 * @property string $format_date Format datetime CMS::date()
 * @property int $cookie_time Cookie time in day
 */
class SettingsCoreForm extends FormModel {

    const MODULE_ID = 'core';
    public $translate_object_url;
    public $site_name;
    public $theme;
    public $etheme;
    public $etheme_start;
    public $etheme_end;
    public $btn_grid_size;
    public $site_close;
    public $site_close_text;
    public $format_date;
    public $cookie_time;
    public $cache_time;
    public $pagenum;
    public $multi_language;
    public $censor_array;
    public $censor;
    public $censor_replace;
    public $site_close_allowed_users;
    public $site_close_allowed_ip;
    public $auto_detect_language;
    public $session_time;
    public $admin_email;
    public $default_timezone;
    public $logo;

    public static function defaultSettings() {
        return array(
            'site_name' => 'Buildshop.NET',
            'session_time' => 86400,
            'logo' => 'logo.png',
            'theme' => 'default',
            'censor' => true,
            'censor_replace' => '***',
            'censor_array' => 'anti',
            'default_timezone' => 'Europe/Kiev',
            'format_date' => 'd MMM yyyy',
            'cache_time' => 864000,
            'cookie_time' => 864000,
            'pagenum' => '20',
            'multi_language' => '1',
            'site_close' => '0',
            'site_close_text' => '<p>site close text</p>',
            'site_close_allowed_users' => 'admin',
            'site_close_allowed_ip' => '',
            'auto_detect_language' => '0',
            'translate_object_url' => '0',
            'etheme' => '',
            'etheme_end' => '',
            'etheme_start' => '',
            'btn_grid_size' => 'btn'
        );
    }

    function titleRow($title) {
        return '<div class="formRow textC"><h3>' . $title . '</h3></div>';
    }

    public function getForm() {
        $themesNames = Yii::app()->themeManager->themeNames;
        $themes = array_combine($themesNames, $themesNames);
        $df = Yii::app()->dateFormatter;
        Yii::app()->controller->widget('ext.tinymce.TinymceWidget');
        Yii::import('zii.widgets.jui.CJuiDatePicker');
        Yii::import('ext.BootstrapTagInput');
        return new TabForm(array(
                    'showErrorSummary' => true,
                    'attributes' => array(
                         'enctype' => 'multipart/form-data',
                        'class' => 'form-horizontal',
                        'id' => __CLASS__,
                    ),
                    'elements' => array(
                        'global' => array(
                            'type' => 'form',
                            'title' => $this->t('TAB_GENERAL'),
                            'elements' => array(
                                'logo' => array(
                                    'type' => 'file',
                                ),
                                '<div class="form-group">
				<div class="col-sm-4"></div>
				<div class="col-sm-8">' . $this->renderLogo() . '</div>
				</div>',
                                'translate_object_url' => array(
                                    'type' => 'checkbox',
                                    'hint' => $this->t('HINT_TRANSLATE_OBJ_URL'),
                                ),
                                'site_name' => array('type' => 'text', 'attributes' => array('class' => 'form-control')),
                                'admin_email' => array('type' => 'text', 'afterField' => '<span class="fieldIcon icon-envelope"></span>'),
                                'session_time' => array('type' => 'text', 'afterField' => '<span class="fieldIcon icon-alarm-2"></span>'),
                                'cookie_time' => array('type' => 'text', 'afterField' => '<span class="fieldIcon icon-alarm-2"></span>'),
                                'cache_time' => array('type' => 'text', 'afterField' => '<span class="fieldIcon icon-alarm-2"></span>'),
                                'pagenum' => array('type' => 'text'),
                                'multi_language' => array('type' => 'checkbox'),
                                'auto_detect_language' => array(
                                    'type' => 'checkbox',
                                    'hint' => $this->t('HINT_AUTO_DETECT_LANG', array(
                                        '{default_lang}' => Yii::app()->languageManager->default->code
                                    ))
                                ),
                                'theme' => array(
                                    'type' => 'dropdownlist',
                                    'items' => $themes,
                                ),
                                'etheme' => array(
                                    'type' => 'dropdownlist',
                                    'items' => $themes,
                                    'empty' => 'no'
                                ),
                                'etheme_start' => array(
                                    'type' => 'CJuiDatePicker',
                                    'options' => array(
                                        'dateFormat' => 'yy-mm-dd ' . date('H:i:s'),
                                    ),
                                    'htmlOptions' => array('class' => 'form-control datatime')
                                // 'htmlOptions' => array(
                                //     'value' => ($this->isNewRecord) ? date('Y-m-d H:i:s') : $this->date_create,
                                // )
                                ),
                                'etheme_end' => array(
                                    'type' => 'CJuiDatePicker',
                                    'options' => array(
                                        'dateFormat' => 'yy-mm-dd ' . date('H:i:s'),
                                    ),
                                    'htmlOptions' => array('class' => 'form-control datatime')
                                // 'htmlOptions' => array(
                                //     'value' => ($this->isNewRecord) ? date('Y-m-d H:i:s') : $this->date_create,
                                // )
                                ),
                                'btn_grid_size' => array(
                                    'type' => 'dropdownlist',
                                    'items' => array(
                                        'btn' => Yii::t('CoreModule.admin', 'normal'),
                                        'btn-xs' => Yii::t('CoreModule.admin', 'xs'),
                                        'btn-sm' => Yii::t('CoreModule.admin', 'sm'),
                                        'btn-lg' => Yii::t('CoreModule.admin', 'lg'),
                                    )
                                ),
                            )
                        ),
                        'close_site' => array(
                            'type' => 'form',
                            'title' => $this->t('TAB_CLOSESITE'),
                            'elements' => array(
                                'site_close' => array('type' => 'checkbox'),
                                'site_close_text' => array('type' => 'textarea', 'class' => 'editor'),
                                'site_close_allowed_users' => array(
                                    'type' => 'BootstrapTagInput',
                                    'htmlOptions' => array(
                                        'placeholder' => $this->t('ADD_USER')
                                    ),
                                    'hint' => Yii::t('hints', 'HINT_TAGS_PLUGIN')
                                ),
                                'site_close_allowed_ip' => array(
                                    'type' => 'text',
                                    'type' => 'BootstrapTagInput',
                                    'htmlOptions' => array(
                                        'placeholder' => $this->t('ADD_IP')
                                    ),
                                    'hint' => Yii::t('hints', 'HINT_TAGS_PLUGIN')
                                ),
                            )
                        ),
                        'censor' => array(
                            'type' => 'form',
                            'title' => $this->t('TAB_CENSOR'),
                            'elements' => array(
                                'censor' => array('type' => 'checkbox'),
                                'censor_array' => array(
                                    'type' => 'BootstrapTagInput',
                                    'htmlOptions' => array(
                                        'placeholder' => $this->t('ADD_WORD')
                                    ),
                                    'hint' => Yii::t('hints', 'HINT_TAGS_PLUGIN')
                                ),
                                'censor_replace' => array('type' => 'text'),
                            )
                        ),
                        'datetime' => array(
                            'type' => 'form',
                            'title' => $this->t('TAB_DATETIME'),
                            'elements' => array(
                                'format_date' => array(
                                    'type' => 'text',
                                    'afterField' => '<span class="fieldIcon icon-calendar-2 "></span>',
                                    'hint' => "<div>День (d или dd): " . $df->format('dd', date('Y-m-d H:i:s')) . "</div>
                    <div>Месяц (MM): " . $df->format('MM', date('Y-m-d H:i:s')) . "</div>
                    <div>Месяц (MMM): " . $df->format('MMM', date('Y-m-d H:i:s')) . "</div>
                    <div>Месяц (MMMM): " . $df->format('MMMM', date('Y-m-d H:i:s')) . "</div>
                    <div>Год (yy): " . $df->format('yy', date('Y-m-d H:i:s')) . "</div>
                    <div>Год (yyyy): " . $df->format('yyyy', date('Y-m-d H:i:s')) . "</div>"
                                ),
                                'default_timezone' => array(
                                    'type' => 'dropdownlist',
                                    'items' => TimeZoneHelper::getTimeZoneData()
                                ),
                            )
                        ),
                    ),
                    'buttons' => array(
                        'submit' => array(
                            'type' => 'submit',
                            'class' => 'btn btn-success',
                            'label' => Yii::t('app', 'SAVE')
                        )
                    )
                        ), $this);
    }

    public function init() {
        $param = Yii::app()->settings->get('core');
        $param['cache_time'] = $param['cache_time'] / 86400;
        $param['cookie_time'] = $param['cookie_time'] / 86400;
        $param['session_time'] = $param['session_time'] / 60;
        $this->attributes = $param;
        parent::init();
    }

    public function renderLogo() {
        if (file_exists(Yii::getPathOfAlias('webroot') . '/uploads/logo.png'))
            return Html::image('/uploads/logo.png?' . time());
    }

    /**
     * Validates uploaded watermark file
     */
    public function validateLogoFile($attr) {
        $file = CUploadedFile::getInstance($this, 'logo');
        if ($file) {
            $allowedExts = array('jpg', 'gif', 'png');
            if (!in_array($file->getExtensionName(), $allowedExts))
                $this->addError($attr, $this->t('ERRPR_LOGO_NO_IMAGE'));
        }
    }

    /**
     * @return array
     */
    public function rules() {
        return array(
            array('logo', 'validateLogoFile'),
            array('etheme_end, etheme_start', 'type', 'type' => 'datetime', 'datetimeFormat' => 'yyyy-MM-dd hh:mm:ss'),
            array('pagenum, admin_email, default_timezone, session_time, site_close_allowed_users, site_name, censor_replace, censor_array, theme, btn_grid_size, site_close_text, format_date, cache_time, cookie_time', 'required'),
            array('site_close_allowed_ip', 'numerical', 'integerOnly' => true),
            array('etheme, etheme_start, etheme_end, default_timezone', 'type', 'type' => 'string'),
            array('auto_detect_language, multi_language, censor, site_close, translate_object_url', 'boolean')
        );
    }

    /**
     * Saves attributes into database
     */
    public function save($message = true) {
        $this->cache_time = $_POST['SettingsCoreForm']['cache_time'] * 86400;
        $this->cookie_time = $_POST['SettingsCoreForm']['cookie_time'] * 86400;
        $this->session_time = $_POST['SettingsCoreForm']['session_time'] * 60;
        Yii::app()->settings->set('core', $this->attributes);
        $this->saveLogo();
        parent::save($message);
    }

    /**
     * @param $prefix
     * @return array
     */
    public function getDataByPrefix($prefix) {
        $prefix.='_';
        $result = array();

        foreach ($this->attributes as $key => $val) {
            if (substr($key, 0, strlen($prefix)) === $prefix) {
                $k = substr($key, strlen($prefix));
                $result[$k] = $val;
            }
        }

        return $result;
    }

    public function saveLogo() {
        $logo = CUploadedFile::getInstance($this, 'logo');
        if ($logo)
            $logo->saveAs(Yii::getPathOfAlias('webroot') . '/uploads/logo.png');
    }

}