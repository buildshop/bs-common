<?php

class BlockWidget extends CWidget {

    public $alias;

    public function getConfig() {
        return Yii::app()->settings->get($this->alias . '.' . get_class($this));
    }

}

?>
