
<?php

  foreach ($model as $key=>$row) {
    echo $row->title;
echo CHtml::link($row->title, '/poll/view'.$row->id);
  }
?>

