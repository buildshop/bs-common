<?php

Yii::import('mod.poll.models.*');

class BasePollWidget extends Portlet {

    private $_id;
    protected $_poll;
    protected $_assetsUrl = false;



    public function getId($autoGenerate = TRUE) {
        if ($this->_id !== NULL)
            return $this->_id;
        else if ($autoGenerate)
            return $this->_id = 'Poll_random';
    }

    public function run() {
        if (Yii::app()->hasModule('poll')) {
            parent::run();
        }
    }

    public function renderDecoration() {
        echo '<div class="' . $this->decorationCssClass . '"><b>Опрос</b></div>';
    }

    public function init() {
        if (Yii::app()->hasModule('poll')) {
            $this->_poll = Poll::model()->rand()->active()->find();
            $this->title = $this->_poll->title;
            parent::init();
        }
    }




}
