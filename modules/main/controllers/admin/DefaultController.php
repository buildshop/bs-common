<?php

class DefaultController extends AdminController {



    /**
     * @return array action filters
     */
    public function actions() {
        return array(
            'order' => array(
                'class' => 'ext.yiisortablemodel.actions.AjaxSortingAction',
            ),
        );
    }

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {

        $model = new News;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['News'])) {
            $model->attributes = $_POST['News'];
            if ($model->validate()) {
                $query = Yii::app()->db->createCommand()
                        ->select('max(`ordern`)')
                        ->from('news')
                        ->queryAll();
                $order = $query[0]['max(`ordern`)'];
                $model->ordern = $order + 1;


                $fileU = CUploadedFile::getInstance($model, 'logo');

                if (isset($fileU)) {
                    $file = PFunction::translit($fileU);
                    $type = strtolower(end(explode('.', $_FILES['News']['name']['logo'])));

                    $newname = CMS::gen(7) . "." . $type;

                    if ($file) {

                        ImageProcessing::image()
                                ->saveImage($fileU, $newname, array(
                                    'width' => 290, 'height' => 110, 'maindir' => '/uploads/news/', 'overwrite' => true));
                        $model->logo = $newname;
                    }
                } else {
                    
                }

                $model->save();





                
                               Yii::app()->user->setFlash('success', Yii::t('admin', 'Success'));
                               $this->redirect(array('index'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('admin', 'Error'));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if (isset($_POST['News'])) {
            $model->attributes = $_POST['News'];
            if ($model->validate()) {
                $model->save();
                 $this->setFlashMessage(Yii::t('core', 'Changes saved successfully'));
                $this->redirect(array('index'));

            } else {
                Yii::app()->user->setFlash('error', Yii::t('admin', 'Error'));
            }
        }
        $this->render('update', array(
            'model' => $model,
        ));
    }

	/**
	 * Status page by Pk
	 */
	public function actionUpdate_switch()
	{
		if (Yii::app()->request->isPostRequest)
		{
			$model = News::model()->findAllByPk($_REQUEST['id']);

			if (!empty($model))
			{
				foreach($model as $page)
					$page->updateByPk($_REQUEST['id'], array('status'=>$_REQUEST['status']));
			}

			if (!Yii::app()->request->isAjaxRequest)
				$this->redirect('index');
		}
	}
        
    public function actionDelete($id) {

        $this->loadModel($id)->delete();
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }


    /**
     * Manages all models.
     */
    public function actionIndex() {
        $model = new News('search');
        $model->unsetAttributes();  // clear any default values    
        if (isset($_GET['News'])) {
            $model->attributes = $_GET['News'];
        }
        if (isset($_POST['action'])) {
            if ($_POST['action'] == 'status_1') {
                foreach ($_POST['checked'] as $ids) {
                    $modelStatus1 = $this->loadModel($ids);
                    if ($modelStatus1->validate()) {
                        $modelStatus1->status = 1;
                        $modelStatus1->save();
                    } else {
                        echo '<script>alert("record no valid please edit this record");</script>';
                    }
                }
            }
            if ($_POST['action'] == 'status_0') {
                foreach ($_POST['checked'] as $ids) {
                    $modelStatus0 = $this->loadModel($ids);
                    if ($modelStatus0->validate()) {
                        $modelStatus0->status = 0;
                        $modelStatus0->save();
                    } else {
                        echo '<script>alert("record no valid please edit this record");</script>';
                    }
                }
            }

            $this->deleteItem();
        }

           $this->render('admin', array('model' => $model));

    }

    private function deleteItem() {
        if ($_POST['action'] == 'delete') {
            if (is_array($_POST['checked'])) {
                foreach ($_POST['checked'] as $ids) {
                    $model = $this->loadModel($ids);
                    $model->deleteByPk($ids);
                }
            } else {
                $id = intval($_POST['checked']);
                $model2 = $this->loadModel($id);
                $model2->deleteByPk($id);
            }
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Clients the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = News::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404);
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Clients $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'news-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function assignAndRender($view, $params = array()) {
        $this->assignControllerJsCss('admin', true, true, array(
                ), array(
            'new/plugins/tables/jquery.dataTables.js',
            'new/plugins/tables/jquery.sortable.js',
            'new/plugins/tables/jquery.resizable.js',
            'new/plugins/forms/jquery.uniform.js',
            'new/plugins/forms/jquery.chosen.min.js',
            'new/files/init_uniform.js',
            'new/files/init_checkboxAll.js',
            'translitter.js',
            'init_translitter.js',
            'actionList.js',
                )
        );
        $this->render($view, $params);
    }

}
