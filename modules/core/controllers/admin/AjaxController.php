<?php

class AjaxController extends AdminController {

    public function actions() {
        return array(
            'widget.' => 'ext.adminList.EditGridColumnsWidget',
        );
    }

    public function actionGetCounters() {
        Yii::import('mod.cart.models.Order');
        Yii::import('mod.cart.models.ProductNotifications');
        Yii::import('mod.support.models.TicketMessage');
        $json = array();
        if (Yii::app()->hasModule('comments')) {
            $json['comments'] = (int) Comments::model()->waiting()->count();
        }
        $json['orders'] = (int) Order::model()->new()->count();
        $json['notify'] = (int) ProductNotifications::model()->count();
        $json['support'] = (int) TicketMessage::model()->count();
        echo CJSON::encode($json);
    }

    /*
     * Возможно не используется.
      public function actionUpdateGrid() {
      if (Yii::app()->request->isAjaxRequest) {
      $response = array();
      $modelClass = $_POST['modelClass'];
      $id = $_POST['pk'];
      $field = $_POST['field'];
      $q = $_POST['q'];
      $model = $modelClass::model()->findByPk($id);
      $model->$field = $q;
      if ($model->validate()) {
      $model->save(false, false);
      $response['message'] = 'saved';
      $response['value'] = $q;
      } else {
      $response['message'] = 'error validate';
      }
      echo CJSON::encode($response);
      } else {
      throw new CHttpException(403, 'no ajax');
      }
      }
     */

    public function actionDeleteFile() {
        $dir = $_POST['aliasDir'];
        $filename = $_POST['filename'];
        $model = $_POST['modelClass'];
        $record_id = $_POST['id'];
        $attr = $_POST['attribute'];
        $path = Yii::getPathOfAlias($dir);
        if (file_exists($path . DIRECTORY_SEPARATOR . $filename)) {
            unlink($path . DIRECTORY_SEPARATOR . $filename);
            $m = $model::model()->findByPk($record_id);
            $m->$attr = '';
            $m->save(false, false, false);
            echo CJSON::encode(array(
                'response' => 'success',
                'message' => Yii::t('core', 'FILE_SUCCESS_DELETE')
                    )
            );
        } else {
            echo CJSON::encode(array(
                'response' => 'error',
                'message' => Yii::t('core', 'ERR_FILE_NOT_FOUND')
                    )
            );
        }
    }

    public function actionCheckalias() {
        $model = $_POST['model'];
        $url = $_POST['alias'];
        $isNew = $_POST['isNew'];

        if (file_exists('mod.' . strtolower($model) . '.models.' . $model)) {
            Yii::import('mod.' . strtolower($model) . '.models.' . $model);
        }
        $criteria = new CDbCriteria();
        if (!empty($isNew)) {
            $criteria->condition = '`t`.`seo_alias`="' . $url . '" AND `t`.`id`!=' . $isNew;
        } else {
            $criteria->condition = '`t`.`seo_alias`="' . $url . '"';
        }
        $check = $model::model()->find($criteria);

        if (isset($check))
            echo 'true';
        else
            echo 'false';
    }

    public function actionGetStats() {
        $n = Stats::model()->findAll();
        echo CJSON::encode(array(
            'hits' => (int) count($n),
            'hosts' => (int) count($n),
        ));
    }

    public function actionAutocomplete() {
        $model = $_GET['modelClass'];
        $string = $_GET['string'];
        $field = $_GET['field'];
        $criteria = new CDbCriteria;
        $criteria->addSearchCondition('t.' . $field, $string);
        $results = $model::model()->findAll($criteria);

        $json = array();
        foreach ($results as $item) {
            $json[] = array(
                'label' => $item->title,
                'value' => $item->title,
                'test' => 'test.param'
            );
        }
        echo CJSON::encode($json);
    }

    public function actionSendMailForm() {
        Yii::import('core.models.MailForm');
        $model = new MailForm;
        $model->toemail = $_GET['mail'];
        $form = new CMSForm($model->config, $model);
        $this->renderPartial('_sendMailForm', array('form' => $form));
    }

}