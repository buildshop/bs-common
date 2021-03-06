<?php

Yii::import('ext.adminList.Pagination');

class ActiveDataProvider extends CActiveDataProvider {

    public $_pagination;

    private function getPageNum() {
        $mod = Yii::app()->settings->get(Yii::app()->controller->module->id);
        $core = Yii::app()->settings->get('core');
        if (Yii::app()->controller instanceof AdminController) {
            return isset($mod['admin_pagenum']) ? $mod['admin_pagenum'] : $core['pagenum'];
        } elseif (Yii::app()->controller instanceof Controller) {
            return isset($mod['pagenum']) ? $mod['pagenum'] : $core['pagenum'];
        }
    }

    public function __construct($modelClass, $config = array()) {
        if (is_string($modelClass)) {
            $this->modelClass = $modelClass;
            $this->model = $this->getModel($this->modelClass);
        } elseif ($modelClass instanceof ActiveRecord) {
            $this->modelClass = get_class($modelClass);
            $this->model = $modelClass;
        }
        $this->setId(Html::modelName($this->model));

        foreach ($config as $key => $value)
            $this->$key = $value;
    }

    public function getPagination($className = 'Pagination') {
        if ($this->_pagination === null) {
            $this->_pagination = new $className;
            if (($id = $this->getId()) != '')
                $this->_pagination->pageVar = $id . '_page';
           // $this->_pagination->setPageSize($this->getPageNum()); 
            $this->_pagination->pageSize=$this->getPageNum(); 
        }
        return $this->_pagination;
    }

    protected function calculateTotalItemCount() {
        $baseCriteria = $this->model->getDbCriteria(false);
        if ($baseCriteria !== null)
            $baseCriteria = clone $baseCriteria;
        $count = $this->model->count($this->getCountCriteria());
        $this->model->setDbCriteria($baseCriteria);
        return $count;
    }

}
