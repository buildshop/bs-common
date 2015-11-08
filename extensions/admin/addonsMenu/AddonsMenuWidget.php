<?php

/**
 * AddonsMenuWidget 
 * 
 * @property array $menu Массив меню
 * @uses CWidget
 */
class AddonsMenuWidget extends CWidget {

    public function run() {
        $menu = isset(Yii::app()->controller->addonsMenu) ? Yii::app()->controller->addonsMenu : array();
        $this->render('view', array('menu' => $menu));
    }

}

?>
