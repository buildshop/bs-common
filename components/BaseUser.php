<?php

/**
 * @package components
 */
class BaseUser extends WebUser {

    private $_identity;

    public function login($identity, $duration = 0) {
        $this->_identity = $identity;
        return parent::login($identity, $duration);
    }

    public function getIsSuperuser() {
        $this->_loadModel();
        $group = $this->_model->group;
        if ($group->alias == 'admin') {
            return true;
        } else {
            return false;
        }
    }

    public function getPlan() {
        $pack = Yii::app()->package->value;
        if ($pack->shop[0]['plan'] == 'pro') {
            return $this->getPlanPro();
        } elseif ($pack->shop[0]['plan'] == 'lite') {
            return $this->getPlanLite();
        } elseif ($pack->shop[0]['plan'] == 'standart') {
            return $this->getPlanStandart();
        } else {
            throw new CHttpException(500, 'Error plan USER');
        }
    }

    /**
     * Array value '*' = unlimit
     * @return array
     */
    public function getPlanPro() {
        return array(
            'productLimit' => '*', // Unlimit * 
            'modules' => CMap::mergeArray(array(
                'sms' => array('class' => 'mod.sms.SmsModule'),
                'compare' => array('class' => 'mod.compare.CompareModule'),
                'wishlist' => array('class' => 'mod.wishlist.WishlistModule'),
                'discounts' => array('class' => 'mod.discounts.DiscountsModule'),
                'csv' => array('class' => 'mod.csv.CsvModule'),
                'exchange1c' => array('class' => 'mod.exchange1c.Exchange1cModule'),
                'sitemap' => array('class' => 'mod.sitemap.SitemapModule'),
                'comments' => array('class' => 'mod.comments.CommentsModule'),
                'xml' => array('class' => 'mod.xml.XmlModule'),
                    ), $this->defaultPlanModules())
        );
    }

    private function getPlanStandart() {
        return array(
            'productLimit' => 10000,
            'modules' => CMap::mergeArray(array(
                'sms' => array('class' => 'mod.sms.SmsModule'),
                'compare' => array('class' => 'mod.compare.CompareModule'),
                'wishlist' => array('class' => 'mod.wishlist.WishlistModule'),
                'discounts' => array('class' => 'mod.discounts.DiscountsModule'),
                'csv' => array('class' => 'mod.csv.CsvModule'),
                'exchange1c' => array('class' => 'mod.exchange1c.Exchange1cModule'),
                'sitemap' => array('class' => 'mod.sitemap.SitemapModule'),
                'comments' => array('class' => 'mod.comments.CommentsModule'),
                'xml' => array('class' => 'mod.xml.XmlModule'),
                    ), $this->defaultPlanModules())
        );
    }

    private function getPlanLite() {
        return array(
            'productLimit' => 1000,
            'modules' => CMap::mergeArray(array(
                'compare' => array('class' => 'mod.compare.CompareModule'),
                'wishlist' => array('class' => 'mod.wishlist.WishlistModule'),
                'discounts' => array('class' => 'mod.discounts.DiscountsModule'),
                'sitemap' => array('class' => 'mod.sitemap.SitemapModule'),
                'comments' => array('class' => 'mod.comments.CommentsModule'),
                    ), $this->defaultPlanModules())
        );
    }

    private function defaultPlanModules() {
        return array(
            'support' => array('class' => 'mod.support.SupportModule'),
            'admin' => array('class' => 'mod.admin.AdminModule'),
            'core' => array('class' => 'mod.core.CoreModule'),
            'main' => array('class' => 'mod.main.MainModule'),
            'cart' => array('class' => 'mod.cart.CartModule'),
            'pages' => array('class' => 'mod.pages.PagesModule'),
            'contacts' => array('class' => 'mod.contacts.ContactsModule'),
            'news' => array('class' => 'mod.news.NewsModule'),
            'shop' => array('class' => 'mod.shop.ShopModule'),
            'users' => array('class' => 'mod.users.UsersModule'),
        );
    }

