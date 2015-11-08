<?php

class FrontControlWidget extends CWidget {

    public $data;
    private $items = array();
    public $options = array(
        'position' => 'left'
    );
    public $widget = false;

    public function init() {
        $this->registerScripts();
    }

    public function run() {
        if(Yii::app()->user->isSuperuser)
            $this->render($this->skin);
    }

    private function registerScripts() {
        $assetsUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DS . 'assets', false, -1, YII_DEBUG);
        $cs = Yii::app()->clientScript;
        $cs->registerCssFile($assetsUrl . '/frontControl.css');
        $cs->registerScriptFile($assetsUrl . '/frontControl.js');
        //$cs->registerCoreScript('cookie');
    }

    protected function renderItems() {
        $result = '';
        if ($this->widget) {
            $grid = $this->widget->getId();
        } else {
            $grid = false;
        }
        if (isset($this->data->switch)) {
            $this->items[] = array(
                'htmlOptions' => array(
                    'id' => 'FrontControlWidget_switch',
                    'onClick' => 'control.switchChange(this, "' . $grid . '"); return false;'
                ),
                'label' => 'Скрыть',
                'url' => $this->data->getSwitchUrl(),
                'icon' => 'flaticon-eye'
            );
        }
        if (isset($this->data->primaryKey)) {
            $this->items[] = array(
                'htmlOptions' => array(
                    'id' => 'FrontControlWidget_update',
                    'target'=>'_blank',
                   // 'onClick' => 'control.update(this, "' . $grid . '");'
                ),
                'label' => Yii::t('app','UPDATE',1),
                'url' => $this->data->getUpdateUrl(),
                'icon' => 'flaticon-edit'
            );
            $this->items[] = array(
                'htmlOptions' => array(
                    'id' => 'FrontControlWidget_remove',
                    'onClick' => 'control.remove(this, "' . $grid . '"); return false;'
                ),
                'label' => Yii::t('app','DELETE'),
                'url' => $this->data->getDeleteUrl(),
                'icon' => 'flaticon-delete'
            );
        }
        foreach ($this->items as $item) {
            $result .= Html::openTag('li');
            $result .= Html::link('<i class="' . $item['icon'] . '"></i> ' . $item['label'], $item['url'], $item['htmlOptions']);
            $result .= Html::closeTag('li');
        }
        return $result;
    }

}

?>
