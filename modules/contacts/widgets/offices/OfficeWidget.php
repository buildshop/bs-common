<?php

class officeWidget extends CWidget {



    public function run() {
        $model = ContactsOffice::model()->findAll();
        $this->render('rendered', array('model' => $model));
    }

}
