<?php

/**
 * Product comments
 *
 * @var $model ShopProduct
 */
Yii::import('comments.models.Comments');

$module = Yii::app()->getModule('comments');
$comments = new Comments('search');

if (!empty($_GET['Comment']))
    $comments->attributes = $_GET['Comment'];

$comments->class_name = 'mod.shop.models.ShopProduct';
$comments->object_pk = $model->id;

// Fix sort url
$dataProvider = $comments->search();
$dataProvider->pagination->pageSize = Yii::app()->settings->get('core', 'pagenum');
$dataProvider->sort->route = 'applyCommentsFilter';
$dataProvider->pagination->route = 'applyCommentsFilter';

$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    'id' => 'productCommentsListGrid',
    //'filter'       => $comments,
    'ajaxUrl' => Yii::app()->createUrl('/shop/admin/products/applyCommentsFilter', array('id' => $model->id)),
    'enableHistory' => false,
    'columns' => array(
        array(
            'class' => 'CheckBoxColumn',
        ),
        array(
            'name' => 'name',
            'type' => 'raw',
            'value' => 'CHtml::link(CHtml::encode($data->name), array("/comments/admin/comments/update", "id"=>$data->id))',
        ),
        array(
            'name' => 'email',
        ),
        array(
            'name' => 'text',
            'value' => 'Comment::truncate($data, 100)'
        ),
        array(
            'name' => 'switch',
            'filter' => Comment::getStatuses(),
            'value' => '$data->statusTitle',
        ),
        array(
            'name' => 'date_create',
        ),
        // Buttons
        array(
            'class' => 'ButtonColumn',
            'updateButtonUrl' => 'Yii::app()->createUrl("/comments/admin/comments/update", array("id"=>$data->id))',
            'deleteButtonUrl' => 'Yii::app()->createUrl("/comments/admin/comments/delete", array("id"=>$data->id))',
            'template' => '{update}{delete}',
        ),
    ),
));
if(!$model->isNewRecord) $this->widget('mod.comments.widgets.comment.CommentWidget', array('model' => $model));