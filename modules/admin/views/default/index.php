
<script type="text/javascript">
    $(function() {
        $('.delete-widget').click(function(){
            var uri = $(this).attr('href');
            var ids = $(this).attr('data-id');
            console.log(ids);
            app.ajax(uri, {}, function(data){
                $('#ordern_'+ids).remove();
            });
            return false; 
        });
        
        $('#createWidget').click(function(){
            var uri = $(this).attr('href');
            app.ajax(uri, {}, function(data){

                $('body').append('<div id="dialog"></div>');
             
                $('#dialog').dialog({
                    modal: true,
                    autoOpen:true,
                    width:500,
                    title:"<?= Yii::t('core', 'DEKSTOP_CREATE_WIDGET'); ?>",
                    resizable: false,
                    open:function(){
                        var obj = $.parseJSON(data);
                        $(this).html(obj.content);
                    },
                    close: function (event, ui) {
                        $(this).remove();
                    },
                    buttons:[{
                            text:'OK',
                            click:function(){
                                app.ajax(uri, $('#dialog form').serialize(), function(data){
                                    var obj = $.parseJSON(data);
                                    if(obj.success){
                                        $('#dialog').dialog('close');
                                        location.reload();
                                    }else{
                                        $('#dialog').html(obj.content);
                                    }
                                });
                            }
                        },{
                            text:'Cancel',
                            click:function(){
                                $(this).dialog('close');
                            }
                        }]
                });
                $( "#dialog" ).dialog( "open" );
            });
            return false; 
        });
        
        $( ".column" ).sortable({
            cursor: 'move',
            connectWith: ".column",
            handle: ".handle",
            placeholder: "placeholder",
            update:function(event, ui){
                var data = $(this).sortable('serialize');
                data+='&dekstop_id='+$(this).attr('data-dekstop-id');
                if(ui.sender){
                    data+='&columnFrom='+$(ui.sender).attr('data-id');
                    data+='&columnTo='+$(this).attr('data-id');
                }
                $.post('/admin/dekstop/updateColumns', data, function(response){
                    
                });
            }
        });
        //$( ".column" ).disableSelection();
    });


</script>


<?php
if (isset($AddonsItems) && $dekstop->addons) {
    echo Html::openTag('div', array('class' => 'btn-group btn-group-lg'));
    foreach ($AddonsItems as $key => $item) {

        if (isset($item['count'])) {
            $count = '<span class="badge">' . $item['count'] . '</span>';
        } else {
            $count = '';
        }
        echo Html::link('<i class="' . $item['icon'] . '"></i><br/>' . $item['label'] . $count, $item['url'], array('class' => 'btn btn-default'));
    }
    echo Html::closeTag('div');
    ?>

    <?php
}
?>

<div class="row">
    <div class="col-lg-6">
        <?php $this->widget('ext.blocks.yourinfo.YourinfoWidget'); ?>

                <?php $this->widget('mod.cart.widgets.stats.StatsWidget'); ?>

    </div>
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">вфвфы</div>
            </div>
            <div class="panel-body panel-body-static">
                <ul class="list-group">
                    <li class="list-group-item">Новых пользователей <span class="badge">3</span></li>
                    <li class="list-group-item">Новых заказов <span class="badge" id="newOrderCount"></span></li>
                    <li class="list-group-item">Уведомлений о наличие <span class="badge" id="newNotifyCount"></span></li>
                    <li class="list-group-item">Новые комментарии <span class="badge" id="newCommentsCount"></span></li>
                    <li class="list-group-item">Vestibulum at eros</li>
                </ul>
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">SMS</div>
            </div>
            <div class="panel-body panel-body-static">
                <?php $this->widget('mod.sms.widgets.SMSWidgetBalance'); ?>
            </div>
        </div>

    </div>
</div>


<div class="fluid">
    <?php
    $x = 0;
    while ($x++ < $dekstop->columns) {
        if ($dekstop->columns == 3) {
            $class = 'grid4';
        } elseif ($dekstop->columns == 2) {
            $class = 'grid6';
        } else {
            $class = '';
        }
        ?>
        <div class="column <?= $class; ?>" data-id="<?= $x; ?>" data-dekstop-id="<?= $dekstop->id ?>">&nbsp;
            <?php
            $cr = new CDbCriteria;

            $cr->condition = '`t`.`column`=:clmn AND `t`.`dekstop_id`=:dekstopID';
            $cr->order = '`t`.`ordern` ASC';
            $cr->params = array(
                ':clmn' => $x,
                ':dekstopID' => $dekstop->id
            );
            $widgets = DekstopWidgets::model()
                    ->cache($this->cacheTime)
                    ->with('wgt')
                    ->findAll($cr);
            foreach ($widgets as $wgt) {
                ?>
                <div class="widget dekstop-widget" id="ordern_<?= $wgt->id ?>">
                    <div class="whead"><h6><?= $wgt->wgt2->name ?></h6>
                        <div class="sOptions">

                            <?php
                            echo Html::link('<span class="icon-move"></span>', '#', array('class' => 'handle'));
                            echo Html::link('<span class="icon-close"></span>', $this->createUrl('dekstop/deleteWidget', array('id' => $wgt->id)), array('data-id' => $wgt->id, 'class' => 'delete-widget'));
                            ?>
                        </div>
                        <div class="clear"></div></div>
                    <div class="widget_content">
                        <?php $this->widget($wgt->wgt2->alias_wgt) ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>
