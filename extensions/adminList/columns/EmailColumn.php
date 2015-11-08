<?php

/**
 * HandleColumn class file.
 *
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @uses CGridColumn
 * @package widgets.adminList.columns
 */
Yii::import('zii.widgets.grid.CGridColumn');

class EmailColumn extends CGridColumn {

    public $name;
    public $value;
    public $type = 'text';

    /**
     * @var array the HTML options for the data cell tags.
     */
    public $htmlOptions = array('class' => 'sssss', 'style' => 'cursor:pointer');

    /**
     * @var array the HTML options for the header cell tag.
     */
    public $headerHtmlOptions = array('class' => 'email-column');

    /**
     * @var array the HTML options for the footer cell tag.
     */
    public $footerHtmlOptions = array('class' => 'email-column');

    /**
     * @var array the HTML options for the checkboxes.
     */
    public $checkBoxHtmlOptions = array();

    protected function renderHeaderCellContent() {
        echo '<span class="sorting"></span>';
    }

    protected function renderDataCellContent($row, $data) {
        echo '';
    }

}
