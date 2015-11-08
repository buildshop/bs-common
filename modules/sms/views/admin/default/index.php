<?php

Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => ' ')
));
?>
<script>
    $(document).ready(function(){
        $('#SettingsSmsForm_service').change(function(){
            $.ajax({
                type:'POST',
                url:'/admin/sms/default/configurationForm/system/'+$(this).val(),
                success:function(data){
                    $('#sms_configuration').html(data);
                    $('.selectpicker').selectpicker();
                }
            });
            //  $('#sms_configuration').load('/admin/sms/default/configurationForm/system/'+$(this).val());
        });
        $('#SettingsSmsForm_service').change();
    });
</script>


<?php

echo $model->getForm();
$class = new Turbosms(); //+380634236242
$class->connect();
echo $class->getBalance();
 


//$class->connect();
//echo $class->getBalance();
//$class->send('Это сообщение будет доставлено на указанный номер',array('+380634236242','+380633907136'));
//$class->send('Это сообщение будет доставлено на указанный номер','+380634236242');
//$this->widget('mod.sms.widgets.SMSWidget',array('type'=>1));
Yii::app()->tpl->closeWidget();
?>