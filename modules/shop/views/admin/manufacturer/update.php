<?php
if ($this->isAjax) {
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div', array('class' => 'wrapper'));
}
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => ' ')
));
echo $model->getForm()->tabs();
Yii::app()->tpl->closeWidget();
?>
<script type="text/javascript">init_translitter('ShopManufacturer','<?= $model->primaryKey; ?>');</script>
<?php
if ($this->isAjax) echo Html::closeTag('div');
?>