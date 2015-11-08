<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => '')
));
 echo $model->getForm(); 
Yii::app()->tpl->closeWidget();

?>

<script>
    
    $(document).ready(function(){
        $('#BlocksModel_widget').change(function(){
            $('#payment_configuration').load('/admin/core/blocks/configurationForm/system/'+$(this).val());
        });
        $('#BlocksModel_widget').change();
    });
</script>