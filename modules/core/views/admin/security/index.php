<?php
if(Yii::app()->request->isAjaxRequest){
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div',array('class'=>'wrapper'));
}
?>
<script>
    $(function(){
        $('#accordion').accordion({header: "> div > h3"});
    });
</script>
<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => 'fluid')
));
echo $model->getForm();
Yii::app()->tpl->closeWidget();




$this->widget('ext.loganalyzer.LogAnalyzerWidget', array(
        'filters' => array('Text filtering','One more'),
    //'log_file_path'=>Yii::getPathOfAlias('webroot.log'),
        'title'   => 'Logs' ,
        // 'log_file_path' => 'Absolute path of the Log File',
    ));  







Yii::app()->tpl->closeWidget();
if(Yii::app()->request->isAjaxRequest){
    echo Html::closeTag('div');
}
?>





