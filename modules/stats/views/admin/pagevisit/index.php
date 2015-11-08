<?php
$this->timefilter();
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => 'fluid')
));




$this->widget('ext.adminList.GridView', array(//ext.adminList.GridView
    'dataProvider' => $dataProvider,
    'selectableRows' => false,
    'enableHeader' => false,
    'autoColumns' => false,

    'enablePagination' => true,
    'columns' => array(
        array(
            'name' => 'num',
            'header' => '№',
            'type' => 'raw',
        ),
        array(
            'name' => 'req',
            'header' => 'Посещаемая страница',
            'type' => 'raw',
        ),
        array(
            'name' => 'h',
            'header' => (($this->sort == "hi") ? "Хиты" : "Хосты"),
            'type' => 'raw',
        ),
        array(
            'name' => 'graphic',
            'header' => 'График',
            'type' => 'raw',
        ),
        array(
            'name' => 'pracent',
            'header' => '%',
            'type' => 'raw',
            'htmlOptions'=>array('class'=>'textL')
        ),
        array(
            'name' => 'detail',
            'header' => 'Дитали',
            'type' => 'raw',
            'htmlOptions'=>array('class'=>'textL')
        ),
    )
));




/*
if (isset($items)) {
    ?>

    <table class="tDefault tMedia">
        <thead>
            <tr>
                <th>№</th>
                <th>Посещаемая страница</th>
                <th><?php echo (($this->sort == "hi") ? "Хиты" : "Хосты") ?></th>
                <th>График</th>
                <th>%</th>
                <th>Дитали</th>
            </tr>
        </thead>
        <?php
                $vse = 0;
        $k = 0;

       foreach($items as $row){
            if ($k == 0)
                $max = $row['cnt'];
            if ($row['req'] == "")
                $row['req'] = "<font color=grey>неизвестно</font>";
            if ($k == $pos)
                break;
            $k++;
            $vse += $row['cnt'];

           echo "<tr>";

            echo "<td>$k</td>";
            echo "<td align=left style='overflow: hidden;text-overflow: ellipsis;'><a target=_blank href=\"" . $row['req'] . "\">" . $row['req'] . "</a></td>";
            echo "<td>" . $row['cnt'] . "</td>";
            echo "<td><img align=left src=/stats/px" . (($this->sort == "hi") ? "h" : "u") . ".gif width=" . ceil(($row['cnt'] * 100) / $max) . " height=11 border=0></td>";
            echo "<td>" . (number_format((($row['cnt'] * 100) / $cnt), 1, ',', '')) . "</td>";
            echo "<td><a class=d target=_blank href=\"?pz=1&tz=1&item=req&s_date=" . StatsHelper::dtconv($this->sdate) . "&f_date=" . StatsHelper::dtconv($this->fdate) . "&qs=" . urlencode($row['req']) . "&sort=" . (empty($this->sort) ? "ho" : $this->sort) . "\">&gt;&gt;&gt;</a></td></tr>";
        }
        ?>

    </table>
    <?php
}else {
    Yii::app()->tpl->alert('info', 'Нет данных');
}*/
Yii::app()->tpl->closeWidget();
?>
