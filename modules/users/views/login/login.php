
<h1><?= $this->pageName ?></h1>

<?php
$this->renderPartial('_form', array('model' => $model));
?>

<?php //Yii::app()->eauth->renderWidget(); ?>