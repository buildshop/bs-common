<?php
$this->timefilter();
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => 'fluid')
));

if(isset($bmas)){ 
?>

<table class="tDefault tMedia">
    <thead>
    <tr>
        <th>№</th>
        <th>Браузер</th>
        <th><?php echo (($this->sort == "hi") ? "Хиты" : "Хосты")?></th>
        <th>График</th>
        <th>%</th>
        <th>Дитали</th>
    </tr>
    </thead>
<?php

    
            arsort($bmas);
        $mmx = max($bmas);
        $cnt = array_sum($bmas);
      foreach($bmas as $brw=>$val){
        // while (list($brw, $val) = each($bmas)) {
            $k++;
            $vse += $val;

                echo "<tr>";

            echo "<td>$k</td>";
            echo "<td align=left>";
            if (!empty($brw))
                echo "<img src=/stats/browsers/$brw width=16 height=16 align=absmiddle> ";
            echo "<a target=_blank href=\"?tz=4&pz=1&item=user&s_date=" . StatsHelper::dtconv($this->sdate) . "&f_date=" . StatsHelper::dtconv($this->fdate) . "&qs=" . $brw . "&sort=" . (empty($this->sort) ? "ho" : $this->sort) . "\">";
            switch ($brw) {
                case "ie.png": echo "MS Internet Explorer";
                    break;
                case "opera.png": echo "Opera";
                    break;
                case "firefox.png": echo "Firefox";
                    break;
                case "chrome.png": echo "Google Chrome";
                    break;
                case "mozilla.gif": echo "Mozilla";
                    break;
                case "safari.png": echo "Apple Safari";
                    break;
                case "mac.gif": echo "Macintosh";
                    break;
                case "maxthon.png": echo "Maxthon (MyIE)";
                    break;
                default: echo "другие";
                    break;
            }
            echo "</a></td>";
            echo "<td>$val</td>";
            echo "<td><img align=left src=/stats/px" . (($this->sort == "hi") ? "h" : "u") . ".gif width=" . ceil(($val * 100) / $mmx) . " height=11 border=0></td>";
            echo "<td>" . (number_format((($val * 100) / $cnt), 1, ',', '')) . "</td>";
            echo "<td><a class=d target=_blank href=\"/admin/stats/browsers/view?pos=10&s_date=" . StatsHelper::dtconv($this->sdate) . "&f_date=" . StatsHelper::dtconv($this->fdate) . "&brw=" . (empty($brw) ? "другие" : $brw) . "&sort=" . (empty($this->sort) ? "ho" : $this->sort) . "\">&gt;&gt;&gt;</a></td></tr>";
        }

?>
 
</table>
<?php 
}else{
    Yii::app()->tpl->alert('info','Нет данных');
}
Yii::app()->tpl->closeWidget(); 

?>
