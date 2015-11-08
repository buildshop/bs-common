<?php
Yii::import('mod.sms.components.CSMS');
class SMSWidgetBalance extends BlockWidget {


    private $aliasPathClass = 'mod.sms.components.services';

    public function init() {

    }
    public function getTitle() {
        return 'Информальция о SMS сервисе';
    }

    public function run() {
        $service = Yii::app()->settings->get('sms');
        if($service){
        Yii::import("{$this->aliasPathClass}.{$service['service']}");
        $class = explode('.', $service['service']);

        $model = new $class[1];

        if (method_exists($model, 'getBalance')) {
            $model->getBalance();
            Yii::app()->tpl->alert('info','На вашем балансе '.$model->getBalance().' кр.',false);
        } else {
             Yii::app()->tpl->alert('error','Нет метода СМС баланса.',false);
        }
        }
    }
}

?>
