<?php

class SMSWidget extends CWidget {

    public $key;
    public $model;
    private $aliasPathClass = 'mod.sms.components.services';

    public function init() {
        $service = Yii::app()->settings->get('sms');
        $method = $this->key;
        Yii::import("{$this->aliasPathClass}.{$service['service']}");
        $class = explode('.', $service['service']);
        $model = new $class[1];
        //  $model->send('dasas', array('+380633907136')); //,'+380634236242'
        if (method_exists($model, $method)) {
            $model->$method($this->model);
        } else {
            $model::log("Ошибка: В классе необнаружен метод {$method}");
        }
    }

}

?>
