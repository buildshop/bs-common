<?php

class BrandsWidget extends CWidget {

    public function run() {
        $data = ShopManufacturer::model()->onlyImage()->findAll();
        $this->render($this->skin, array('result' => $data));
    }

}
