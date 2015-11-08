<?php

class WishlistWidget extends CWidget {

 //   public $registerFile = array('wishlist.js');
    public $pk;

    public function init() {
      //  $this->assetsPath = dirname(__FILE__) . '/assets';
        parent::init();
    }

    public function run() {
        $this->render($this->skin,array('pk'=>$this->pk));
       
    }

}
?>
