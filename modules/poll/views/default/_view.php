<?php $this->renderPartial('results', array('model' => $model)); ?>

<?php if ($userVote->id): ?>
  <p id="pollvote-<?php echo $userVote->id ?>">
      

    Ваш голос: <strong><?php echo $userChoice->name ?></strong>.
  </p>



<?php else: ?>
  <p><?php echo CHtml::link('Vote', array('/poll/poll/vote', 'id' => $model->id)); ?></p>
<?php endif; ?>


