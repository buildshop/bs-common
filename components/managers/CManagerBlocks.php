<?php

/**
 * Комнонент блоков сайта.
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package components
 * @uses CComponent 
 */
class CManagerBlocks extends CComponent {

    protected $data = array();

    public function init() {

        $blocks = BlocksModel::model()->cache(Yii::app()->controller->cacheTime)->enabled()->findAll();

        if (!empty($blocks)) {
            foreach ($blocks as $row) {

                if (!isset($this->data[$row['position']]))
                    $this->data[$row['position']] = array();

                $this->data[$row['position']][$row['id']] = array(
                    'blockID' => $row['id'],
                    'blockPOS' => $row['position'],
                    'modules' => $row['modules'],
                    'access' => $row['access'],
                    'widget' => $row['widget'],
                    'name' => $row['name'],
                    'content' => $row['content']
                );
            }
        }
    }

    public function get($position, $key = null) {
        if (isset($this->data[$position])) {
            if ($key === null) {
                foreach ($this->data[$position] as $key => $block) {
                    if (Yii::app()->access->check($block['access']) && in_array(Yii::app()->controller->module->id, explode(',', $block['modules']))) {
                        if ($block['widget']) {
                            if (file_exists(Yii::getPathOfAlias($block['widget']) . '.php')) {

                                
                               // $block['content']=Yii::app()->controller->widget($block['widget']);
                                $this->randerBlock($block);
                            } else {
                                echo Yii::app()->tpl->alert('warning', 'Указанный виджет не найден.');
                            }
                        } else {
                            $this->randerBlock($block);
                        }
                    }
                }
            }
            if (isset($this->data[$position][$key]) && $position = 'fly') {
                if (Yii::app()->access->check((int)$this->data[$position][$key]['access']) && in_array(Yii::app()->controller->module->id, explode(',', $this->data[$position][$key]['modules']))) {
                    if ($this->data[$position][$key]['widget']) {
                        if (file_exists(Yii::getPathOfAlias($this->data[$position][$key]['widget']) . '.php')) {
                            Yii::app()->controller->widget($this->data[$position][$key]['widget']);
                        } else {
                            echo Yii::app()->tpl->alert('warning', 'Указанный виджет не найден.');
                        }
                    } else {
                        $this->randerBlock($this->data[$position][$key]);
                    }
                }
            }
        } else {
            if (Yii::app()->user->getIsSuperuser()) {
                // echo Yii::app()->tpl->alert('warning', 'Список блоков пуст. Данное сообщение видет только администраторы');
            }
        }
    }

    /*
     * Ирархия блоков
     */

    private function randerBlock($params = array()) {
        $theme = Yii::app()->theme->getName();
        $module = Yii::app()->controller->module->id;
        $controller = Yii::app()->controller->id;
        $action = Yii::app()->controller->action->id;
        $block_id = $params['blockID'];
        $block_pos = $params['blockPOS'];
        $layouts = array(
            "mod.{$module}.views.layouts.blocks.block-{$block_pos}-{$block_id}",
            "themes.{$theme}.views.{$module}.layouts.blocks.block-{$block_pos}-{$block_id}",
            "themes.{$theme}.views.layouts.blocks.block-{$block_pos}-{$block_id}",
            "themes.{$theme}.views.{$module}.layouts.blocks.block-{$block_pos}",
            "mod.{$module}.views.layouts.blocks.block-{$block_pos}",
            "themes.{$theme}.views.layouts.blocks.block-{$block_pos}",
        );

        foreach ($layouts as $layout) {
            if (file_exists(Yii::getPathOfAlias($layout) . '.php')) {
                //Yii::log('loading block: "' . $layout . '"', 'info', 'CManagerBlocks');
                return Yii::app()->controller->renderPartial($layout, array('block' => $params), false, false);
                break;
            }
        }
    }

}