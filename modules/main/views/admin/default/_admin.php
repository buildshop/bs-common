<script type="text/javascript">
    $(function(){
        // init_uniform();
        // init_checkboxAll();
        //init_uniform_grid();
        // $('#actionList').actionList({selector:'.widget', formid:'#form', actionUrl: '/news/admin/', dialog:true});
        // $('#actionNumList').actionList({selector:'.widget', formid:'#formOptions', method:'GET', actionUrl: '/news/admin/'});
        //$('.ajaxUrl').ajaxAdminURL();
    });</script>  

<style>
    table{
        width: 100%;
    }
    .summary{
        display: none;
    }
    .optionBlock{
        display: none;

        border-top: 1px solid #FFFFFF;

    }
    .grid-view-loading{
        background: #fff;
    }
</style>


<script>
    $(function(){
        $('.tableOptions').click(function(){
            $(this).toggleClass('act');
            if($(this).hasClass('act')){
                $(this).next('.optionBlock').show();
            }else{
                $(this).next('.optionBlock').hide();
            }
        });
    });
</script>

<div class="whead">
    <span class="titleIcon">
        <input id="titleCheck" type="checkbox" name="titleCheck">
    </span>
    <h6>Список</h6>
    <div class="clear"></div>

</div>
<span class="tableOptions icon-search icon-medium"></span>

<div class="optionBlock ">
    <?php $this->renderPartial('_search', array('model' => $model)); ?>
    <div class="clear"></div>
</div>


<?php
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'id' => 'pagesListGrid',
    //'filter'=>$model,
    'columns' => array(
        array(
            'class' => 'CCheckBoxColumn',
        ),
        array(
            'name' => 'title_ru',
            'type' => 'raw',
            'value' => 'CHtml::link(CHtml::encode($data->title_ru), array("update", "id"=>$data->id))',
        ),
        array(
            'class' => 'ButtonColumn',
            'header' => Yii::t('core', 'Options'),
            'template' => '{switch}{update}{delete}',
        ),
    ),
));
?>
