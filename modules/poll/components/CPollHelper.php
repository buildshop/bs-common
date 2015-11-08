<?php

class CPollHelper {

    const TYPE_RADIO_LIST = 0;
    const TYPE_CHECKBOX_LIST = 1;

    protected $_poll;

    public function __construct($model) {
        $this->_poll = $model;
    }

    public function renderField($name, $data = array()) {
        $template = '<div class="checkbox">{input}{label}</div>';
        $name = 'PortletPollVote_choice_id[]';
        switch ($this->_poll->many) {
            case self::TYPE_RADIO_LIST:
                return Html::radioButtonList($name, null, $data, array('separator' => '', 'template' => $template));
                break;
            case self::TYPE_CHECKBOX_LIST:
                return Html::checkBoxList($name, null, $data, array('separator' => '', 'template' => $template));
                break;
        }
    }

    public function getUserVoted($userChoice) {
        if (is_array($userChoice) && count($userChoice) >= 1) {
            $r = array();
            foreach ($userChoice as $choice) {
                $r[] = $choice->name;
            }
            return implode(', ', $r);
        } else {
            return $userChoice[0]->name;
        }
    }

    public function loadChoice($choice_ids) {
        if ($choice_ids) {
            $result = array();
            foreach ($this->_poll->choices as $choice) {
                if ($this->_poll->many && count($choice_ids) > 1) { // && count($choice_ids)>1
                    if (in_array($choice->id, $choice_ids)) {
                        $result[] = $choice;
                    } else {
                        unset($choice);
                    }
                    // return $result;
                } else {
                    if (is_object($choice_ids)) {
                        $result = new PollChoice;
                    } else {
                        if ($choice->id == $choice_ids[0]) {
                            $result[] = $choice;
                            // return $result;
                        }
                    }
                }
            }
        }
        return $result;
        //  return is_object($choice_ids)?new PollChoice:$result;
    }

    public function loadVote() {
        $model = $this->_poll;
        $userId = (int) Yii::app()->user->id;
        $isGuest = Yii::app()->user->isGuest;
        $result = array();
        foreach ($model->votes as $vote) {
            if ($vote->user_id == $userId) {
                if (Yii::app()->settings->get('poll', 'ip_restrict') && $isGuest && $vote->ip_address != $_SERVER['REMOTE_ADDR']) {
                    continue;
                } else {
                    $result[] = $vote->choice_id;
                    // return $result;
                }
            }
        }
        return (count($result) >= 1) ? $result : new PollVote;
    }

}

?>
