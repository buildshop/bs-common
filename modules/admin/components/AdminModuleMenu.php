<?php

class AdminModuleMenu extends CMenu {

    protected function renderMenuItem($item) {
        if (isset($item['url'])) {
            $icon = isset($item['icon']) ? '<i class="fa ' . $item['icon'] . '"></i>' : '';
            $count = isset($item['count']) ? '<span class="' . ((isset($item['countClass'])) ? $item['countClass'] : 'dataNumGreen') . '">' . $item['count'] . '</span>' : '';
            $label = $this->linkLabelWrapper === null ? $item['label'] : Html::tag($this->linkLabelWrapper, $this->linkLabelWrapperHtmlOptions, $item['label']);
            return Html::link($icon . $label . $count, $item['url'], isset($item['linkOptions']) ? $item['linkOptions'] : array());
        }
        else
            return Html::tag('span', isset($item['linkOptions']) ? $item['linkOptions'] : array(), $item['label']);
    }
/*
    protected function renderMenu($items) {
        if (count($items)) {
            echo CHtml::openTag('div', $this->htmlOptions) . "\n";
            $this->renderMenuRecursive($items);
            echo CHtml::closeTag('div');
        }
    }

    protected function renderMenuRecursive($items) {
        $count = 0;
        $n = count($items);
        foreach ($items as $item) {
            $count++;
            $options = isset($item['itemOptions']) ? $item['itemOptions'] : array();
            $class = array();
            if ($item['active'] && $this->activeCssClass != '')
                $class[] = $this->activeCssClass;
            if ($count === 1 && $this->firstItemCssClass !== null)
                $class[] = $this->firstItemCssClass;
            if ($count === $n && $this->lastItemCssClass !== null)
                $class[] = $this->lastItemCssClass;
            if ($this->itemCssClass !== null)
                $class[] = $this->itemCssClass;
            if ($class !== array()) {
                if (empty($options['class']))
                    $options['class'] = implode(' ', $class);
                else
                    $options['class'].=' ' . implode(' ', $class);
            }

            // echo CHtml::openTag('li', $options);

            $menu = $this->renderMenuItem($item);
            if (isset($this->itemTemplate) || isset($item['template'])) {
                $template = isset($item['template']) ? $item['template'] : $this->itemTemplate;
                echo strtr($template, array('{menu}' => $menu));
            }
            else
                echo $menu;

            if (isset($item['items']) && count($item['items'])) {
                //   echo "\n" . CHtml::openTag('ul', isset($item['submenuOptions']) ? $item['submenuOptions'] : $this->submenuHtmlOptions) . "\n";
                $this->renderMenuRecursive($item['items']);
                //   echo CHtml::closeTag('ul') . "\n";
            }

            // echo CHtml::closeTag('li') . "\n";
        }
    }*/

}