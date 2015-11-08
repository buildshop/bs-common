<?php

class DefaultController extends Controller {

    public function actionIndex() {
        $model = Poll::model()->open()->findAll();
        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionView($id) {
        Yii::app()->clientScript->scriptMap = array('jquery.js' => false);
        $model = Poll::model()->findByPk($id);
        if ($model) {
            $fn = new CPollHelper($model);
          
            $params = array('model' => $model);
            if (isset($_POST['PortletPollVote_choice_id'])) {
                foreach ($_POST['PortletPollVote_choice_id'] as $ids) {
                    $userVote = new PollVote;
                    $userVote->choice_id = $ids;
                    $userVote->poll_id = $model->id;
                    if ($userVote->validate()) {
                        $userVote->save(false, false);
                    } else {
                        die('err');
                    }
                }
            }
              $userVote = $fn->loadVote();
            if (Yii::app()->settings->get('poll', 'is_force') && $model->userCanVote()) {
                if (Yii::app()->request->isAjaxRequest) {
                    $this->widget('ext.uniform.UniformWidget', array(
                        'theme' => 'default',
                    ));
                    $userVote->addError('choise_id', 'Тыкни ты уже кудато!!');
                    $view = 'poll.widgets.random.views.vote';
                } else {
                    $view = 'vote';
                }

                // Convert choices to form options list
                $choices = array();
                foreach ($model->choices as $choice) {
                    $choices[$choice->id] = Html::encode($choice->name);
                }
                $params['choices'] = $choices;
            } else {
                if (Yii::app()->request->isAjaxRequest) {
                    $view = 'poll.widgets.random.views.view';
                } else {
                    $view = 'view';
                }
                $userChoice = $fn->loadChoice($userVote);

                $params += array(
                    'userVote' => $userVote,
                    'userChoice' => $userChoice
                );
            }

            $this->render($view, $params, false, true);
        }
    }




}