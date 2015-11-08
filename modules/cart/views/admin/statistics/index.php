<?php
$typeMonth = 2;
$monthArray = array(
    1 => Yii::t('month', 'January', $typeMonth),
    2 => Yii::t('month', 'February', $typeMonth),
    3 => Yii::t('month', 'March', $typeMonth),
    4 => Yii::t('month', 'April', $typeMonth),
    5 => Yii::t('month', 'May', $typeMonth),
    6 => Yii::t('month', 'June', $typeMonth),
    7 => Yii::t('month', 'July', $typeMonth),
    8 => Yii::t('month', 'August', $typeMonth),
    9 => Yii::t('month', 'September', $typeMonth),
    10 => Yii::t('month', 'October', $typeMonth),
    11 => Yii::t('month', 'November', $typeMonth),
    12 => Yii::t('month', 'December', $typeMonth)
        )
?>

<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => '')
));
?>

<div class="widget fluid">
    <div class="whead">
        <h6>Количество заказов по дням</h6>

        <?php echo Html::form(array('/admin/cart/statistics'), 'GET'); ?>
        Год: <?= Html::dropDownList('year', $year, $this->getAvailableYears(), array('onchange' => 'this.form.submit()','class'=>'selectpicker','data-width'=>'auto')) ?>
        Месяц: <?= Html::dropDownList('month', $month, $monthArray, array('onchange' => 'this.form.submit()','class'=>'selectpicker','data-width'=>'auto')) ?>
        <?php echo Html::endForm(); ?>
        <div class="clear"></div>

    </div>
    <?php
    $this->widget('ext.highcharts.HighchartsWidget', array(
        'scripts' => array(
            'highcharts-more', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
            'modules/exporting', // adds Exporting button/menu to chart
        //  'themes/grid'        // applies global 'grid' theme to all charts
        ),
        'options' => array(
            'chart' => array(
                'type' => 'column',
                'plotBackgroundColor' => null,
                'plotBorderWidth' => null,
                'plotShadow' => false,
                'backgroundColor' => 'rgba(255, 255, 255, 0)'
            ),
            'title' => array('text' => $this->pageName),
            'xAxis' => array(
                'categories' => range(1, cal_days_in_month(CAL_GREGORIAN, $month, $year))
            ),
            'yAxis' => array(
                'title' => array('text' => $this->pageName)
            ),
            'plotOptions' => array(
                'areaspline' => array(
                    'fillOpacity' => 0.5
                )
            ),
            'tooltip' => array(
                'shared' => true,
            //  'valueSuffix' => ' кол.'
            ),
            'series' => array(
                array('name' => 'Сумма заказов', 'data' => $data_total),
                array('name' => 'Заказы', 'data' => $data),
            ),
            'credits' => array(
                'enabled' => false
            )
        )
    ));
    ?>

</div>
<?php Yii::app()->tpl->closeWidget();?>