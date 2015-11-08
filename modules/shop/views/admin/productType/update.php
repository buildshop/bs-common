<?php
if ($this->isAjax) {
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div', array('class' => 'wrapper'));
}

Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => ' ')
));

echo Html::beginForm('', 'post', array(
    'id' => 'ShopProductTypeForm',
    'class' => 'form-horizontal'
));
echo Html::errorSummary($model);

echo Html::hiddenField('main_category', $model->main_category);

$this->widget('ext.sidebartabs.AdminTabs', array(
    'tabs' => array(
        Yii::t('app', 'OPTIONS') => $this->renderPartial('_options', array('model' => $model, 'attributes' => $attributes), true),
        Yii::t('app', 'CATEGORIES') => $this->renderPartial('_tree', array('model' => $model), true),
    )
));
?>

<div class="form-group buttons text-center">
    <?= Html::submitButton(Yii::t('app', 'SAVE'), array('class' => 'btn btn-success')); ?>
</div>

<?= Html::endForm(); ?>
<?php Yii::app()->tpl->closeWidget(); ?>
<?php
if ($this->isAjax)
    echo Html::closeTag('div');