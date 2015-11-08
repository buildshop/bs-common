
<?php
if(isset($items)){
foreach ($items as $item) {
    echo Html::link($item['label'], $item['url']) .' ';
}
}
?>
