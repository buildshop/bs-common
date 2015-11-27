<?php

/**
 * This is the model class for table "user".
 * The followings are the available columns in table 'user':
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @uses ActiveRecord
 * @package modules.users.models
 * @property integer $id
 * @property string $username Имя пользовотеля
 * @property string $login Логин
 * @property string $password sha1(Пароль)
 * @property string $email Почта
 * @property integer $date_registration Дата регистрации
 * @property integer $last_login
 * @property string $login_ip IP-адрес входа пользователя
 * @property string $recovery_key Password recovery key
 * @property string $recovery_password
 * @property boolean $banned
 */
class User extends ActiveRecord {

    const MODULE_ID = 'users';
    public $new_password;
    public $confirm_password;
    // public $verifyCode;
    public $duration;
    public $group_id = 3; //По умолчание группа пользоватедя 

    // public $phone;
    // public $address;

    public function getGridColumns() {
        return array(
            array(
                'name' => 'avatar',
                'type' => 'raw',
                'filter' => false,
                'value' => 'Html::image($data->avatarUrl("50x50"), $data->username, array("width"=>"50"))',
            ),
            array(
                'name' => 'login',
                'type' => 'raw',
                'value' => 'Html::link(Html::encode($data->login),array("update","id"=>$data->id))',
            ),
            array(
                'type' => 'raw',
                'name' => 'email',
                'value' => '$data->emailLink'
            ),
            /* array(
              'type' => 'raw',
              'header' => 'Роли',
              'value' => '$data->role()'
              ), */
            array(
                'type' => 'raw',
                'name' => 'gender',
                'filter' => self::getSelectGender(),
                'value' => 'CMS::gender("$data->gender")'
            ),
            array(
                'type' => 'raw',
                'name' => 'last_login',
                'value' => 'CMS::date("$data->last_login")'
            ),
            array(
                'type' => 'raw',
                'name' => 'login_ip',
                'value' => 'CMS::ip("$data->login_ip", 1)'
            ),
            'DEFAULT_CONTROL' => array(
                'class' => 'ButtonColumn',
                'template' => '{update}{delete}',
                'hidden' => array('delete' => array(1))
            ),
            'DEFAULT_COLUMNS' => array(
                array(
                    'class' => 'SGridIdColumn',
                    'type' => 'raw',
                    'value' => '$data->isUserOnline($data->id)'
                ),
            ),
        );
    }
    public function groupBy($group_id) {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'group_id=:id',
            'params' => array(':id' => $group_id)
        ));
        return $this;
    }
    // class User
    public function getFullName() {
        return $this->login;
    }

    public function getSuggest($q) {
        $c = new CDbCriteria();
        $c->addSearchCondition('login', $q, true, 'OR');
        $c->addSearchCondition('email', $q, true, 'OR');
        return $this->findAll($c);
    }

    public function init() {
        /**
         * проблема для установки.
         */
        /*  $this->_attrLabels['confirm_password'] = $this->t('CONFIRM_PASSWORD');

          $this->_attrLabels['address'] = $this->t('ADDRESS');
          $this->_attrLabels['phone'] = $this->t('PHONE'); */
        // $this->_attrLabels['confirm_password'] = $this->t('CONFIRM_PASSWORD');
        //$this->_attrLabels['verifyCode'] = $this->t('VERIFY_CODE');
        //$this->_attrLabels['new_password'] = $this->t('NEW_PASSWORD');
        return parent::init();
    }

    public function getForm() {
        Yii::import('zii.widgets.jui.CJuiDatePicker');
        return new CMSForm(array(
                    'attributes' => array(
                        'id' => __CLASS__,
                        'class' => 'form-horizontal',
                    ),
                    'enctype' => 'multipart/form-data',
                    'showErrorSummary' => false,
                    'elements' => array(
                        'login' => array(
                            'type' => 'text',
                            'disabled' => $this->isService,
                            'afterField' => '<span class="fieldIcon icon-user"></span>'
                        ),
                        'username' => array(
                            'type' => 'text',
                            'afterField' => '<span class="fieldIcon icon-user"></span>'
                        ),
                        'email' => array(
                            'type' => 'text',
                            'afterField' => '<span class="fieldIcon icon-envelope"></span>'
                        ),
                        'address' => array('type' => 'text'),
                        'role' => array('type' => 'text'),
                        'phone' => array(
                            'type' => 'text',
                            'afterField' => '<span class="fieldIcon icon-phone"></span>'
                        ),
                        'subscribe' => array('type' => 'checkbox'),
                        'last_login' => array(
                            'type' => 'CJuiDatePicker',
                            'options' => array(
                                'dateFormat' => 'yy-mm-dd ' . date('H:i:s'),
                            ),
                            'afterField' => '<span class="fieldIcon icon-calendar-2"></span>'
                        ),
                        'date_birthday' => array(
                            'type' => 'CJuiDatePicker',
                            'options' => array(
                                'dateFormat' => 'yy-mm-dd',
                            ),
                            'afterField' => '<span class="fieldIcon icon-calendar-2"></span>'
                        ),
                        'timezone' => array(
                            'type' => 'dropdownlist',
                            'items' => TimeZoneHelper::getTimeZoneData()
                        ),
                        'language' => array(
                            'type' => 'dropdownlist',
                            'items' => Yii::app()->languageManager->getLangsByArray(),
                            'empty' => 'По умолчанию',
                        ),
                        'gender' => array(
                            'type' => 'dropdownlist',
                            'items' => self::getSelectGender(),
                            'disabled' => $this->isService
                        ),
                        'group_id' => array(
                            'type' => 'dropdownlist',
                            'items' => Html::listData(UserGroup::model()->findAll(), 'id', 'name'),

                        ),
                        'avatar' => array('type' => 'file', 'disabled' => $this->isService),
                        // 'login_ip' => array('type' => 'text', 'disabled' => $this->isService),
                        'new_password' => array(
                            'type' => 'password',
                            'disabled' => $this->isService,
                            'afterField' => '<span class="fieldIcon icon-lock"></span>'
                        ),
                        'banned' => array('type' => 'checkbox'),
                    ),
                    'buttons' => array(
                        'submit' => array(
                            'type' => 'submit',
                            'class' => 'btn btn-success',
                            'label' => ($this->isNewRecord) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'SAVE')
                        ),
                    )
                        ), $this);
    }

    public static function getRoles($user_id) {
        foreach (Rights::getAssignedRoles($user_id) as $role) {
            echo $role->name . ', ';
        }
    }

    public function role() {
        foreach (Rights::getAssignedRoles($this->id) as $role) {
            echo $role->description . ', ';
        }
    }

    public function getIsService() {
        return (isset($this->service) && !empty($this->service)) ? true : false;
    }

    public function getEmailLink() {

        Yii::app()->clientScript->registerScript('sendEmail', '
       function sendEmailer(mail){

          if($("#sendEmailer").length == 0)
    {
        var div =  $("<div id=\"sendEmailer\"/ class=\"fluid\">");
        $(div).attr("title", "Оптавить письмо:");
        $("body").append(div);
    }

    var dialog = $("#sendEmailer");
    dialog.html("Загрузка формы...");
    dialog.load("/admin/core/ajax/sendMailForm?mail="+mail+"");

    dialog.dialog({
        modal: true,
        width: "50%",
        buttons: {
            "Отправить": function() {
                $.ajax("/admin/core/ajax/sendMailForm", {
                    type:"post",
                    data: {
                        token: $(link_clicked).attr("data-token"),
                        data: $("#sendEmailer form").serialize()
                    },
                    success: function(data){
                        $(dialog).dialog("close");
                        dialog.html("Письмо отправлено!");
                        
                    },
                    error: function(){
                        $.jGrowl("Ошибка", {
                            position:"bottom-right"
                        });
                    }
                });
            },
            "Отмена": function() {
                $( this ).dialog( "close" );
            }
        }
    });
}
        ', CClientScript::POS_HEAD);



        if (!empty($this->email)) {
            $em = CHtml::link($this->email, Yii::app()->createAbsoluteUrl('admin/delivery', array('send' => $this->email)), array('onClick' => 'sendEmailer("' . $this->email . '"); return false;'));
        } else {
            $em = $this->service;
        }
        return $em;
    }

    public function isUserOnline($user_id) {
        $session = Session::model()->find(array('condition' => '`t`.`user_id`=:uid', 'params' => array(':uid' => $user_id)));
        if (isset($session)) {
            if (Yii::app()->controller instanceof AdminController) {
                return '<span class="status_available" style="position:static" title="' . Yii::t('default', 'ONLINE') . '"></span>';
            } else {
                return true;
            }
        } else {
            if (Yii::app()->controller instanceof AdminController) {
                return '<span class="status_off" style="position:static" title="' . Yii::t('default', 'OFFLINE') . '"></span>';
            } else {
                return false;
            }
        }
    }

    public function scopes() {
        return array(
            'subscribe' => array(
                'condition' => '`t`.`subscribe`=:subs AND `t`.`active`=:act',
                'params' => array(':subs' => 1, ':act' => 1)
            )
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        $config = Yii::app()->settings->get('users');

        return array(
            array('login, password, confirm_password', 'required', 'on' => 'register'),
            array('login, group_id', 'required'),
            array('login', 'checkIfAvailable'),
            array('banned', 'boolean'),
            array('avatar', 'file',
                'types' => $config['users'],
                'allowEmpty' => true,
                'maxSize' => $config['upload_size'],
                'wrongType' => Yii::t('core', 'WRONG_TYPES', array('{TYPES}' => $config['upload_types']))
            ),
            array('email', 'email'),
            array('date_registration', 'required', 'on' => 'update'),
            array('date_registration, last_login', 'date', 'format' => array('yyyy-M-d H:m:s', '0000-00-00 00:00:00')),
            array('date_birthday', 'date', 'format' => array('yyyy-M-d', '0000-00-00')),
            array('username, password, email, avatar, login_ip, service, phone, address, timezone', 'length', 'max' => 255),
            array('new_password', 'length', 'min' => $config['min_password']),
            array('password', 'length', 'min' => $config['min_password']),
            array('gender, language, subscribe', 'numerical', 'integerOnly' => true),
            array('id, username, email, date_registration, last_login, banned, avatar, language, address, phone', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Check if username/email is available
     */
    public function checkIfAvailable($attr) {
        $labels = $this->attributeLabels();
        $check = User::model()->countByAttributes(array(
            $attr => $this->$attr,
                ), 't.id != :id', array(':id' => (int) $this->id));

        if ($check > 0)
            $this->addError($attr, Yii::t('UsersModule.default', 'ERROR_ALREADY_USED', array('{attr}' => $labels[$attr])));
    }

    public function checkBadName($attr) {
        $labels = $this->attributeLabels();
        $names_array = explode(',', Yii::app()->settings->get('users', 'bad_name'));
        if (in_array($this->username, $names_array))
            $this->addError($attr, Yii::t('UsersModule.default', 'ERROR_BAD_NAMES', array('{attr}' => $labels[$attr], '{name}' => $this->username)));
    }

    public function checkBadEmail($attr) {
        $config = Yii::app()->settings->get('users', 'bad_email');
        if (!empty($config)) {
            $mails = explode(',', $config);

            foreach ($mails as $mail) {
                if (preg_match('#' . $mail . '$#iu', $this->email))
                    $this->addError($attr, Yii::t('UsersModule.default', 'ERROR_BAD_EMAILS', array('{email}' => $mail)));
            }
        }
    }



    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'group' => array(self::BELONGS_TO, 'UserGroup', 'group_id'),
            //'orders' => array(self::HAS_MANY, 'Order', 'user_id'),
            //'ordersCount' => array(self::STAT, 'Order', 'user_id'),
            'comments' => array(self::HAS_MANY, 'Comment', 'user_id'),
            'commentsCount' => array(self::STAT, 'Comment', 'user_id'),
        );
    }

    public function getAvatarPath() {
        if (Yii::app()->user->isGuest) {
            $avatar = '/uploads/users/avatars/guest.png';
        } else {
            if ($this->avatar == null) {
                $avatar = '/uploads/users/avatars/user.png';
            } else {
                $avatar = $this->avatar;
            }
        }
        return $avatar;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('date_registration', $this->date_registration, true);
        $criteria->compare('avatar', $this->avatar, true);
        $criteria->compare('last_login', $this->last_login);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('banned', $this->banned);

        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     *  Encodes user password
     *
     * @param string $string
     * @return string
     */
    public static function encodePassword($string) {
        return sha1($string);
    }

    /**
     * @return bool
     */
    public function beforeSave() {
        // Set new password

        if ($this->isNewRecord) {

            if (!$this->date_registration)
                $this->date_registration = date('Y-m-d H:i:s');
            $this->login_ip = Yii::app()->request->userHostAddress;

            if (!$this->hasErrors())
                $this->password = self::encodePassword($this->password);
        }
        if ($this->new_password) {
            $this->password = self::encodePassword($this->new_password);
        }
        // $this->uploadFile('avatar', '/uploads/users/avatar/');
        return parent::beforeSave();
    }

    /**
     * Generate admin link to edit user.
     * @return string
     */
    public function getUpdateLink() {
        return Html::link(Html::encode($this->username), array('/users/admin/default/update', 'id' => $this->id));
    }

    /**
     * Activate new user password
     * @static
     * @param $key
     * @return bool
     */
    public static function activeNewPassword($key) {
        $user = User::model()->findByAttributes(array('recovery_key' => $key));

        if (!$user)
            return false;

        $user->password = self::encodePassword($user->recovery_password);
        $user->recovery_key = '';
        $user->recovery_password = '';
        $user->save(false);
        return true;
    }

    /**
     * @return int
     */
    public function getOrdersTotalPrice() {
        $result = 0;

        foreach ($this->orders as $order)
            $result += $order->full_price;

        return $result;
    }

    //Пол
    public static function getSelectGender() {
        return array(
            0 => Yii::t('core', 'GENDER', 0),
            1 => Yii::t('core', 'GENDER', 1),
            2 => Yii::t('core', 'GENDER', 2)
        );
    }

    public static function getUserPanel($username, $id) {
        $txt = '<ul class="navi nav-pills">';
        $txt .= '<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown">' . $username . '<b class="caret"></b></a>';
        $txt .= '<ul class="dropdown-menu">';
        $txt .= '<li><a href="/admin/users/default/update?id=' . $id . '"><span class="iconb icon-wrench"></span>' . Yii::t('core', 'UPDATE', 1) . '</a></li>';
        $txt .= '<li><a href="/admin/users/default/update?id=' . $id . '"><span class="iconb icon-wrench"></span>' . Yii::t('core', 'UPDATE', 1) . '</a></li>';
        $txt .= '</ul>';
        $txt .= '</li>';
        $txt .= '</ul>';

        return $txt;
    }

    public function avatarUrl($size = false) {
        if ($size === false) {
            $size = Yii::app()->settings->get('users', 'avatar_size');
        }
        $ava = $this->avatar;
        if (!preg_match('/(http|https):\/\/(.*?)$/i', $ava)) {
            $r = true;
        } else {
            $r = false;
        }
        // if (!is_null($this->service)) {
        //     return $this->avatar;
        // }
        if ($size !== false && $r !== false) {
            $thumbPath = Yii::getPathOfAlias('webroot.assets.user_avatar') . DS . $size;
            if (!file_exists($thumbPath)) {
                mkdir($thumbPath, 0777, true);
            }
            // Path to source image
            $fullPath = Yii::getPathOfAlias('webroot.uploads.users.avatar') . DS . $ava;

            // Path to thumb
            $thumbPath = $thumbPath . DS . $ava;
            if (!file_exists($thumbPath)) {
                // Resize if needed
                Yii::import('ext.phpthumb.PhpThumbFactory');
                $sizes = explode('x', $size);
                $thumb = PhpThumbFactory::create($fullPath);
                $thumb->resize($sizes[0], $sizes[1])->save($thumbPath);
            }
            return (empty($ava)) ? '/uploads/users/avatars/user.png' : '/assets/user_avatar/' . $size . '/' . $ava;
        } else {
            return $ava;
        }
    }

}
