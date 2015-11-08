<?php
/**
 * @uses AdminController 
 */
class DefaultController extends AdminController {

    public function actionIndex() {
        $menu = $this->module->adminMenu;
        $this->render('index',array('menu'=>$menu));
    }

}
