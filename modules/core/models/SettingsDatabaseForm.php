<?php

class SettingsDatabaseForm extends CFormModel {

    const MODULE_ID = 'core';
    public $backup = false;

    public function getForm() {
        return new CMSForm(array('id' => __CLASS__,
                    'showErrorSummary' => false,
                    'attributes' => array(
                        'class' => 'form-horizontal'
                    ),
                    'elements' => array(
                        'backup' => array('type' => 'checkbox'),
                    ),
                    'buttons' => array(
                        'submit' => array(
                            'type' => 'submit',
                            'class' => 'btn btn-success',
                            'label' => Yii::t('core', 'Создать')
                        )
                    )
                        ), $this);
    }

    public function init() {
        parent::init();
    }

    public function rules() {
        return array(
            array('backup', 'boolean'),
        );
    }

    public function attributeLabels() {
        return array(
            'backup' => 'Создать резервную копию',
        );
    }

}
