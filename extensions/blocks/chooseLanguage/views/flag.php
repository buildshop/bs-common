<?php

echo CHtml::openTag('ul', array('id' => 'choose-lang'));
foreach (Yii::app()->languageManager->getLanguages() as $lang) {

    $classLi = ($lang->code == Yii::app()->language) ? $lang->code . ' active' : $lang->code;
    $link = ($lang->default) ? CMS::currentUrl() : '/' . $lang->code . CMS::currentUrl();

    echo CHtml::openTag('li', array('class' => $classLi));
    echo CHtml::link(CHtml::image('/images/language/' . $lang->flag_name, $lang->name), $link, array('title' => $lang->name));
    echo CHtml::closeTag('li');
}
echo CHtml::closeTag('ul');
?>
