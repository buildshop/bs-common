<?php

class SettingsNewsForm extends FormModel {

    protected $_mid = 'news';
    public $pagenum;

    public static function defaultSettings() {
        return array(
            'pagenum' => 30
        );
    }

    public function getForm() {
        return new CMSForm(array('id' => __CLASS__,
                    'showErrorSummary' => true,
                    'elements' => array(
                        'pagenum' => array(
                            'type' => 'text',
                        ),
                    ),
                    'buttons' => array(
                        'submit' => array(
                            'type' => 'submit',
                            'class' => 'buttonS bGreen',
                            'label' => Yii::t('core', 'SAVE')
                        )
                    )
                        ), $this);
    }

    public function init() {
        $this->attributes = Yii::app()->settings->get('news');
    }

    public function rules() {
        return array(
            array('pagenum', 'numerical', 'integerOnly' => true),
        );
    }

    public function save($message = true) {
        Yii::app()->settings->set('news', $this->attributes);
        parent::save($message);
    }

}
