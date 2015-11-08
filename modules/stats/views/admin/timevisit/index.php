<?php
$this->timefilter();
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => 'fluid')
));








if (isset($tmas)) {
    ?>

    <table class="tDefault tMedia">
        <thead>
            <tr>
                <th>№</th>
                <th>Время</th>
                <th><?php echo (($this->sort == "hi") ? "Хиты" : "Хосты") ?></th>
                <th>График</th>
                <th>%</th>
            </tr>
        </thead>
        <?php
        ksort($tmas);
        $mmx = max($tmas);
        $cnt = array_sum($tmas);
        $times = array();
        $test = array();
        foreach ($tmas as $tm => $val) {
            $k++;
            $vse += $val;
            echo "<tr>";

            echo "<td>$k</td>";
            if ($tm <> "23")
                $tm2 = $tm + 1; else
                $tm2 = "00";
            if (strlen($tm2) == 1)
                $tm2 = "0" . $tm2;
            $par = $tm . ':00 - ' . $tm2 . ':00';
            $times[] = $par;
            $test[] = array(
                'y' => (int) $val,
                'url' => "?pz=1&item=tm&s_date=" . StatsHelper::dtconv($this->sdate) . "&f_date=" . StatsHelper::dtconv($this->fdate) . "&qs=" . $tm . ":&sort=" . (empty($this->sort) ? "ho" : $this->sort)
            );
            echo "<td align=left><a target=_blank href=\"?pz=1&item=tm&s_date=" . StatsHelper::dtconv($this->sdate) . "&f_date=" . StatsHelper::dtconv($this->fdate) . "&qs=" . $tm . ":&sort=" . (empty($this->sort) ? "ho" : $this->sort) . "\">$tm:00 - $tm2:00</a></td>";
            echo "<td>$val</td>";
            echo "<td><img align=left src=/stats/px" . (($this->sort == "hi") ? "h" : "u") . ".gif width=" . ceil(($val * 100) / $mmx) . " height=11 border=0></td>";
            echo "<td>" . (number_format((($val * 100) / $cnt), 1, '.', '')) . "</td></tr>";
        }
        ?>

    </table>
    <?php
}else {
    Yii::app()->tpl->alert('info', 'Нет данных');
}

$this->Widget('ext.highcharts.HighchartsWidget', array(
            'scripts' => array(
             'columnrange', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
            'modules/exporting', // adds Exporting button/menu to chart
        'themes/grid'        // applies global 'grid' theme to all charts
        ),
    'options' => array(

        'chart' => array(
            'type' => 'column',
            'animation' => false,
            'backgroundColor'=> array(
                'linearGradient'=> array(0, 0, 0, 500),
                'stops'=> array(
                    array(0, 'rgb(255, 255, 255)'),
                    array(1, 'rgb(200, 200, 255)')
                )
            ),
        ),
                'credits'=> array(
            'enabled'=>  false
        ),
'exporting'=> array(
            'buttons'=> array(
                'contextButton'=> array(
                    'menuItems'=> array(array(
                        'text'=> 'Export to PNG (small)',
                        'onclick'=> 'js:function () {
                            this.exportChart({
                                width: 250
                            });
                        }'
                    ), array(
                        'text'=> 'Export to PNG (large)',
                        'onclick'=> 'js:function () {
                            this.exportChart();
                        }',
                        'separator'=> false
                    ))
                )
            )
        ),
        
        
        'title' => array('text' => 'Время посещение'),
        'subtitle' => array(
            'text' => 'График времени посещения с ' . $_GET['s_date'] . ' по ' . $_GET['f_date'] . ''
        ),
        'xAxis' => array(
            'categories' => $times
        ),
        'yAxis' => array(
            'title' => array('text' => null)
        ),
        'plotOptions' => array(
            'column' => array(
                'dataLabels' => array(
                    'enabled' => false,
                ),
            ),
            'series' => array(
                'cursor' => 'pointer',
                'point' => array(
                    'events' => array(
                        'click' => "js:function (e) {
                                console.log(this.options.test);
//location.href = this.options.url;
                            }"
                    )
                ),
                'marker' => array(
                    'lineWidth' => 1
                )
            )
        ),
        'series' => array(
            array('name' => (($this->sort == "hi") ? "Хиты" : "Хосты"), 'data' => $test),
        //array('name' => 'John', 'data' => array(5, 7, 3))
        )
    )
));




Yii::app()->tpl->closeWidget();
?>
