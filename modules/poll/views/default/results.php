<div class="poll-results">
<?php
    $colors = array('barG','barR','barB','barO','barBl','barGr');
  foreach ($model->choices as $key=>$choice) {
    $this->renderPartial('poll.views.default._resultsChoice', array(
      'choice' => $choice,
	'color'=>$colors[$key],
      'percent' => $model->totalVotes > 0 ? 100 * round($choice->votes / $model->totalVotes, 3) : 0,
      'voteCount' => $choice->votes,
    ));
  }
?>
</div>
