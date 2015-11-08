

<div class="widget fluid">
    <div class="whead"><h6>Перевроды файла <?= $file ?></h6><div class="clear"></div></div>

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'translate-form',
        'enableAjaxValidation' => false,
            ));
    echo Html::hiddenField('module', $module);
    echo Html::hiddenField('locale', $locale);
    echo Html::hiddenField('file', $file);
    echo Html::hiddenField('type', $type);
    ?>
    <table class="tDefault checkAll tMedia">
        <thead> 
            <tr>
                <th width="20%">Ключь</th>
                <th width="70%">Перевод</th>
                <th width="10%"><a href="javascript:addTranslate()" class="buttonS bGreen"><span class="icon-plus"></span></a></th>
            </tr>
        </thead> 
        <tbody id="list">
            <?php
            foreach ($return as $key => $translate) {
                $pos = strpos($translate, '|');
                if ($pos) {
                    $exp = explode('|', $translate);
                    ?>
                    <tr id="f<?= $key ?>">
                        <td class="textL"><?= Html::label($key, 'TranslateForm[' . $key . ']'); ?></td>
                        <td><?php foreach ($exp as $key2 => $translate) { ?>
                                <?= Html::textField('TranslateForm[' . $key . '][]', substr($translate, 2)); ?><br/>
                            <?php } ?>
                            <a href="javascript:void(0)" onclick="addParam('<?= $key ?>')" class="buttonH bGreen">добавить параметр</a></td>
                        <td><a href="javascript:void(0)" onClick="removeTranslate('#f<?= $key ?>');"><span class="icon-trashcan icon-medium"></span></a></td>
                    </tr>
                <?php } else { ?>
                    <tr id="f<?= $key ?>">
                        <td class="textL"><?= Html::label($key, 'TranslateForm[' . $key . ']'); ?></td>
                        <td><?= Html::textField('TranslateForm[' . $key . ']', $translate); ?></td>
                        <td><a href="javascript:void(0)" onClick="removeTranslate('#f<?= $key ?>');"><span class="icon-trashcan icon-medium"></span></a></td>
                    </tr>
                <?php } ?>

            <?php } ?>
        </tbody></table>

    <div class="formRow noBorderB">
        <div class="grid12 textC"><?= Html::link(Yii::t('core', 'SAVE'), 'javascript:ajaxSave()', array('class' => 'buttonS bGreen')); ?> <a href="javascript:options()" class="buttonS bDefault">Дополнительные параметры</a></div>
        <div class="clear"></div>
    </div>


    <div id="options" class="hidden">
        <div class="divider"><span></span></div>
        <div class="formRow noBorderT"><?= Yii::app()->tpl->alert('warning','Важно! Если файл выбранного перевода уже существует он будет перезаписан.',false); ?></div>
        <div class="formRow noBorderB">
            <div class="grid3"><label for="lang">Перевести эти переводы на язык:</label></div>
            <div class="grid9">
                <?php echo Html::dropDownList('lang', null, yandexTranslate::onlineLangs(), array('empty' => Yii::t('core', 'EMPTY_DROPDOWNLIST', 1))); ?>
                <div class="hint">Данный файл будет переведен с помощью Yandex translate API. После выбора перевода, нажмите сохранить</div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

<?php
$this->endWidget();
?>

<script>
    function options(){
        $('#options').toggleClass('hidden');
    }
    function ajaxSave(){
        $.ajax({
            type:'POST',
            url:'/admin/core/translates/ajaxOpen',
            data: $('#translate-form').serialize(),
            success:function(result){
                // $('#tester').html(result);
                $.jGrowl('Перевод успешно сохранен');
            },
            beforeSend:function(){
                $.jGrowl('Сохранение...');
            },
            error:function(){
                $.jGrowl('Ошибка');
            }
        });
    }
    function addParam(key){
        var valid = false;
        // var paramName = prompt('Введите ключь перевода','NEW_PARAM');
        alert('В разработке.');
    }
    function removeTranslate(obj){
        $(obj).remove();
    }
    function addTranslate(){
        var valid = false;
        var paramName = prompt('Введите ключь перевода','NEW_PARAM');
        if(paramName!=null){
            paramName = paramName.replace(" ","_");
            paramName = paramName.toUpperCase();
            $('#list tr td.textL').each(function(k,index){
                if(paramName == $(index).text()){
                    valid = false;
                    $.jGrowl('Такой параметр уже существует.');
                }else{
                    valid = true;
                }

            });
            if(valid){
                $('#list').prepend($('<tr/>', {
                    'id':paramName
                }).append($('<td/>',{
                    'class': 'textL'
                }).append($('<label>').attr('for','TranslateForm['+paramName+']').text(paramName)
            )).append($('<td/>').append($('<input/>', {
                    'type': 'text',
                    'name':'TranslateForm['+paramName+']'
                }))).append($('<td/>').append($('<a/>',{click:function(){
                        removeTranslate('#'+paramName);
                    }}).append($('<span/>',{'class':'icon-trashcan icon-medium'})))));
            }
        }
    }
</script>