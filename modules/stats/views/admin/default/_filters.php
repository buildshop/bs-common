<?php
Yii::app()->tpl->openWidget(array(
    'title' => 'filters',
    'htmlOptions' => array('class' => 'fluid')
));
?>

<table align=center width=750 cellpadding=5 cellspacing=1 border=0>
    <tr class=h>
        <td width=100% valign=middle><form style="margin:0px;padding:0px;" action="" method="get">
                <span style="vertical-align: middle;">Фильтр: &nbsp;
                с 
<?php

$this->widget('zii.widgets.jui.CJuiDatePicker',array(
    'name'=>'s_date',
   'value'=>$this->sdate,
    // additional javascript options for the date picker plugin
    'options'=>array(
        'dateFormat' => 'yymmdd',
'defaultdate'=>'12.02.2012'
    ),
    'htmlOptions'=>array(
        
    ),
));
?>
                до 
                
<?php

$this->widget('zii.widgets.jui.CJuiDatePicker',array(
    'name'=>'f_date',
   'value'=>$this->fdate,
    // additional javascript options for the date picker plugin
    'options'=>array(
        'dateFormat' => 'yymmdd',

    ),
    'htmlOptions'=>array(

    ),
));
?>
                
                
              
<?php if (isset($_GET['engin'])) { ?>
                    &nbsp;<span style="vertical-align: middle;">кол-во поз.</span> <input type=text name="pos" value="<?php if ($_GET['pos'] == 99999) echo ""; else echo $_GET['pos']; ?>">
                <?php } ?>
                <?php if (!isset($_GET['domen'])) { ?>
                    &nbsp;<span style="vertical-align: middle;">сорт.</span> <select name="sort">
                        <option value="ho" <?php if ($this->sort == "ho" or $this->sort != "hi") echo "selected"; ?>>Хосты</option>
                        <option value="hi" <?php if ($this->sort == "hi") echo "selected"; ?>>Хиты</option>
                    </select>
<?php } ?>
                <?php if ($_GET['engin']) echo "<input name='engin' value=" . $_GET['engin'] . " type='hidden'>"; ?>
                <?php if ($_GET['domen']) echo "<input name='domen' value=" . $_GET['domen'] . " type='hidden'>"; ?>
                <?php if ($_GET['brw']) echo "<input name='brw' value='" . $_GET['brw'] . "' type='hidden'>"; ?>
                <?php if ($_GET['qq']) echo "<input name='qq' value='" . $_GET['qq'] . "' type='hidden'>"; ?>
                <?php if (isset($_GET['domen']) or !empty($_GET['engin'])) { ?>
                    &nbsp;<span>строка</span> <input type=text name="str_f"  value="<?php if ($_GET['str_f']) echo $_GET['str_f']; ?>">
                <?php } ?>
                <input class="buttonS bGreen" type=submit value="Показать!">
            </form></td>
    </tr>
</table>
<?php Yii::app()->tpl->closeWidget(); ?>


