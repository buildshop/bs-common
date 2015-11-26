<?php

/**
 * Это действие вызывается при adminList виджета для скрыть/показать записи или запись.
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package widgets.adminList.actions
 * @uses CAction
 * 
 * @property integer $_REQUEST['id'] Массив записей
 * @property string $_REQUEST['model'] Модель 
 * @property integer $_REQUEST['switch'] ON/OFF 
 */
class SwitchAction extends CAction {

    /**
     * Запустить действие
     */
    public function run() {
        if (isset($_REQUEST)) {
            if (Yii::app()->request->isPostRequest) {
                $model = call_user_func(array($_REQUEST['model'], 'model'));
                $entry = $model->findAllByPk($_REQUEST['id']);
                if (!empty($entry)) {
                    foreach ($entry as $page) {
                        $page->updateByPk($_REQUEST['id'], array('switch' => $_REQUEST['switch']));
                    }
                    Yii::app()->timeline->set('SWITCH_RECORD', array(
                        '{model}' => $_REQUEST['model'],
                        '{pk}' => $_REQUEST['id'],
                        '{switch}' => ($_REQUEST['switch']) ? Yii::t('app', 'ON',0) : Yii::t('app', 'OFF',0),
                        '{htmlClass}'=>($_REQUEST['switch']) ? 'success' : 'danger',
                    ));
                }
                if ($model instanceof ShopProduct) {
                    ShopProductCategoryRef::model()->updateAll(array(
                        'switch' => $_REQUEST['switch']
                            ), 'product=:p', array(':p' => $_REQUEST['id']));
                }
            }
        }
    }

}
