<?php

class DiscountHelper extends CComponent {

    /**
     * Get roles and prepare to display in dropdownlist
     *
     * @return array
     */
    public static function getGroup() {
        $roles = Yii::app()->db->createCommand()
                ->select('name')
                ->from('{{user_group}}')
                ->where('id!=2 AND id!=1')
                ->queryColumn();

        return array_combine($roles, $roles);
    }

}