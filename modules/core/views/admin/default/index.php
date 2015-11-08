<?php

if (isset($this->module->adminMenu['shop'])) {
    echo CHtml::openTag('ul', array('class' => 'middleNavA'));
    foreach ($this->module->adminMenu['shop']['items'] as $key => $item) {
        echo CHtml::openTag('li');
        echo CHtml::link('<span class="iconb ' . $item['icon'] . '"></span><span>' . $item['label'] . '</span>', $item['url']);
        echo CHtml::closeTag('li');
    }
    echo CHtml::closeTag('ul');
    ?>
    <div class="divider"><span></span></div>
    <?php
}
?>