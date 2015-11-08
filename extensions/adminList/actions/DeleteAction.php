<?php

/**
 * Это действие вызывается при adminList виджета для удаление записей или записи.
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package widgets.adminList.actions
 * @uses CAction
 */
class DeleteAction extends CAction {

    /**
     * @var string 
     */
    public $model;

    /**
     * Запустить действие
     */
    public function run() {
        if (isset($_REQUEST)) {
            if (Yii::app()->request->isPostRequest) {
                $model = (isset($this->model)) ? call_user_func(array($this->model, 'model')) : call_user_func(array($_REQUEST['model'], 'model'));
                $entry = $model->findAllByPk($_REQUEST['id']);
                if (!empty($entry)) {
                    foreach ($entry as $page)
                        $page->delete();//$page->deleteByPk($_REQUEST['id']);
                }
            }
        }
    }

}
