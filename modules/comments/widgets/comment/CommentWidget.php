<?php

class CommentWidget extends CWidget {

    public $model;

    public function init() {
        $this->registerAssets();
    }

    public function run() {
        $module = Yii::app()->getModule('comments');

        $comment = $module->processRequest($this->model);
        $currentUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $config = Yii::app()->settings->get('comments');

        $criteria = new CDbCriteria;
        $criteria->condition = '`t`.`model`=:class AND object_id=:pk';
        $criteria->scopes = array('roots', 'active');
        $criteria->order = '`t`.`date_create` DESC';
        $criteria->params = array(
            ':class' => $this->model->getModelName(),
            ':pk' => $this->model->id,
        );

        $dataProvider = new ActiveDataProvider('Comments', array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageVar' => 'comment_page',
                        'pageSize' => $config['pagenum']
                    )
                ));
        $obj_id = $this->model->getObjectPkAttribute();
        $this->render('comment_form', array(
            'comment' => $comment,
            'currentUrl' => $currentUrl,
            'object_id' => $this->model->$obj_id,
            'owner_title' => $this->model->getOwnerTitle(),
            'model' => $this->model->getModelName()
        ));

        $this->render('comment_list', array('dataProvider' => $dataProvider));
    }

    public function registerAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
        $css = (Yii::app()->controller instanceof AdminController) ? 'admin_comments.css' : 'comments.css';
        
        $themeName = Yii::app()->settings->get('core','theme');
        if (is_dir($assets)) {
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/jquery.session.js', CClientScript::POS_HEAD);
            Yii::app()->clientScript->registerScriptFile($baseUrl . '/js/comment.js', CClientScript::POS_BEGIN);
            if(file_exists(Yii::getPathOfAlias("webroot.themes.{$themeName}.assets.css").'/'.$css)){
                $baseUrl = Yii::app()->controller->getAssetsUrl();
            }
            Yii::app()->clientScript->registerCssFile($baseUrl . '/css/' . $css);
            Yii::app()->clientScript->registerScript('comment-messages', "
var commentsMessage = {
    'save':'" . Yii::t('app', 'SAVE') . "',
    'cancel':'" . Yii::t('app', 'CANCEL') . "',
    'yes':'" . Yii::t('app', 'YES') . "',
    'no':'" . Yii::t('app', 'NO') . "',
};
		", CClientScript::POS_HEAD);
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

}