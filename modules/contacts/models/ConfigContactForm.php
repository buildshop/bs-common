<?php

class ConfigContactForm extends FormModel {

    const MODULE_ID = 'contacts';
    public $sendMail;
    public $address;
    public $tempMessage;
    public $phone;
    public $skype;
    public $form_emails;

    public $seo_title;
    public $seo_keywords;
    public $seo_description;
    public static function defaultSettings() {
        return array(
            'form_emails' => 'andrew.panix@gmail.com'
        );
    }
    public function getForm() {
        Yii::app()->controller->widget('ext.tinymce.TinymceWidget');
        return new TabForm(array(
                                'attributes' => array(
                        'class' => 'form-horizontal',
                        'id' => __CLASS__,
                    ),
                    'showErrorSummary' => true,
                    'elements' => array(
                        'seo' => array(
                            'type' => 'form',
                            'title' => Yii::t('ContactsModule.core', 'Мета данные'),
                            'elements' => array(
                                'seo_title' => array(
                                    'type' => 'text',
                                ),
                                'seo_keywords' => array(
                                    'type' => 'textarea',
                                ),
                                'seo_description' => array(
                                    'type' => 'textarea',
                                ),
                            ),
                        ),
                        'form_feedback' => array(
                            'type' => 'form',
                            'title' => Yii::t('contactsModule.core', 'Форма обратной связи'),
                            'elements' => array(
                                'form_emails' => array('type' => 'text'),
                                'tempMessage' => array('type' => 'textarea', 'class' => 'editor'),
                            ),
                        ),
                        'map' => array(
                            'type' => 'form',
                            'title' => Yii::t('contactsModule.core', 'Карта'),
                            'elements' => array(
                                'switch' => array(
                                    'type' => 'dropdownlist',
                                    'items' => array('0' => 'no', '1' => 'yes')
                                ),
                                'yandex_map_zoomControl' => array('type' => 'checkbox'),
                                'yandex_map_zoom' => array('type' => 'dropdownlist', 'items' => range(1, 17)),
                                'yandex_map_zoomControl_top' => array('type' => 'text'),
                                'yandex_map_zoomControl_left' => array('type' => 'text'),
                                'yandex_map_center' => array('type' => 'text'),
                                'yandex_map_balloon_content' => array('type' => 'text'),
                                'yandex_map_height' => array('type' => 'text'),
                                'yandex_map_width' => array('type' => 'text'),
                                'yandex_map_typeSelector' => array('type' => 'checkbox'),
                                'yandex_map_typeSelector_top' => array('type' => 'text'),
                                'yandex_map_typeSelector_right' => array('type' => 'text'),
                                
                                'yandex_map_mapTools' => array('type' => 'checkbox'),
                                'yandex_map_mapTools_top' => array('type' => 'text'),
                                'yandex_map_mapTools_left' => array('type' => 'text'),
                            ),
                        ),
                    ),
                    'buttons' => array(
                        'submit' => array(
                            'type' => 'submit',
                            'label' => Yii::t('app', 'SAVE'),
                            'class' => 'btn btn-success',
                        )
                    )
                        ), $this);
    }

    public function init() {
        $this->attributes = Yii::app()->settings->get('contacts');
    }

    public function rules() {
        return array(
            array('form_emails, yandex_map_zoom', 'required'),
            array('yandex_map_zoomControl_top, yandex_map_zoomControl_left, yandex_map_zoomControl', 'numerical', 'integerOnly' => true),
            array('yandex_map_typeSelector_top, yandex_map_typeSelector_right, yandex_map_typeSelector', 'numerical', 'integerOnly' => true),
            array('yandex_map_mapTools_top, yandex_map_mapTools_left, yandex_map_mapTools', 'numerical', 'integerOnly' => true),
            array('yandex_map_zoom, yandex_map_height, yandex_map_width', 'numerical', 'integerOnly' => true),
            array('seo_title, seo_keywords, seo_description, tempMessage, address, phone, skype, yandex_map_center, yandex_map_balloon_content', 'type', 'type' => 'string'),
            array('sendMail', 'match', 'pattern' => '/^[\da-z][-_\d\.a-z]*@(?:[\da-z][-_\da-z]*\.)+[a-z]{2,5}$/iu'),
        );
    }

    /**
     * Save settings
     */
    public function save($message=true) {
        Yii::app()->settings->set('contacts', $this->attributes);
        parent::save($message);
    }

}

?>