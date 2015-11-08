<?php

class DefaultController extends AdminController {

    public function actions() {
        return array(
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
            'order' => array(
                'class' => 'ext.adminList.actions.SortingAction',
            ),
        );
    }

    public function actionIndex() {

        $this->pageName = 'Список голосований';
        $model = new Poll('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Poll']))
            $model->attributes = $_GET['Poll'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $this->pageName = 'Добавить';
        $model = new Poll;
        $choices = array();
        //$this->performAjaxValidation($model);


        if (isset($_POST['Poll'])) {
            $model->attributes = $_POST['Poll'];

            // Setup poll choices
            if (isset($_POST['PollChoice'])) {
                foreach ($_POST['PollChoice'] as $id => $choice) {
                    $pollChoice = new PollChoice;
                    $pollChoice->attributes = $choice;
                    $choices[$id] = $pollChoice;
                }
            }

            if ($model->save()) {
                // Save any poll choices too
                foreach ($choices as $choice) {
                    $choice->poll_id = $model->id;
                    $choice->save();
                }

                $this->redirect(array('update', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
            'choices' => $choices,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {

        $model = $this->loadModel($id);
        $this->pageName = 'Редактирование';
        $choices = $model->choices;
        //$this->performAjaxValidation($model);
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['remove'])) {

                $deleteModel = PollChoice::model()->findByPk($_POST['id']);
                $deleteModel->delete();
            }
        }

        if (isset($_POST['PollChoice']['update'])) {
            foreach ($_POST['PollChoice']['update'] as $key => $row) {
                $updateModel = PollChoice::model()->findByPk($key);
                if ($updateModel->validate()) {
                    $updateModel->name = $row;
                    $updateModel->save(false, false);
                } else {
                    echo '<script>alert("record no valid please edit this record");</script>';
                }
            }
        }

        if (isset($_POST['PollChoice']['create'])) {

            foreach ($_POST['PollChoice']['create'] as $key => $row) {
                $createModel = new PollChoice();
                //$createModel->attributes = $_POST['filter']['create'];
                $createModel->poll_id = $model->id;
                $createModel->name = $row;
                if ($createModel->validate()) {
                    //$createModel->value = $filter;

                    $createModel->save(false, false);
                } else {
                    echo '<script>alert("record no valid please edit this record");</script>';
                }
            }
        }


        if (isset($_POST['Poll'])) {
            $model->attributes = $_POST['Poll'];

            $model->save();


            //$this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array('model' => $model, 'choices' => $choices));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            if ($_GET['ajax'] == 'pollChoice-grid') {

                $this->loadChoiceDelete($id)->delete();
            } else {
                $this->loadModel($id)->delete();


                if (!isset($_GET['ajax']))
                    $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionView($id) {
        $model = $this->loadModel($id);

        if (Yii::app()->getModule('poll')->forceVote && $model->userCanVote()) {
            $this->redirect(array('vote', 'id' => $model->id));
        } else {
            $userVote = $this->loadVote($model);
            $userChoice = $this->loadChoice($model, $userVote->choice_id);

            $this->render('view', array(
                'model' => $model,
                'userVote' => $userVote,
                'userChoice' => $userChoice,
                'userCanCancel' => $model->userCanCancelVote($userVote),
            ));
        }
    }

    public function actionAjaxcreate() {
        if (Yii::app()->request->isPostRequest) {
            $choice = new PollChoice;
            $choice->name = $_POST['name'];

            $result = new stdClass();
            $result->id = $_POST['id'];
            $result->html = $this->renderPartial('_formChoice', array(
                'id' => $_POST['id'],
                'choice' => $choice,
                    ), TRUE);

            echo function_exists('json_encode') ? json_encode($result) : CJSON::encode($result);

            Yii::app()->end();
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function loadModel($id) {
        $model = Poll::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404);
        return $model;
    }

    public function loadChoiceDelete($id) {
        $model = PollChoice::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404);
        return $model;
    }

    public function loadChoice($poll, $choice_id) {
        if ($choice_id) {
            foreach ($poll->choices as $choice) {
                if ($choice->id == $choice_id)
                    return $choice;
            }
        }

        return new PollChoice;
    }

    public function loadVote($model) {
        $userId = (int) Yii::app()->user->id;
        $isGuest = Yii::app()->user->isGuest;

        foreach ($model->votes as $vote) {
            if ($vote->user_id == $userId) {
                if (Yii::app()->getModule('poll')->ipRestrict && $isGuest && $vote->ip_address != $_SERVER['REMOTE_ADDR'])
                    continue;
                else
                    return $vote;
            }
        }

        return new PollVote;
    }

    public function actionVote($id) {
        $model = $this->loadModel($id);
        $vote = new PollVote;

        if (!$model->userCanVote())
            $this->redirect(array('view', 'id' => $model->id));

        if (isset($_POST['PollVote'])) {
            $vote->attributes = $_POST['PollVote'];
            $vote->poll_id = $model->id;
            if ($vote->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        // Convert choices to form options list
        $choices = array();
        foreach ($model->choices as $choice) {
            $choices[$choice->id] = CHtml::encode($choice->name);
        }

        $this->render('vote', array(
            'model' => $model,
            'vote' => $vote,
            'choices' => $choices,
        ));
    }

    public function actionClear($id) {
        // $model = $this->loadModel($id);
        PollChoice::model()->updateAll(array('votes' => 0), 'poll_id="' . $id . '"');
        PollVote::model()->deleteAll("poll_id ='" . $id . "'");
    }

}
