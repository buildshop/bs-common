
<style>
    #container-contancts{
        position: relative;
        min-height: 990px;
    }
    #container-contancts #map,
    #container-contancts .info{

        text-align: center;
    }
    #container-contancts #map{
        z-index: 1;
        position: absolute;
    }
    #container-contancts .info{
        background: #fff;
        padding:20px 40px;
        width: 325px;
        position: absolute;
        margin-top: 30px;
    }
    #container-contancts .info b{
        font-size:20px;
    }
    #container-contancts .info span{
        color:#919191;
        font-size:13px;
    }
    #container-contancts .info p {
        font-size:16px;
        color:#4f4d4a;
        margin: 30px 0;
    }
    #container-contancts .info form{
        margin-top: 30px;
    }
    #container-contancts .info .phone{
        color:#32ca77;
        margin-bottom: 5px;
    }
    #container-contancts .info .phone:last-child{
        margin-bottom: 0;
    }
</style>


<h1>Контактная информация</h1>

<div id="container-contancts">
    <?php //$this->widget('mod.contacts.widgets.offices.OfficeWidget');  ?>
    <?php $this->widget('mod.contacts.widgets.map.MapWidget'); ?>
    <div class="indent2" style="position:relative;z-index: 2;top: 40px;">
        
        
    <div class="info">
        <b>Мы находимся по адресу</b>
        <p>
            <?= $this->oneoffice->address?>
        </p>
        <span>звоните нам</span>
        <?php
        foreach (explode(',', $this->oneoffice->phones) as $phone) { ?>
    <div class="phone"><?= $phone ?></div>

      <?php } ?>
    </div>
    <div class="info" style="top:255px;">
                <h3>Написать нам</h3>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'contact_form',
            'clientOptions' => array(
                'validateOnSubmit' => true
            ),
            'htmlOptions' => array('name' => 'contact_form', 'class' => 'form fluid')
                ));
        ?>


        <?php
        if ($model->hasErrors())
            Yii::app()->tpl->alert('failure', Html::errorSummary($model));
        ?>
        <div class="row">
            <div class="grid12"><?= $form->labelEx($model, 'name'); ?><br/><?= $form->textField($model, 'name'); ?></div>
            <div class="clear"></div>
        </div>
        <div class="row">
            <div class="grid12"><?= $form->labelEx($model, 'email'); ?><br/><?= $form->textField($model, 'email'); ?></div>
            <div class="clear"></div>
        </div>
        <div class="row">
            <div class="grid12"><?= $form->labelEx($model, 'msg'); ?><br/><?= $form->textArea($model, 'msg'); ?></div>
            <div class="clear"></div>
        </div>
                <div class="row">
                    <div class="grid6"><?php
        $this->widget('CCaptcha', array(
            'clickableImage' => true,
            'showRefreshButton' => false,
        ))
        ?></div>
            <div class="grid6"><?php echo Html::activeLabelEx($model, 'verifyCode') ?><br/><?php echo CHtml::activeTextField($model, 'verifyCode') ?></div>
            <div class="clear"></div>
        </div>

                <div class="row">
            <div class="grid12"><?= Html::submitButton(Yii::t('default', 'SEND'), array('class' => 'btn btn-default')); ?></div>
            <div class="clear"></div>
        </div>


<?php $this->endWidget(); ?>
    </div>
        
        
        
        
        
        
        
        
    </div>

</div>




