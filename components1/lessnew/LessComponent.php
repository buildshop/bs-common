<?php

require_once(dirname(__FILE__) . '/lessphp/lessc.inc.php');
/*if (!class_exists('Less_Parser')) {
    require_once dirname(__FILE__) . '/lessphp/lib/Less/Autoloader.php';
    Less_Autoloader::register();
}
*/
//Yii::import('root.core.components.lessnew.lessphp.lib.Less.*');
class LessComponent extends CApplicationComponent {

    private $lessc = null;

    public function init() {
       // echo Yii::getPathOfAlias('root.core.components.lessnew.lessphp.lib.Less');
        $this->lessc = new lessc();

    }
    public function compileFile($lessFile,$cssFile){

    $this->lessc->compileFile($lessFile,$cssFile);
      //  print_r($r);
    }




}