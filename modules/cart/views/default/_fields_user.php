<?php //echo Html::errorSummary($this->form);      ?>

<div class="form-group">
    <?= Html::activeLabel($form, 'name', array('required' => true, 'class' => 'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
        <?= Html::activeTextField($form, 'name', array('class' => 'form-control')); ?>
    </div>
</div>
<div class="form-group">
    <?= Html::activeLabel($form, 'email', array('required' => true, 'class' => 'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
        <?= Html::activeTextField($form, 'email', array('class' => 'form-control')); ?>
    </div>
</div>
<div class="form-group">
    <?= Html::activeLabel($form, 'phone', array('required' => true, 'class' => 'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
        <?= Html::activeTextField($form, 'phone', array('class' => 'form-control')); ?>
    </div>
</div>
<div class="form-group">
    <?= Html::activeLabel($form, 'address', array('required' => true, 'class' => 'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
        <?= Html::activeTextField($form, 'address', array('class' => 'form-control')); ?>
    </div>
</div>
<?php if(Yii::app()->user->isGuest){ ?>
<div class="form-group">
    <?= Html::activeLabel($form, 'registerGuest', array('required' => true, 'class' => 'col-sm-4 control-label')); ?>
    <div class="col-sm-8">
        <?= Html::activeCheckBox($form, 'registerGuest', array('class' => 'form-control')); ?>
    </div>
</div>
<?php } ?>
<div class="hint">Поля отмеченные <span class="required">*</span> обязательны для заполнения</div>

