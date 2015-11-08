<?php

class TestMainWidget extends TestWidget {

    protected $assetsPath = 'ext.widgets.test';

    public function run() {
        echo self::t('LANG');
        $this->renderOptions=array('tre'=>'GAGAGAGA');
        parent::run();
    }

}

?>
