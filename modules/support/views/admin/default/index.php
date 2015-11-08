
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title pull-left">Новые обращения</div>

        <div class="pull-right">
            <a  href="/admin/support/default/create" class="btn btn-success btn-xs">
                Создать
            </a>
            <a  href="/admin/support/default/archive" class="btn btn-primary btn-xs">
                Архив
            </a>
        </div>
        <div class="clearfix"></div>


    </div>
    <div class="panel-body">
        <?php
        $this->widget('ext.adminList.GridView', array(
            'dataProvider' => $ticket->search(),
            // 'filter'=>false,
            // 'name'=>$this->pageName,
            'enableHeader' => false,
            'selectableRows' => false,
            'autoColumns' => false,
            'columns' => array(
                array(
                    'name' => 'name',
                    'type' => 'raw',
                    'htmlOptions' => array('class' => 'text-left'),
                    'value' => 'Html::link(Html::encode($data->name),array("view","id"=>$data->id))',
                ),
                array(
                    'name' => 'countMessages',
                    'type' => 'html',
                    'header' => Yii::t('SupportModule.default', 'COUNT_ANSWER'),
                     'htmlOptions' => array('class' => 'text-center'),
                    'value' => '$data->countMessages',
                ),
                array(
                    'name' => 'status',
                    'type' => 'raw',
                    'htmlOptions' => array('class' => 'text-center'),
                    'value' => '$data->statusByHtml'
                )
            )
        ));
        ?>
    </div>
</div>