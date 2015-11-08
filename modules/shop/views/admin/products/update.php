<?php
if ($this->isAjax) {
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div', array('class' => 'wrapper'));
}

if (!$model->isNewRecord && Yii::app()->settings->get('shop', 'auto_gen_url')) {
    Yii::app()->tpl->alert('warning', Yii::t('ShopModule.admin', 'ENABLE_AUTOURL_MODE'));
}

Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => '')
));
?>


<?php
// If selected `configurable` product without attributes display error
if ($model->isNewRecord && $model->use_configurations == true && empty($model->configurable_attributes))
    $attributeError = true;
else
    $attributeError = false;

if ($model->isNewRecord && !$model->type_id || $attributeError === true) {
    // Display "choose type" form
    echo Html::form('', 'get', array('class' => 'form-horizontal'));

    if ($attributeError) {
        echo '<div class="errorSummary"><p>' . Yii::t('ShopModule', 'Необходимо исправить следующие ошибки:') . '</p>
					<ul>
						<li>' . Yii::t('ShopModule.admin', 'Выберите атрибуты для конфигурации продуктов.') . '</li>
					</ul>
			</div>';
    }
    ?>
    <div class="form-group">
        <div class="col-sm-4"><?= Html::activeLabel($model, 'type_id'); ?></div>
        <div class="col-sm-8"><?= Html::activeDropDownList($model, 'type_id', CHtml::listData(ShopProductType::model()->orderByName()->findAll(), 'id', 'name'), array('class' => 'form-control')); ?></div>
    </div>
    <div class="form-group">
        <div class="col-sm-4"><?= Html::activeLabel($model, 'use_configurations'); ?></div>
        <div class="col-sm-8"><?= Html::activeDropDownList($model, 'use_configurations', array(0 => Yii::t('app', 'NO'), 1 => Yii::t('app', 'YES')), array('class' => 'form-control')); ?></div>
    </div>
    <div id="availableAttributes"></div>
    <div class="form-group text-center">
        <?= Html::submitButton(Yii::t('app', 'CREATE', 0), array('name' => false, 'class' => 'btn btn-success')); ?>
    </div>
    <?php
    echo Html::endForm();
}
else
    echo $form->tabs();
?>
<?php Yii::app()->tpl->closeWidget(); ?>

<script type="text/javascript">init_translitter('ShopProduct','<?= $model->primaryKey; ?>');</script>

<?php
if ($this->isAjax)
    echo Html::closeTag('div');