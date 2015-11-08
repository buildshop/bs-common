<?php

/**
 * Контроллер профиля пользователей.
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.users.controllers
 * @uses Controller
 */
class ProfileController extends Controller {

    public function actions() {
        return array(
          //  'widget.' => 'mod.users.widgets.webcam.Webcam',
            'getAvatars' => array(
                'class' => 'mod.users.actions.AvatarAction',
            ),
            'saveAvatar' => array(
                'class' => 'mod.users.actions.SaveAvatarAction',
            ),
        );
    }

    public function filters() {
        return array(
            'ajaxOnly + addFriend',
        );
    }

    /**
     * Check if user is authenticated
     * @return bool
     * @throws CHttpException

      public function beforeAction($action) {
      if (Yii::app()->user->isGuest)
      throw new CHttpException(404, Yii::t('UsersModule.core', 'Ошибка доступа.'));
      return true;
      } */
    /**
     * Display user orders
     */

    /**
     * Display profile start page
     */
    public function actionIndex() {
        if (!Yii::app()->user->isGuest) {
            $this->pageName = Yii::t('UsersModule.default', 'PROFILE');
            $this->pageTitle = $this->pageName;
            $this->breadcrumbs = array($this->pageName);

            Yii::import('mod.users.forms.ChangePasswordForm');
            $request = Yii::app()->request;
            $user = Yii::app()->user->getModel();
            //  if(!isset($user->service)){
            $oldAvatar = $user->avatar;

            $changePasswordForm = new ChangePasswordForm();

            $changePasswordForm->user = $user;

            if (isset($_POST['User'])) {
                $user->attributes = $_POST['User'];
                //$user->email = isset($_POST['User']['email']) ? $_POST['User']['email'] : null;


                
                if ($user->validate()) {
                    /*$file = CUploadedFile::getInstance($user, 'avatar');
                    if (isset($file) && !empty($file)) {
                        var_dump($file);
                        $path = Yii::getPathOfAlias('webroot.uploads.users.avatar');
                        if (isset($oldAvatar) && file_exists($path . DS . $oldAvatar)) {
                            unlink($path . DS . $oldAvatar);
                        }
                        $newFile = time() . "." . $file->getExtensionName();
                        Yii::app()->img
                                ->load($file->tempName)
                                ->thumb(100, 100)
                                ->save($path . DS . $newFile, false, 100);
                        $user->avatar = $newFile;
                    }else{
                        $user->avatar = $oldAvatar;
                    }*/
                    $user->saveImage('avatar','webroot.uploads.users.avatar',$oldAvatar);
                    $user->save();
                    // $this->refresh();
                }
            }

            if ($request->getPost('ChangePasswordForm')) {
                $changePasswordForm->attributes = $request->getPost('ChangePasswordForm');
                if ($changePasswordForm->validate()) {
                    $user->password = User::encodePassword($changePasswordForm->new_password);
                    if ($user->save(false, false, false)) {
                        $forum = new CIntegrationForums;
                        $forum->changepassword($user->login, $changePasswordForm->new_password, $user->email);
                    }
                    $this->addFlashMessage(Yii::t('UsersModule.default', 'Пароль успешно изменен.'));
                    $this->redirect('post/read',array('#'=>'chagepass'));
                }
            }

            $uConfig = Yii::app()->settings->get('users');
            $tabsArray = array(
                Yii::t('UsersModule.default', 'PROFILE') => array(
                    'content' => $this->renderPartial('_profile', array('user' => $user), true),
                    'id' => 'profile',
                    'visible' => true
                ),
                Yii::t('UsersModule.default', 'CHANGE_PASSWORD') => array(
                    'content' => $this->renderPartial('_changepass', array('changePasswordForm' => $changePasswordForm), true),
                    'id' => 'changepass',
                    'visible' => true
                ),
                Yii::t('UsersModule.default', 'FRIENDS') => array(
                    'ajax' => $this->createAbsoluteUrl('friends/index'),
                    'id' => 'friends',
                    'visible' => $uConfig['friends'] && false
                ),
                Yii::t('UsersModule.default', 'FAVORITES') => array(
                    'ajax' => $this->createAbsoluteUrl('favorites/index'),
                    'id' => 'favorites',
                    'visible' => $uConfig['favorites'] && false
                ),
                Yii::t('UsersModule.default', 'MESSAGES') => array(
                    'ajax' => $this->createAbsoluteUrl('/message/inbox'),
                    'id' => 'message',
                    'visible' => Yii::app()->user->message && false
                ),
            );
            $tabs = array();
            foreach ($tabsArray as $k => $tab) {
                if ($tabsArray[$k]['visible']) {
                    $tabs[$k] = $tabsArray[$k];
                }
            }


            $this->render('index', array(
                'user' => $user,
                 'tabs' => $tabs,
                'changePasswordForm' => $changePasswordForm
            ));
        } else {
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

    /**
     * Display user orders
     */
    public function actionOrders() {
        Yii::import('mod.cart.models.*');
        Yii::import('mod.cart.CartModule');
        $this->pageName = Yii::t('UsersModule.core', 'Мои заказы');
        $this->pageTitle = $this->pageName;
        $orders = new Order('search');
        $orders->user_id = Yii::app()->user->getId();

        $this->render('orders', array(
            'orders' => $orders,
        ));
    }

    public function actionUserInfo($user_id) {
        $user = User::model()->findByPk((int) $user_id);

        if (isset($user)) {
            //if(!$user->banned){
            $this->render('user_info', array('user' => $user));
            //}else{
            // $this->redirect('/');
            //    Yii::app()->tpl->alert('info','ban');
            //}
        } else {
            $this->redirect('/');
        }
    }

    public function actionSaveAvatar22() {


        $collection = (isset($_POST['collection'])) ? $_POST['collection'] : $_GET['collection'];
        $avatar = (isset($_POST['img'])) ? $_POST['img'] : $_GET['img'];

        if (!Yii::app()->user->isGuest) {
            $user_id = intval(Yii::app()->user->id);

            $user = User::model()->findByPk($user_id);

            if ($user->validate()) {
                $user->avatar = $avatar;
                $user->save();


                //$this->redirect('/users/profile');
            } else {
                die(print_r($user->getErrors()));
            }
        } else {

            $this->redirect('/users/profile');
        }
    }

    public function actionGetAvatars2222() {
        $collection = $_POST['collection'];
        $avatars = array();
        if (!Yii::app()->user->isGuest && $collection) {


            $dir = opendir(Yii::getPathOfAlias('webroot.uploads.users.avatars') . '/' . $collection);
            while ($entry = readdir($dir)) {
                if (preg_match("/(\.gif|\.png|\.jpg|\.jpeg)$/is", $entry) && $entry != "." && $entry != "..") {
                    //  $entryname = str_replace("_", " ", preg_replace("/^(.*)\..*$/", "\\1", $entry));
                    $avatars[] = $entry;
                }
            }
            closedir($dir);


            $this->render('_avatar_collections', array('avatars' => $avatars, 'collection' => $collection));
        } else {
            //redirect
        }
    }

}
