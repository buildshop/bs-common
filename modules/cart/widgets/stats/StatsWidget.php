<?php



class StatsWidget extends CWidget {

    public $registerFile = array(
            // 'cartWidget.css',
            //'cartWidget.js',
    );

    public function init() {
     //   $this->assetsPath = dirname(__FILE__) . '/assets';
        parent::init();
    }

    public function run() {


        $this->render($this->skin, array(

        ));
    }

}
