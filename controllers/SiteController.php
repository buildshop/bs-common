<?php

class SiteController extends Controller {

    public function actionError() {
        $error = Yii::app()->errorHandler->error;
        $this->layout = '//layouts/error';
        if ($error) {
            $this->pageTitle = Yii::t('default', 'ERROR') . ' ' . $error['code'];
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('//layouts/_error', array('error' => $error));
            }
        }
    }

}



?>
