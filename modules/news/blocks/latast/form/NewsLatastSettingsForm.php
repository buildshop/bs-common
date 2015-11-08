<?php

/**
 * Модель настроек блока новости
 */
class NewsLatastSettingsForm extends BModel {

    public $num;
    public $truncate_title;
    public $truncate_text;

    /**
     * Настройка прав полей
     * @return array
     */
    public function rules() {
        return array(
            array('num, truncate_title, truncate_text', 'type')
        );
    }

    /**
     * Массив выводимых полей
     * @return array
     */
    public function getFormConfigArray() {
        return array(
            'type' => 'form',
            'elements' => array(
                'num' => array(
                    'label' => Yii::t('NewsLatastBlock.default', 'NUM'),
                    'type' => 'text',
                ),
                'truncate_title' => array(
                    'label' => Yii::t('NewsLatastBlock.default', 'TRUNCATE_TITLE'),
                    'type' => 'text',
                ),
                'truncate_text' => array(
                    'label' => Yii::t('NewsLatastBlock.default', 'TRUNCATE_TEXT'),
                    'type' => 'text',
                ),
            )
        );
    }

}
