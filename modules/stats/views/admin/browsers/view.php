<?php



















        $this->timefilter();







Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => 'fluid')
));


?>

<table class="tDefault tMedia">
    <tr>
        <th>№</th>
        <th>User-Agent <?= $browserName;?></th>
        <th><?php echo (($this->sort == "hi") ? "Хиты" : "Хосты")?></th>
        <th>График</th>
        <th>%</th>
    </tr>

    <?php
    foreach ($items as $row) {

        if ($row['user'] == "")
            $row['user'] = "<font color=grey>неизвестно</font>";
        if ($k == 0)
            $max = $row['cnt'];
        if ($k == $pos)
            break;
        $k++;
        $vse += $row['cnt'];
        echo '<tr>';
        echo "<td>$k</td>";
        echo "<td align=left style='overflow: hidden;text-overflow: ellipsis;'><a target=_blank href=\"?tz=1&pz=1&item=user&s_date=" . StatsHelper::dtconv($this->sdate) . "&f_date=" . StatsHelper::dtconv($this->fdate) . "&qs=" . $row['user'] . "&sort=" . (empty($this->sort) ? "ho" : $this->sort) . "\">" . $row['user'] . "</a></td>";
        echo "<td>" . $row['cnt'] . "</td>";
        echo "<td><img align=left src=/stats/px" . (($this->sort == "hi") ? "h" : "u") . ".gif width=" . ceil(($row['cnt'] * 100) / $max) . " height=11 border=0></td>";
        echo "<td>" . (number_format((($row['cnt'] * 100) / $cnt), 1, ',', '')) . "</td>";
        echo "</tr>";
    }
    ?>

</table>
<?php Yii::app()->tpl->closeWidget(); ?>
