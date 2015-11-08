<?php

/**
 * Display vertical tabs in sidebar
 */
class AdminTabs extends CWidget {

    public $tabs = array();

    public function run() {
        $cs = Yii::app()->getClientScript();
        if (Yii::app()->controller->isAjax) {
            $cs->registerCoreScript('jquery.ui');
            $cs->scriptMap = array('jquery.js' => false);
        }
        $liContent = '';
        $tabContent = '';

        $cs->registerScript('tabs', '$(".tabs-form").tabs();', CClientScript::POS_READY);
        $n = 0;

        foreach ($this->tabs as $title => $content) {
            $tabContent .= Html::openTag('div', array(
                        'id' => 'tab_' . $n,
                        'class' => 'tabz',
                    ));

            $tabContent .= (is_array($content)) ? $content['content'] : $content;
            $tabContent .= Html::closeTag('div');
            $title = (preg_match('#^(icon-)#ui', $title)) ? '<span class="icon-medium ' . $title . ' noBold"></span>' : $title;
            $liContent .= '<li><a href="#tab_' . $n . '">' . $title . '</a></li>';
            $n++;
        }

        echo Html::openTag('div', array('class' => 'tabs-form'));
        echo '<ul class="tabs">' . $liContent . '</ul>' . $tabContent;
        echo Html::closeTag('div');
    }

}