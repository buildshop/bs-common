<?php

Yii::import('mod.poll.widgets.BasePollWidget');

class RandomWidget extends BasePollWidget {

    public function renderContent() {
        Yii::import('poll.components.CPollHelper');
        $model = $this->_poll;

        if ($model) {
            $fn = new CPollHelper($model);
            $userVote = $fn->loadVote();
            $params = array('model' => $model, 'userVote' => $userVote);

            // Force user to vote if needed
            if (Yii::app()->settings->get('poll', 'is_force') && $model->userCanVote()) {
                $view = 'vote';

                // Convert choices to form options list
                $choices = array();
                foreach ($model->choices as $choice) {
                    $choices[$choice->id] = Html::encode($choice->name);
                }
                $params['choices'] = $choices;
            } else {
                $view = 'view';
                $userChoice = $fn->loadChoice($userVote);

                $params += array(
                    'userVote' => $userVote,
                    'userChoice' => $userChoice,
                );
            }
            $this->render($view, $params);
        }
    }

}
