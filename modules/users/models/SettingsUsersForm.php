<?php

class SettingsUsersForm extends FormModel {

    const MODULE_ID = 'users';
    public $upload_avatar;
    public $upload_types;
    public $upload_size;
    public $avatar_size;
    public $register_nomail;
    public $min_password;
    public $bad_name;
    public $bad_email;
    public $social_auth;

    /**
     * Social settings
     */
    public $vkontakte;
    public $facebook;
    public $odnoklassniki;
    public $twitter;
    public $github;
    public $dropbox;
    public $linkedin;
    public $google_oauth;
    public $mailru;
    public $yandex_oauth;
    public $vkontakte_client_id;
    public $facebook_client_id;
    public $odnoklassniki_client_id;
    public $twitter_client_id;
    public $github_client_id;
    public $dropbox_client_id;
    public $linkedin_client_id;
    public $google_oauth_client_id;
    public $mailru_client_id;
    public $yandex_oauth_client_id;
    public $vkontakte_client_secret;
    public $facebook_client_secret;
    public $odnoklassniki_client_secret;
    public $twitter_client_secret;
    public $github_client_secret;
    public $dropbox_client_secret;
    public $linkedin_client_secret;
    public $google_oauth_client_secret;
    public $mailru_client_secret;
    public $yandex_oauth_client_secret;

    public static function defaultSettings() {
        return array(
            'min_password' => 4,
            'register_nomail'=>false,
            'upload_size'=>1048576,
            'upload_types'=>'jpg,gif',
            'bad_email' => 'badmail.ru',
            'upload_avatar'=>true,
            'avatar_size'=>'100x100',
            'bad_name' => 'root,admin,administrator,moderator,guest,anonymous',
        );
    }
    function row($title) {
        return '<div class="formRow textC"><h3>' . $title . '</h3></div>';
    }

    public function getForm() {
        Yii::import('ext.TagInput');
        $tab = new TabForm(array(
                    'showErrorSummary' => false,
                    'attributes' => array(
                        'id' => __CLASS__,
                        'class' => 'form-horizontal',
                    ),
                    'elements' => array(
                        'main' => array(
                            'type' => 'form',
                            'title' => $this->t('TAB_GENERAL'),
                            'elements' => array(

                                'upload_avatar' => array('type' => 'checkbox'),
                                'upload_types' => array(
                                    'type' => 'TagInput',
                                    'htmlOptions' => array(
                                        'placeholder' => 'Добавить формат'
                                    )
                                ),
                                'upload_size' => array(
                                    'type' => 'text',
                                    'hint' => '1Мб = 1048576 байт.'
                                ),
                                'avatar_size' => array('type' => 'text'),
                                'register_nomail' => array('type' => 'checkbox'),
                                'min_password' => array('type' => 'text'),
                                'bad_name' => array(
                                    'type' => 'TagInput',
                                    'htmlOptions' => array(
                                        'placeholder' => 'Добавить Имя'
                                    )
                                ),
                                'bad_email' => array('type' => 'TagInput',
                                    'htmlOptions' => array(
                                        'placeholder' => 'Добавить сервис'
                                    )
                                ),
                            )
                        ),
                        'social' => array(
                            'visible' => (Yii::app()->hasComponent('eauth')) ? true : false,
                            'type' => 'form',
                            'title' => $this->t('TAB_SOCIAL'),
                            'elements' => $this->getSocialArrayForm()
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
        return $tab;
    }

    private function getSocialApp($app) {
        return $this->t('Registration application: {app_link}', array('{app_link}' => Html::link($app::APP_URL, $app::APP_URL, array('target' => '_blank'))));
    }

    private function getSocialArrayRules() {
        $r = array();
        foreach ($this->getSocialArray() as $key => $val) {
            $name = $this->getServiceName($key);
            $r[] = array($name, 'boolean');
            $r[] = array($name . '_client_id', 'length', 'max' => 255);
            $r[] = array($name . '_client_secret', 'length', 'max' => 255);
        }
        return $r;
    }

    private function getSocialArrayForm() {
        $r = array();
        $r['social_auth'] = array('type' => 'checkbox');
        foreach ($this->getSocialArray() as $key => $val) {
            $name = $this->getServiceName($key);
            $r[$name] = array('type' => 'checkbox', 'hint' => $this->getSocialApp($key));
            $r[$name . '_client_id'] = array('type' => 'text');
            $r[$name . '_client_secret'] = array('type' => 'text');
        }
        return $r;
    }

    /**
     * 
     * for view settings
     */
    public function getJsonSocialClasses() {
        $r = array();
        foreach ($this->getSocialArray() as $key => $val) {
            $name = $this->getServiceName($key);
            $r['#' . get_class($this) . '_' . $name] = array(
                '.field_' . $name . '_client_secret',
                '.field_' . $name . '_client_id'
            );
        }
        return CJSON::encode($r);
    }

    private function getSocialArray() {
        if (Yii::app()->hasComponent('eauth')) {
            return array(
                'DropboxOAuthService' => 'Dropbox',
                'LinkedinOAuthService' => 'Linkedin',
                'GoogleOAuthService' => 'Google',
                'MailruOAuthService' => 'Mail.ru',
                'YandexOAuthService' => 'Yandex',
                'GitHubOAuthService' => 'GitHub',
                'TwitterOAuthService' => 'Twitter',
                'OdnoklassnikiOAuthService' => 'Одноклассники',
                'FacebookOAuthService' => 'Facebook',
                'VKontakteOAuthService' => 'В контакте',
            );
        } else {
            return array();
        }
    }

    public function init() {
        $this->attributes = Yii::app()->settings->get('users');
        // $test = new GoogleOAuthService();
        // echo $test->name;
    }

    private function getServiceName($name) {
        $service = new $name;
        return $service->name;
    }

    public function rules() {
        $rules = array(
            array('upload_types ,upload_size, avatar_size, min_password', 'required'),
            array('bad_name, bad_email', 'length', 'max' => 255),
            // array('db_client_id, db_client_secret, lin_client_id, lin_client_secret, go_client_id, go_client_secret, mailru_client_id, mailru_client_secret, ya_client_id, ya_client_secret, gh_client_id, gh_client_secret, fb_client_id, fb_client_secret, vk_client_id, vk_client_secret, ok_client_id, ok_client_secret, tw_client_id, tw_client_secret', 'length', 'max' => 255),
            // array('facebook, vkontakte, odnoklassniki, twitter, github, yandex, mailru, google_oauth, linkedin, dropbox', 'boolean'),
            array('min_password', 'numerical', 'integerOnly' => true),
            array('register_nomail, upload_avatar', 'boolean'),
            array('social_auth', 'boolean')
                // $this->getSocialArrayRules()
        );
        return CMap::mergeArray($rules, $this->getSocialArrayRules());
    }

    public function save($message = true) {
        Yii::app()->settings->set('users', $this->attributes);
        parent::save($message);
    }

}
