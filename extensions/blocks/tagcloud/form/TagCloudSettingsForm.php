<?php

class TagCloudSettingsForm extends BModel {

    public $maxTags;
    public $title;

    public function rules() {
        return array(
            array('maxTags, title', 'type')
        );
    }

    public function getFormConfigArray() {
        return array(
            'type' => 'form',
            'elements' => array(
                'maxTags' => array(
                    'label' => 'Максимальный размер шрифта у тега',
                    'type' => 'text',
                ),
                'title' => array(
                    'label' => 'Заголовок',
                    'type' => 'text',
                ),
            )
        );
    }

}
