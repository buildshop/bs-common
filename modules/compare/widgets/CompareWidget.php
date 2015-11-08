<?php

class CompareWidget extends Widget {

    public $registerFile = array('compare.js');
    public $pk;

    public function init() {
        $this->assetsPath = dirname(__FILE__) . '/assets';
        parent::init();
    }

    public function run() {
        $this->render($this->skin, array('pk' => $this->pk));
    }

}

?>
