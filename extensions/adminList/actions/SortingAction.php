<?php

/**
 * Это действие вызывается при adminList виджета для сортировать записи.
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package widgets.adminList.actions
 * @uses CAction
 */
class SortingAction extends CAction {

    /**
     * Запустить действие
     */
    public function run() {
        if (isset($_POST)) {
            $order_field = $_POST['order_field'];
            $model = call_user_func(array($_POST['model'], 'model'));
            $dragged_entry = $model->findByPk($_POST['dragged_item_id']);
            $replacement_entry = $model->findByPk($_POST['replacement_item_id']);
            /* load dragged entry before changing orders */
            $prev = $dragged_entry->{$order_field};
            $new = $replacement_entry->{$order_field};
            $dragged_entry->{$order_field} = $new;
            $dragged_entry->update();
            $replacement_entry->{$order_field} = $prev;
            $replacement_entry->update();
        }
    }

}
