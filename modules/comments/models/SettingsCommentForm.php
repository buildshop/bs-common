<?php

class SettingsCommentForm extends FormModel {

    const MODULE_ID = 'comments';
    public $pagenum;
    public $allow_add;
    public $allow_view;
    public $flood_time;
    public $reply;
    public $control_timeout;

    public static function defaultSettings() {
        return array(
            'pagenum' => 5,
            'flood_time' => 10,
            'control_timeout'=>5 * 60,
            'allow_add' => 0,
            'allow_view' => 0,
        );
    }

    public function getForm() {
        return new TabForm(array(
                    'attributes' => array(
                        'enctype' => 'multipart/form-data',
                        'class' => 'form-horizontal',
                        'id' => __CLASS__
                    ),
                    'showErrorSummary' => false,
                    'elements' => array(
                        'general' => array(
                            'type' => 'form',
                            'title' => Yii::t('core', 'Общие'),
                            'elements' => array(
                                'pagenum' => array('type' => 'text'),
                                'flood_time' => array('type' => 'text'),
                                'control_timeout' => array('type' => 'text'),
                                'reply' => array('type' => 'checkbox'),
                                'allow_add' => array(
                                    'type' => 'dropdownlist',
                                    'items' => Yii::app()->access->dataList()
                                ),
                                'allow_view' => array(
                                    'type' => 'dropdownlist',
                                    'items' => Yii::app()->access->dataList()
                                )
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
        $param = Yii::app()->settings->get('comments');
        $param['control_timeout'] = $param['control_timeout'] / 60;
        $this->attributes = $param;
        parent::init();
    }

    public function rules() {
        return array(
            array('pagenum, flood_time, allow_add, allow_view, control_timeout', 'required'),
            //array('bad_name, bad_email', 'length', 'max' => 255),
            array('reply', 'boolean'),
            array('pagenum', 'numerical', 'integerOnly' => true),
        );
    }

    public function save($message = true) {
        $this->control_timeout = $_POST['SettingsCommentForm']['control_timeout'] * 60;
        Yii::app()->settings->set('comments', $this->attributes);
        parent::save($message = true);
    }

}
