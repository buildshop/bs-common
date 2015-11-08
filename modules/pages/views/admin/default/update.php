<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => '')
));
echo $model->getForm()->tabs();
Yii::app()->tpl->closeWidget();
?>
<script type="text/javascript">init_translitter('Pages','<?= $model->primaryKey; ?>');</script>




