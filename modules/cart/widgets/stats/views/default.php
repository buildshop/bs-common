<div class="panel with-nav-tabs panel-default">
    <div class="panel-heading">
        <div class="panel-title pull-left">Статистика заказов</div>
        <ul class="nav nav-tabs pull-right">
            <li class="active"><a href="#yesterday" data-toggle="tab">За неделю</a></li>
            <li><a href="#week" data-toggle="tab">За неделю</a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="panel-body panel-body-static">
        <div class="tab-content">
            <div class="tab-pane active" id="yesterday">

                
                <?php
               // Yii::app()->controller->renderPartial('_week');
                $this->render('_yesterday');
            
                ?>

            </div>
            <div class="tab-pane" id="week">
                <?php  $this->render('_week');?>
                
            </div>
        </div>
    </div>
</div>


