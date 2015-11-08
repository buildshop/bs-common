<?php

/**
 * Контроллер AJAX запрос пользователей.
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.users.controllers
 * @uses Controller
 */
class AjaxController extends Controller {

    public function actionAddFriend() {
        $a = array();
        if (Yii::app()->request->isAjaxRequest) {
            $model = new UserFriends();
            $post = $_POST['UserFriends'];
            if (isset($post)) {
                $model->attributes = $post;
                $model->user_id = Yii::app()->user->id;
                if ($model->validate()) {
                    if ($model->save(false, false)) {
                        $a = array('message' => 'Заявка успешно подана!', 'errorCode' => 0);
                    } else {
                        $a = array('message' => Yii::t('core', 'ERROR'), 'errorCode' => 1);
                    }
                } else {
                    $a = array('message' => $model->errors['user_id'], 'errorCode' => 2);
                }
            } else {
                $a = array('message' => Yii::t('core', 'ERROR'), 'errorCode' => 3);
            }
        } else {
            $a = array('message' => Yii::t('core', 'ERROR'), 'errorCode' => 4);
        }
        echo CJSON::encode($a);
    }

    public function actionActiveFriend() {
        $a = array();
        if (Yii::app()->request->isAjaxRequest) {
            $post = $_POST['UserFriends'];
            $model = UserFriends::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'friend_id' => $post['friend_id']));

            if (isset($model)) {
                $model->attributes = $post;

                if ($model->validate()) {

                    $model->status = 1;
                    if ($model->save(false, false)) {
                        $a = array('message' => 'Пользователь успешно добавлен в друзья', 'errorCode' => 0);
                    } else {
                        $a = array('message' => Yii::t('core', 'ERROR'), 'errorCode' => 1);
                    }
                } else {
                    $a = array('message' => $model->errors['user_id'], 'errorCode' => 2);
                }
            } else {
                $a = array('message' => Yii::t('core', 'ERROR'), 'errorCode' => 3);
            }
        } else {
            $a = array('message' => Yii::t('core', 'ERROR'), 'errorCode' => 4);
        }
        echo CJSON::encode($a);
    }
    
    
    public function actionDeleteFriend() {
        $a = array();
        if (Yii::app()->request->isAjaxRequest) {
            $post = $_POST['UserFriends'];
            $model = UserFriends::model()->findByAttributes(array('user_id' => Yii::app()->user->id, 'friend_id' => $post['friend_id']));

            if (isset($model)) {
                $model->attributes = $post;

                if ($model->validate()) {

                    $model->status = 1;
                    if ($model->delete()) {
                        $a = array('message' => 'Пользователь успешно удален из друзей', 'errorCode' => 0);
                    } else {
                        $a = array('message' => Yii::t('core', 'ERROR'), 'errorCode' => 1);
                    }
                } else {
                    $a = array('message' => $model->errors['user_id'], 'errorCode' => 2);
                }
            } else {
                $a = array('message' => Yii::t('core', 'ERROR'), 'errorCode' => 3);
            }
        } else {
            $a = array('message' => Yii::t('core', 'ERROR'), 'errorCode' => 4);
        }
        echo CJSON::encode($a);
    }
}
