

<?php

//print_r($model);$_GET['ShopProduct']['type_id']

//if ($model->type) {
    $chosen = array(); // Array of ids to enable chosen
    $attributes = $model->type->shopAttributes;

    if (empty($attributes))
        Yii::app()->tpl->alert('info',Yii::t('ShopModule.admin', 'Список свойств пустой'));
    else {
        foreach ($attributes as $a) {
            // Repopulate data from POST if exists
            if (isset($_POST['ShopAttribute'][$a->name]))
                $value = $_POST['ShopAttribute'][$a->name];
            else
                $value = $model->getEavAttribute($a->name);

            $a->required ? $required = ' <span class="required">*</span>' : $required = null;

            if ($a->type == ShopAttribute::TYPE_DROPDOWN) {
                $chosen[] = $a->getIdByName();

                $addOptionLink = CHtml::link(' +', '#', array(
                            'rel' => $a->id,
                            'data-name' => $a->getIdByName(),
                            'onclick' => 'js: return addNewOption($(this));',
                            'class' => 'bold',
                            'title' => Yii::t('ShopModule.admin', 'Создать опцию')
                        ));
            }else
                $addOptionLink = null;

            echo CHtml::openTag('div', array('class' => 'formRow'));
            echo '<div class="grid4">' . CHtml::label($a->attr_translate->title . $required, $a->name, array('class' => $a->required ? 'required' : '')) . '</div>';
            echo '<div class="grid6 rowInput eavInput" style="width:350px">' . $a->renderField($value) . '</div><div class="clear"></div>';
            echo CHtml::closeTag('div');
        }
 //   }

    // Enable chosen
    //$this->widget('application.modules.admin.widgets.schosen.SChosen', array('elements'=>$chosen));
}