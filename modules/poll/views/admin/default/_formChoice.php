
    <?php //echo CHtml::activeTextField($choice,"[$id]name",array('size'=>60,'maxlength'=>255)); ?>
    <?php echo CHtml::error($choice,"[$id]name"); ?>

  <?php
    $deleteJs = 'jQuery("#pollchoice-'. $id .'").find("td").fadeOut(1000,function(){jQuery(this).parent().remove();});return false;';

    if (isset($choice->id)) {
      // Add AJAX delete link
      echo CHtml::ajaxLink(
        'Delete',
        array('/poll/pollchoice/delete', 'id' => $choice->id, 'ajax' => TRUE),
        array('type' => 'POST', 'success' => 'js:function(){'. $deleteJs .'}'),
        array('confirm' => 'Are you sure you want to delete this item?')
      );
    }
    else {
      // Model hasn't been created yet, so just remove the DOM element
      echo CHtml::link('Delete', '#', array('onclick' => 'js:'. $deleteJs));
    }
    // Add additional hidden fields
    echo CHtml::activeHiddenField($choice,"[$id]id");
  ?>
