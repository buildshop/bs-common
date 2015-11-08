<script>
    function changeLanguage(that, url){
        var selected = $('option:selected', that).val();
        if(selected==undefined){
            window.location.pathname=url; 
        }else{
            window.location.pathname=selected+''+url;
        }
    }
</script>

<?php echo CHtml::dropDownList('language', Yii::app()->languageManager->getUrlPrefix(), Yii::app()->languageManager->getLangs(), array('class' => 'select', 'onChange' => 'changeLanguage(this, "' . CMS::currentUrl() . '")')); ?>
