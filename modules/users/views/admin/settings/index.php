
<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => '')
));
echo $model->getForm()->tabs();
Yii::app()->tpl->closeWidget();
?>

<script type="text/javascript">
    $(function(){
        var objects = '<?= $model->getJsonSocialClasses() ?>';
        var socialAuth = '#SettingsUsersForm_social_auth';
        var json = $.parseJSON(objects);
        $.each(json,function(key,index){
            hasChecked(key,index);
            $(key).click(function(){
                $(index).each(function(key2,val){
                    $(val).toggleClass('hidden');
                });
            });
            
        });




        $('#SettingsUsersForm_social_auth').click(function(){
            var that = this;
            $.each(json,function(key,index){
                //console.log(index);
                if($(that).attr('checked')){
                    var t=  $(key).parent().parent().parent().parent().removeClass('hidden');
                    $(index).each(function(key2,val){
                        $(key2).removeClass('hidden');

                    });
                    // console.log('y');
                }else{
                    $(key).parent().parent().parent().parent().addClass('hidden');
                    $(index).each(function(key2,val){
                        $(key2).addClass('hidden');

                    });
                    //  console.log('n');
                }
                //console.log(index);
                     
                // $(key).toggleClass('hidden');
                //$(val).toggleClass('hidden');
            });
        });
            
        function hasChecked(has, array){
            if($(has).attr('checked')){
                $(array).each(function(key2,index2){
                    $(index2).removeClass('hidden');
                });
            }else{
                console.log(array);
                $(array).each(function(key2,index2){
                    $(index2).addClass('hidden');
                });
            }
        }

    });
</script>