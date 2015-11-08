<?php 

$tpl = TplMail::model()->findByPk(1);
$tpl->setOptions = $tpl->getModelByPk(1,'User');
$res = $tpl->getBody();
echo $res;
$ss = $tpl->getModelByPk(1,'User');
print_r($ss);

Yii::app()->tpl->openWidget(array('title' => $this->pageName));
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'selectableRows' => false,
    'enableHeader' => false,
    'autoColumns' => false,
    'columns' => array(

        'formkey',

        array(
            'class' => 'ButtonColumn',
            'template' => '{update}{delete}',
            'hidden' => array(
                'delete' => array(1),
            )
        ),
    ),
));
Yii::app()->tpl->closeWidget();
?>