<?php

class HistoryController extends AdminController {

    public $topButtons = false;

    public function actions() {
        return array(
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
        );
    }

    public function actionIndex() {
        $model = new OrderProduct('search');
        $form = new OrderProductHistoryForm;
        $buttons = array();
        $buttons[] = array(
            'label' => Yii::t('CartModule.admin', 'HISTORY_RESET'),
            'url' => '/admin/cart/history',
            'htmlOptions' => array('class' => 'buttonS bBlue')
        );
        if (Yii::app()->hasComponent('pdf')) {
            $buttons[] = array(
                'label' => Yii::t('CartModule.admin', 'SAVE_PDF'),
                'url' => '#',
                'htmlOptions' => array('class' => 'btn btn-success', 'id' => 'save_pdf', 'target' => '_blank')
            );
        }

        $this->topButtons = $buttons;
        $this->pageName = Yii::t('CartModule.admin', 'ORDERED_PRODUCTS');
        if (!empty($_GET['OrderProduct']))
            $model->attributes = $_GET['OrderProduct'];

        if (isset($_GET['OrderProductHistoryForm'])) {
            $form->attributes = $_GET['OrderProductHistoryForm'];

            if ($form->validate()) {
                //$this->setFlashMessage(Yii::t('core', 'OK'));
            } else {
                print_r($form->getErrors());
                $this->setFlashMessage(Yii::t('core', 'NO VALID'));
            }
        }
        $this->render('index', array(
            'model' => $model,
            'form' => $form,
        ));
    }

    /*
      public function actionNew() {
      $model = new OrderProduct('search');

      $form = new OrderProductHistoryForm;

      $this->pageName = Yii::t('CartModule.admin', 'Заказанные продукты');
      if (!empty($_GET['OrderProduct']))
      $model->attributes = $_GET['OrderProduct'];

      if (isset($_GET['OrderProductHistoryForm'])) {
      $form->attributes = $_GET['OrderProductHistoryForm'];

      if ($form->validate()) {
      //$this->setFlashMessage(Yii::t('core', 'OK'));
      } else {
      print_r($form->getErrors());
      $this->setFlashMessage(Yii::t('core', 'NO VALID'));
      }
      }
      $this->render('index', array(
      'model' => $model,
      'form' => $form,
      ));
      }
     */

    /**
     * Дополнительное меню Контроллера.
     * @return array
     */
    public function getAddonsMenu() {
        return array(
            array(
                'label' => Yii::t('CartModule.admin', 'STATUSES'),
                'url' => Yii::app()->createUrl('/admin/cart/statuses'),
                'icon' => 'icon-plus',
                'visible' => Yii::app()->user->isSuperuser
            ),
            array(
                'label' => Yii::t('CartModule.admin', 'STATS'),
                'url' => Yii::app()->createUrl('/admin/cart/statistics'),
                'icon' => 'icon-stats',
                'visible' => Yii::app()->user->isSuperuser
            ),
            array(
                'label' => Yii::t('CartModule.admin', 'ORDER',0),
                'url' => Yii::app()->createUrl('/admin/cart'),
                'icon' => 'icon-cart-3',
                'visible' => Yii::app()->user->isSuperuser
            ),
        );
    }

}
