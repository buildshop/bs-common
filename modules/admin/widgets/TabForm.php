<?php

/**
 * Render form using jquery tabs.
 * @package Widgets
 */
class TabForm extends CMSForm {

    /**
     * @var array list of tabs (tab title=>tab content). Will be
     * generated from form elements.
     */
    protected $tabs = array();

    /**
     * @var array Additional tabs to render.
     */
    public $additionalTabs = array();

    /**
     * @var string Widget to render form. zii.widgets.jui.CJuiTabs
     */
    public $formWidget = 'ext.sidebartabs.AdminTabs';
    protected $activeTab = null;
    public $positionTabs = null; //vertical or null

    public function render() {
        if ($this->positionTabs == 'vertical') {
            $cs = Yii::app()->getClientScript();
            $cs->registerScript('tabs-pos', '
            $(".ui-tabs").tabs().addClass("ui-tabs-vertical ui-helper-clearfix");
            var widgetWidth = $(".fluid").width();
            var tabsWidth = $(".ui-tabs-nav").width();
            $(".tabz").css({"width":widgetWidth - tabsWidth,"margin-left":tabsWidth - 1});
       
            ', CClientScript::POS_READY);
        }
        $result = $this->renderBegin();
        $result .= $this->renderElements();
        $result .= $this->renderEnd();
        return $result;
    }

    public function tabs() {
        $this->render();
        $result = $this->renderBegin();
        if ($this->showErrorSummary && ($model = $this->getModel(false)) !== null) {
            // Display errors summary on each tab.
            $errorSummary = $this->getActiveFormWidget()->errorSummary($model) . "\n";
            $result = $errorSummary . $result;
        }

        $result .= $this->owner->widget($this->formWidget, array(
            'tabs' => CMap::mergeArray($this->tabs, $this->additionalTabs),
                ), true);
        $result .= $this->renderButtons();
        $result .= $this->renderEnd();
        return $result;
    }

    /**
     * Renders elements
     * @return string
     */
    public function renderElements() {
        $output = '';
        foreach ($this->getElements() as $element) {
            if ($element->visible === true) {
                if (isset($element->title))
                    $this->activeTab = $element->title;

                $out = '' . $this->renderElement($element) . '';
                $this->tabs[$this->activeTab] = $out;
                $output .= $out;
            }
        }
        return $output;
    }

}