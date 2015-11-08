<?php

Yii::import('zii.widgets.CMenu');

class MenuWidget extends CMenu {

    public function init() {
        $model = MenuModel::model()
                ->enabled()
                ->findAll();
        $result = array();
        foreach ($model as $item) {
            $result[] = array(
                'label' => $item->label,
                'url' => $item->url,
                'active' => $this->isActive($item->url),
            );
        }
        $this->items = CMap::mergeArray($result, $this->items);
        parent::init();
    }

    private function isActive($url) {
        if (isset($_GET['url'])) {
            if (Yii::app()->request->requestUri == $url) {
                return true;
            } else {
                return false;
            }
            return ($_GET['url'] == $url) ? true : false;
        } else {
            return false;
        }
    }

}