    /*   public function afterLogin($fromCookie) {
      if ($this->_identity !== null) {
      //CIntegrationForums::instance()->log_in($this->_identity->username, $this->_identity->password);
      }
      parent::afterLogin($fromCookie);
      }

      public function afterLogout() {
      // CIntegrationForums::instance()->log_out();
      parent::afterLogout();
      } */

    /**
     * @var User model
     */
    private $_model;
    public $guestName = '_GUEST';
    protected $_avatarPath;

    /**
     * @return string user email
     */
    public function getEmail() {
        $this->_loadModel();
        return $this->_model->email;
    }

    public function getProfile() {
        $this->_loadModel();
        return $this->_model->profile;
    }

    /* public function getDb() {
      $this->_loadModel();
      return unserialize($this->_model->db);
      } */
    /*
      public function getBalance() {
      $this->_loadModel();
      return $this->_model->balance;
      } */

    public function getTheme() {
        $this->_loadModel();
        return $this->_model->theme;
    }

    public function getTimezone() {
        $this->_loadModel();
        return $this->_model->timezone;
    }

    public function getLogin() {
        $this->_loadModel();
        return $this->_model->login;
    }

    public function getPhone() {
        $this->_loadModel();
        return $this->_model->phone;
    }

    public function getAddress() {
        $this->_loadModel();
        return $this->_model->address;
    }

    public function getAccessMessage() {
        if (Yii::app()->user->message) {
            return true;
        } else {
            throw new CHttpException(401, MessageModule::t('ACCESS_DENIED_USER'));
        }
    }

    /**
     * @return string username
     */
    public function getUsername() {
        $this->_loadModel();
        return $this->_model->username;
    }

    public function getService() {
        $this->_loadModel();
        return $this->_model->service;
    }

    /**
     * Load user model
     */
    private function _loadModel() {
        if (!$this->_model)
            $this->_model = User::model()
                    ->with('group')
                    ->findByPk($this->id);
    }

    public function getModel() {
        $this->_loadModel();
        return $this->_model;
    }

    public function getAvatarPath() {
        $this->_loadModel();
        if (Yii::app()->user->isGuest) {
            $avatar = '/uploads/users/avatars/guest.png';
        } else {
            if ($this->_model->avatar == null) {
                $avatar = '/uploads/users/avatars/user.png';
            } else {
                $avatar = $this->_model->avatar;
            }
        }
        return $avatar;
    }

    public function avatarUrl($size = false) {
        $this->_loadModel();
        if ($size === false) {
            $size = Yii::app()->settings->get('users', 'avatar_size');
        }
        // if (!is_null($this->_model->service)) {
        //     return $this->_model->avatar;
        // }
        if ($size !== false) {
            $thumbPath = Yii::getPathOfAlias('webroot.assets.user_avatar') . DS . $size;
            if (!file_exists($thumbPath)) {
                mkdir($thumbPath, 0777, true);
            }
            // Path to source image
            $fullPath = Yii::getPathOfAlias('webroot.uploads.users.avatar') . DS . $this->_model->avatar;

            // Path to thumb
            $thumbPath = $thumbPath . DS . $this->_model->avatar;
            if (!file_exists($thumbPath)) {
                // Resize if needed
                Yii::import('ext.phpthumb.PhpThumbFactory');
                $sizes = explode('x', $size);
                $thumb = PhpThumbFactory::create($fullPath);
                $thumb->resize($sizes[0], $sizes[1])->save($thumbPath);
            }
            if (Yii::app()->user->isGuest) {
                return '/uploads/users/avatars/guest.png';
            } else {
                if ($this->_model->avatar == 'user.png') {
                    return '/uploads/users/avatars/user.png';
                } else {
                    return '/assets/user_avatar/' . $size . '/' . $this->_model->avatar;
                }
            }
        }
    }

}
