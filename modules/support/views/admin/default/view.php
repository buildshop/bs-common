<style>
    .support .media{
        border-bottom: 1px solid #e0e0e0;
        padding: 10px 0;
        margin: 10px 0;
        background: #ccc;
    }
    .support .media:nth-child(odd) {
        background: #e0e0e0;
    }
</style>


<div class="support">
    
    
    
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title pull-left"><?=$model->name?></div>

        <div class="pull-right">
             <span class="label label-<?= $model->statusByCssClass; ?>"><?= $model->statusByName; ?></span>
        </div>
        <div class="clearfix"></div>


    </div>
    <div class="panel-body panel-body-static">
        


        <p class="well"><b>Ваше обращение:</b> <?= mb_strtolower(CMS::date($model->date_create),Yii::app()->charset) ?><br/><?= $model->text ?></p>
    <hr/>
   
        <?php
        ?>
        <?php if ($model->countMessages > 0) { ?>
            <?php foreach ($model->messages as $message) { ?>
                <div class="media">
                    <div class="media-left media-middle">
                        <img class="media-object" src="<? //= $message->user->avatarUrl('50x50')  ?>" alt="">
                    </div>
                    <div class="media-body">
                        <h5 class="media-heading">
                            <?= $message->user->login ?>
                            <small><?= CMS::date($message->date_create) ?></small>
                        </h5>
                        <p><?= $message->text ?></p>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>


    <?php
    if ($model->status != 0)
    $this->renderPartial('_form', array('modelForm' => $modelForm));
        
        ?>
    </div>
</div>
</div>