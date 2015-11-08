<?php


class CManagerAccess extends CComponent {

    protected $user_id;
    protected $_rules = array();

    public function init() {



        $this->user_id = (!Yii::app()->user->isGuest) ? Yii::app()->user->id : false;
    }

    public function getName($accessLevel) {
        if (is_numeric($accessLevel)) {
            $accessData = $this->dataList();
            return $accessData[$accessLevel];
        } else {
            throw new Exception('$accessLevel должен быть числом');
        }
    }

    /**
     * with module rights
     * 
     * @param int $accessLevel Default 0 all members access
     * @return boolean
     */
    public function check($accessLevel = 0) {
        $accessArray = $this->dataList();
        if (isset($accessArray[$accessLevel])) {
            if ($accessLevel == 0) {
                return true;
            } elseif (Yii::app()->user->checkAccess($accessArray[$accessLevel])) {
                return true;
            }
        }
        return false;
    }

    public function dataList() {
        $array = array();
        foreach (CMap::mergeArray(array(array('name' => 'Все посетители')), $this->_rules) as $key => $value) {
            $array[$key] = $value['name'];
        }
        return $array;
    }

    public function getUserRole() {
        $array = array();
        foreach (Rights::getAssignedRoles($this->user_id) as $role) {
            $array[] = $role->name;
        }
        return $array;
    }

    public function checkUserRole($user_id) {
        foreach (Rights::getAssignedRoles($user_id) as $role) {
            echo $role->name . '<br/>';
        }
    }

}