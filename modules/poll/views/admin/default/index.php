<?php
Yii::app()->tpl->openWidget(array('title' => $this->pageName));


$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'filter' => $model,
    'filterCssClass' => 'tfilter',
    'columns' => array(
        array(
            'name' => 'title',
            'type' => 'raw',
            'value' => 'Html::link(CHtml::encode($data->title), array("/admin/poll/default/update", "id"=>$data->id))',
            'htmlOptions' => array('class' => 'textL')
        ),
        array(
            'name' => 'switch',
            'filter' => array(1 => Yii::t('core', 'Показанные'), 0 => Yii::t('core', 'Скрытые')),
            'value' => '$data->switch ? Yii::t("core", "Показан") : Yii::t("core", "Скрыт")'
        ),
        array(
            'class' => 'ButtonColumn',
            'group' => false,
            'header' => Yii::t('core', 'OPTIONS'),
            'template' => '{clear}{switch}{update}{delete}',
            'buttons' => array(
                'clear' => array(
                    'label' => 'Обнулить голосование',
                    'url' => 'Yii::app()->createUrl("/admin/poll/default/clear", array("id"=>$data->id))',
                    'icon'=>'icon-refresh',
                    'visible' => '1',
                    'click'=>"
                        function() {
            var th = this
            $('body').append('<div id=\"dialog\"></div>');
            $('#dialog').dialog({
                modal: true,
                resizable: false,
                title:$(th).attr('title'),
                open:function(){
                    $(this).html('Вы уверене что хотите обнулить голосование $data->title?');
                },
                close: function (event, ui) {
                    $(this).remove();
                },
                buttons:[{
                        text:'".Yii::t('core','YES')."',
                        click:function(){
                            $(this).dialog('close');

                            $('#poll-grid').yiiGridView('update', {
                                    type: 'POST',
                                    url: $(th).attr('href'),
                                    data:{'".Yii::app()->request->csrfTokenName."':'".Yii::app()->request->csrfToken."' },
                                    success: function(data) {
                                            $('#poll-grid').yiiGridView('update');
                                        
                                
                                    }
                            });
                        }
                    },{
                        text:'".Yii::t('core','CANCEL')."',
                            'class': 'bDefault',
                        click:function(){
                            $(this).dialog('close');
                            
                        }
                    }]
            });
            return false;
}
"
                )
            )
        ),
    ),
));
Yii::app()->tpl->closeWidget();
?>













</div>


