

<table class="variantsTable table table-striped" id="variantAttribute<?php echo $attribute->id ?>">
    <thead>
        <tr>
            <td colspan="6">
                <h4><?php echo Html::encode($attribute->title); ?></h4>
                <?php
                echo Html::link('Добавить опцию', '#', array(
                    'rel' => $attribute->id,
                    'class' => 'btn btn-xs btn-success',
                    'onclick' => 'js: return addNewOption($(this));',
                    'data-name' => $attribute->getIdByName(),
                ));
                ?>
            </td>
        </tr>
        <tr>
            <td>Значение</td>
            <td>Цена</td>
            <td>Тип цены</td>
            <td>Артикул</td>
            <td class="text-center"><?php
                echo Html::link('<span class="flaticon-add"></span>', '#', array(
                    'rel' => '#variantAttribute' . $attribute->id,
                    'class' => 'plusOne btn btn-success btn-xs',
                    'onclick' => 'js: return cloneVariantRow($(this));'
                ));
                ?></td>
        </tr>
    </thead>
    <tbody>
<?php if (!isset($options)) { ?>
            <tr>
                <td>
                    <?= Html::dropDownList('variants[' . $attribute->id . '][option_id][]', null, CHtml::listData($attribute->options, 'id', 'value'), array('class' => 'options_list select')); ?>
                </td>
                <td>
                    <input type="text" name="variants[<?php echo $attribute->id ?>][price][]">
                </td>
                <td>
                    <?= Html::dropDownList('variants[' . $attribute->id . '][price_type][]', null, array(0 => 'Фиксированная', 1 => 'Процент')); ?>
                </td>
                <td>
                    <input type="text" name="variants[<?php echo $attribute->id ?>][sku][]" />
                </td>
                <td>
                    <a href="javascript:void(0)" class="btn btn-danger btn-xs" onclick="return deleteVariantRow($(this));"><i class="flaticon-delete"></i></a>
                </td>
            </tr>
        <?php } ?>
        <?php
        if (isset($options)) {
            foreach ($options as $o) {
                ?>
                <tr>
                    <td>
                        <?= Html::dropDownList('variants[' . $attribute->id . '][option_id][]', $o->option->id, CHtml::listData($attribute->options, 'id', 'value'), array('class' => 'options_list')); ?>
                    </td>
                    <td>
                        <input type="text" name="variants[<?php echo $attribute->id ?>][price][]" value="<?php echo $o->price ?>">
                    </td>
                    <td>
        <?php echo CHtml::dropDownList('variants[' . $attribute->id . '][price_type][]', $o->price_type, array(0 => 'Фиксированная', 1 => 'Процент')); ?>
                    </td>
                    <td>
                        <input type="text" name="variants[<?php echo $attribute->id ?>][sku][]" value="<?php echo $o->sku ?>">
                    </td>
                    <td class="text-center">

                        <a href="javascript:void()" class="btn btn-danger btn-xs" onclick="return deleteVariantRow($(this));"><i class="flaticon-delete"></i></a>

                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>