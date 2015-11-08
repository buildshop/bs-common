<?php
$data_before = $data->getDataBefore();
$data_after = $data->getDataAfter();
$currency = Yii::app()->currency->main->symbol;
?>
<tr>
    <td style="width: 250px">
        <a href="<?= Yii::app()->createUrl('/admin/users/default/update', array('id' => $data->user_id)) ?>"><?= $data->username ?></a>
        <br/>
        <span class="date"><?= $data->date_create ?></span>
    </td>
    <?php if (isset($data_before['changed'])) { ?>
        <td style="width: 400px">
            <?php
            echo Yii::t('CartModule.admin', 'HISTORY_UPDATE_PRODUCT', array('{name}' => $data_before['name'])) . '<br>';
            echo Yii::t('CartModule.admin', 'HISTORY_QUANTITY',array('{quantity}'=>$data_before['quantity']));
            ?>
        </td>
        <td style="width: 400px">
            <?php
            echo Yii::t('CartModule.admin', 'HISTORY_QUANTITY',array('{quantity}'=>$data_before['quantity']));
            ?>
        </td>
    <?php } elseif ($data_before['deleted']) { ?>
        <td style="width: 800px" colspan="2" class="bg-danger">
            <?php
            echo Yii::t('CartModule.admin', 'HISTORY_REMOVE_PRODUCT',array('{name}'=>$data_before['name'])). '<br>';
            echo Yii::t('CartModule.admin', 'HISTORY_PRICE',array('{price}'=>$data_before['price'],'{symbol}'=>$currency)) . '<br>';
            echo Yii::t('CartModule.admin', 'HISTORY_QUANTITY',array('{quantity}'=>$data_before['quantity']));
            ?>
        </td>
    <?php } else { ?>
        <td style="width: 800px" colspan="2" class="bg-success">
            <?php
            echo Yii::t('CartModule.admin', 'HISTORY_ADD_PRODUCT',array('{name}'=>$data_before['name'])) . '<br>';
            echo Yii::t('CartModule.admin', 'HISTORY_PRICE',array('{price}'=>$data_before['price'],'{symbol}'=>$currency)) . '<br>';
            echo Yii::t('CartModule.admin', 'HISTORY_QUANTITY',array('{quantity}'=>$data_before['quantity']));
            ?>
        </td>
    <?php } ?>
</tr>