<?php

class SettingsYandexMarketForm extends FormModel {

    protected $_mid = 'yandexMarket';
    public $name;
    public $company;
    public $url;
    public $currency_id;

    public function getForm() {
        return new CMSForm(array('id' => __CLASS__,
                    'showErrorSummary' => true,
                    'elements' => array(
                        'name' => array(
                            'type' => 'text',
                            'hint' => $this->t('HINT_NAME')
                        ),
                        'company' => array(
                            'type' => 'text',
                            'hint' => $this->t('HINT_COMPANY')
                        ),
                        'url' => array(
                            'type' => 'text',
                            'hint' => $this->t('HINT_URL')
                        ),
                        'currency_id' => array(
                            'type' => 'dropdownlist',
                            'items' => $this->getCurrencies(),
                            'empty' => '---',
                        ),
                    ),
                    'buttons' => array(
                        'submit' => array(
                            'type' => 'submit',
                            'class' => 'buttonS bGreen',
                            'label' => Yii::t('core', 'SAVE')
                        ),
                        'button' => array(
                            'type' => 'button',
                            'label' => Yii::t('core', 'Просмотреть файл'),
                            'attributes' => array(
                                'onclick' => 'window.open("/yandex-market.xml","_blank");',
                                'class' => 'buttonS bDefault',
                            )
                        )
                    )
                        ), $this);
    }

    public function init() {
        $this->attributes = Yii::app()->settings->get('yandexMarket');
    }

    public function validateCurrency() {
        $currencies = Yii::app()->currency->getCurrencies();
        if (count($currencies)) {
            if (!array_key_exists($this->currency_id, $currencies))
                $this->addError('currency_id', $this->t('ERROR_CURRENCY'));
        }
    }

    public function rules() {
        return array(
            array('currency_id', 'validateCurrency'),
            array('name, company, url', 'type', 'type' => 'string'),
        );
    }

    public function getCurrencies() {
        $result = array();
        foreach (Yii::app()->currency->getCurrencies() as $id => $model)
            $result[$id] = $model->name;
        return $result;
    }

    public function save($message=true) {
        Yii::app()->settings->set('yandexMarket', $this->attributes);
        parent::save($message);
    }

}
