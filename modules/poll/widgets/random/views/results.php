<div class="poll-results">
    <?php
    $colors = array(
        'progress-bar-info',
        'progress-bar-success',
        'progress-bar-warning',
        'progress-bar-danger'
    );
    foreach ($model->choices as $key => $choice) {
        $this->render('mod.poll.widgets.random.views._resultsChoice', array(
            'choice' => $choice,
            'color' => $colors[$key],
            'percent' => $model->totalVotes > 0 ? 100 * round($choice->votes / $model->totalVotes, 3) : 0,
            'voteCount' => $choice->votes,
        ));
    }
    ?>
</div>
