<?php
Yii::app()->getClientScript()->registerScriptFile($this->module->assetsUrl . '/admin/products.variations.js', CClientScript::POS_END);
?>
<div class="clearfix"></div>
<div class="variants">
    <div class="formRow">
        <div class="grid2"><label>Добавить атрибут</label></div>
        <div class="grid10">     
            <?php
            if ($model->type) {
                $attributes = $model->type->shopConfigurableAttributes;
                echo CHtml::dropDownList('variantAttribute', null, Html::listData($attributes, 'id', 'title'));
            }
            ?>
            <a href="javascript:void(0)" id="addAttribute" class="btn btn-success">Добавить</a>
        </div>

    </div>




    <div id="variantsData">
        <?php
        foreach ($model->processVariants() as $row) {
            $this->renderPartial('variants/_table', array(
                'attribute' => $row['attribute'],
                'options' => $row['options']
            ));
        }
        ?>
    </div>
</div>