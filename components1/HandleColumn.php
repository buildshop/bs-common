<?php

/**
 * HandleColumn class file.
 *
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @uses CGridColumn
 * @package widgets.adminList.columns
 */
Yii::import('zii.widgets.grid.CGridColumn');

class HandleColumn extends CGridColumn {

    /**
     * @var array the HTML options for the data cell tags.
     */
    public $htmlOptions = array('class' => 'handle', 'style' => 'cursor:pointer');

    /**
     * @var array the HTML options for the header cell tag.
     */
    public $headerHtmlOptions = array('class' => 'handle-column');

    /**
     * @var array the HTML options for the footer cell tag.
     */
    public $footerHtmlOptions = array('class' => 'handle-column');

    /**
     * @var array the HTML options for the checkboxes.
     */
    public $checkBoxHtmlOptions = array();

    protected function renderHeaderCellContent() {
        echo '<span class="sorting"></span>';
    }

    protected function renderDataCellContent($row, $data) {
        echo '<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>';
    }

}
