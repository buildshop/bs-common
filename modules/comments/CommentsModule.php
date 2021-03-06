<?php

class CommentsModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id.'.models.*',
        ));
    }

    public function processRequest($model) {
        $comment = new Comments;
        $request = Yii::app()->request;
        $pkAttr = $model->getObjectPkAttribute();
        if ($request->isPostRequest && $request->isAjaxRequest) {

            $comment->attributes = $request->getPost('Comments');
            $comment->model = $model->getModelName();
            $comment->owner_title = $model->getOwnerTitle();
            $comment->object_id = $model->$pkAttr;
            if ($comment->validate()) {
                $comment->saveNode();
                echo CJSON::encode(array('success'=>'OK'));
                Yii::app()->end();
                Yii::app()->session['caf'] = time();
            }
        }
        return $comment;
    }

    public function getRules() {
        return array(
            '/comments/edit' => '/comments/default/edit',
            '/comments/reply/<id:(\d+)>' => '/comments/default/reply',
            '/comments/reply_submit/' => '/comments/default/reply_submit',
            '/comments/delete/<id:(\d+)>' => '/comments/default/delete',
            //'/comments/edit/save' => '/comments/default/edit',
            '/comments/create' => '/comments/default/create',
            '/comments/auth' => '/comments/default/authProvider',
            '/comments/auth/<provide>' => '/comments/default/auth',
            '/rate/<type:(up|down)>/<object_id:(\d+)>' => '/comments/default/rate',
        );
    }

    public function afterInstall() {
        Yii::app()->settings->set($this->id, SettingsCommentForm::defaultSettings());
        Yii::app()->database->import($this->id);
        return parent::afterInstall();
    }

    public function afterUninstall() {
        Yii::app()->settings->clear($this->id);
        Yii::app()->db->createCommand()->dropTable(Comments::model()->tableName());
        return parent::afterUninstall();
    }

    public static function getInfo() {
        return array(
            'name' => Yii::t('CommentsModule.default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '2.0 PRO beta',
            'icon' => 'flaticon-comments',
            'url' => Yii::app()->createUrl('/comments/admin/default/index'),
            'description' => Yii::t('CommentsModule.core', 'Позволяет оставлять комментарии к продуктам, страницам.'),
        );
    }

    public static function getAdminMenu() {
        $c = Yii::app()->controller->id;
        return array(
            'system' => array(
                'items' => array(
                    array(
                        'label' => Yii::t('CommentsModule.default', 'MODULE_NAME'),
                        'url' => array('/admin/comments'),
                        'icon' => 'flaticon-comments',
                        'active' => ($c == 'admin/default') ? true : false,
                        'visible' => Yii::app()->user->isSuperuser,
                    ),
                ),
            ),
        );
    }

    public function getAdminSidebarMenu() {
        $c = Yii::app()->controller->id;
        return array(
            $this->adminMenu['system']['items'][0],
            array(
                'label' => Yii::t('core', 'SETTINGS'),
                'url' => array('/admin/comments/settings'),
                'active' => ($c == 'admin/settings') ? true : false,
                'icon' => 'flaticon-settings',
                'visible' => Yii::app()->user->isSuperuser
            )
        );
    }

}