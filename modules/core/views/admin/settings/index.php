<?php

if (Yii::app()->request->isAjaxRequest) {
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div', array('class' => 'wrapper'));
}
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => '')
));

echo $model->getForm()->tabs();
Yii::app()->tpl->closeWidget();
?>
<script type="text/javascript">
    function hasChecked(clickObject, classArray){
        $.each(classArray, function( k, className ) {
            if($(clickObject).is(':checked')){
                $(className).removeClass('hidden');
            }else{
                $(className).addClass('hidden');
            }
        });

    }
    $(function(){
        var classesClose = ['.field_site_close_text', '.field_site_close_allowed_ip', '.field_site_close_allowed_users'];
        var classesCensor = ['.field_censor_array', '.field_censor_replace'];
        var selectorClose = '#SettingsCoreForm_site_close';
        var selectorCensor = '#SettingsCoreForm_censor';
        $(selectorClose).change(function(){
            hasChecked(selectorClose, classesClose);
        });
        hasChecked(selectorClose, classesClose);
        $(selectorCensor).change(function(){
            hasChecked(selectorCensor, classesCensor);
        });
        hasChecked(selectorCensor, classesCensor);
    });
    


</script>
<?php

if (Yii::app()->request->isAjaxRequest) {
    echo Html::closeTag('div');
}
?>