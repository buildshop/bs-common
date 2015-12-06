
<?php

$chosen = array(); // Array of ids to enable chosen
$attributes = $model->type->shopAttributes;

if (empty($attributes))
    Yii::app()->tpl->alert('info', Yii::t('ShopModule.admin', 'Список свойств пустой'),false);
else {
    foreach ($attributes as $a) {

        if (isset($_POST['ShopAttribute'][$a->name]))
            $value = $_POST['ShopAttribute'][$a->name];
        else
            $value = $model->getEavAttribute($a->name);

        $a->required ? $required = ' <span class="required">*</span>' : $required = null;

        if ($a->type == ShopAttribute::TYPE_DROPDOWN) {
            $chosen[] = $a->getIdByName();

            $addOptionLink = CHtml::link('Создать опцию', '#', array(
                        'rel' => $a->id,
                        'data-name' => $a->getIdByName(),
                        'onclick' => 'js: return addNewOption($(this));',
                        'class' => 'btn btn-success btn-xs',
                        'title' => Yii::t('ShopModule.admin', 'Создать опцию')
            ));
        } else
            $addOptionLink = null;

        echo CHtml::openTag('div', array('class' => 'form-group'));
        echo '<div class="col-sm-4">' . CHtml::label($a->attr_translate->title . $required, $a->name, array('class' => $a->required ? 'required' : '')) . '</div>';
        echo '<div class="col-sm-8 rowInput eavInput" style="width:350px">' . $a->renderField($value) .' '. $addOptionLink.'</div>';
        echo CHtml::closeTag('div');
    }

}