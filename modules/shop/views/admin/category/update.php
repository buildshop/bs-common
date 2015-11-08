<?php
if ($this->isAjax) {
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div', array('class' => 'wrapper'));
}
$this->widget('ext.fancybox.Fancybox', array(
    'target' => 'a.overview-image',
    'config' => array(),
));


$checkRoot = ShopCategory::model()
        ->findByPk(1);
if (!$checkRoot) {
    // throw new CHttpException(404,'no root');
    Yii::app()->tpl->alert('warning', 'Необходимо создать root категорию. <a href="/admin/shop/category/createRoot">создать</a>', false);
} else {
    ?>
    <div class="row">
        <div class="col-lg-8">
            <?php
            Yii::app()->tpl->openWidget(array(
                'title' => $this->pageName,
                'htmlOptions' => array('class' => '')
            ));
            echo $model->getForm()->tabs();
            Yii::app()->tpl->closeWidget();
            ?>
        </div>


        <div class="col-lg-4">
            <?php $this->renderPartial('_categories', array('model' => $model)); ?>
        </div>
    </div>
    <script type="text/javascript">init_translitter('ShopCategory','<?= $model->primaryKey; ?>', false);</script>

    <?php
}
if ($this->isAjax)
    echo Html::closeTag('div');
