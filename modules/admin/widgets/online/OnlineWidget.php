<?php

class OnlineWidget extends CWidget {

    public function init() {
        parent::init(__FILE__);
    }

    public function run() {
        $model = new Session(null);
        $this->render('widget', array(
            'model' => $model,
            'online' => Session::online(),
        ));
    }

}

?>
