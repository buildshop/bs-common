<?php

/**
 * CEditableColumn class file.
 *
 * @author Herbert Maschke <thyseus@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2010 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
Yii::import('zii.widgets.grid.CGridColumn');

/**
 * CEditableColumn represents a grid view column that is editable.
 *
 * @author Herbert Maschke <thyseus@gmail.com>
 * @package zii.widgets.grid
 * @since 1.1
 */
class CEditableColumn extends CGridColumn {

    public $editable;

    /**
     * @var string the attribute name of the data model. The corresponding attribute value will be rendered
     * in each data cell. If {@link value} is specified, this property will be ignored
     * unless the column needs to be sortable.
     * @see value
     * @see sortable
     */
    public $name;
    public $type = 'text';

    /**
     * @var string a PHP expression that will be evaluated for every data cell and whose result will be rendered
     * as the content of the data cells. In this expression, the variable
     * <code>$row</code> the row number (zero-based); <code>$data</code> the data model for the row;
     * and <code>$this</code> the column object.
     */
    public $value;
    public $sortable = true;
    public $filter;
    public $pk;

    /**
     * Initializes the column.
     */
    public function init() {
        $this->registerScript();
        parent::init();





        if ($this->name === null && $this->value === null)
            throw new CException(Yii::t('zii', 'Either "name" or "value" must be specified for CEditableColumn.'));
    }

    /**
     * Renders the filter cell content.
     * This method will render the {@link filter} as is if it is a string.
     * If {@link filter} is an array, it is assumed to be a list of options, and a dropdown selector will be rendered.
     * Otherwise if {@link filter} is not false, a text field is rendered.
     * @since 1.1.1
     */
    protected function renderFilterCellContent() {
        if (is_string($this->filter))
            echo $this->filter;
        elseif ($this->filter !== false && $this->grid->filter !== null && $this->name !== null && strpos($this->name, '.') === false) {
            if (is_array($this->filter))
                echo CHtml::activeDropDownList($this->grid->filter, $this->name, $this->filter, array('id' => false, 'prompt' => ''));
            elseif ($this->filter === null)
                echo CHtml::activeTextField($this->grid->filter, $this->name, array('id' => false));
        }
        else
            parent::renderFilterCellContent();
    }

    /**
     * Renders the header cell content.
     * This method will render a link that can trigger the sorting if the column is sortable.
     */
    protected function renderHeaderCellContent() {
        //    $this->htmlOptions = array('class' => 'editable', 'data-id' => $this->pk);
        if ($this->grid->enableSorting && $this->sortable && $this->name !== null)
            echo $this->grid->dataProvider->getSort()->link($this->name, $this->header, array('class' => 'sort-link'));
        elseif ($this->name !== null && $this->header === null) {
            if ($this->grid->dataProvider instanceof ActiveDataProvider)
                echo CHtml::encode($this->grid->dataProvider->model->getAttributeLabel($this->name));
            else
                echo CHtml::encode($this->name);
        }
        else
            parent::renderHeaderCellContent();
    }

    public function renderDataCell($row) {
        $data = $this->grid->dataProvider->data[$row];
        $options = array(
            'class' => 'editable',
            'data-id' => $this->grid->dataProvider->data[$row]->primaryKey,
            'data-json'=>  CJSON::encode(array(
                'pk'=>$this->grid->dataProvider->data[$row]->primaryKey,
                'type'=>$this->editable['type'],
                'items'=>$this->editable['items'],
                'modelClass'=>$this->grid->dataProvider->modelClass,
                'field'=>$this->name,
                'grid'=>$this->grid->id
            ))
                );
        if ($this->cssClassExpression !== null) {
            $class = $this->evaluateExpression($this->cssClassExpression, array('row' => $row, 'data' => $data));
            if (!empty($class)) {
                if (isset($options['class']))
                    $options['class'].=' ' . $class;
                else
                    $options['class'] = $class;
            }
        }
        echo CHtml::openTag('td', $options);
        $this->renderDataCellContent($row, $data);
        echo '</td>';
    }

    /**
     * Renders the data cell content.
     * This method evaluates {@link value} or {@link name} and renders the result.
     * @param integer $row the row number (zero-based)
     * @param mixed $data the data associated with the row
     */
    protected function renderDataCellContent($row, $data) {
        if ($this->value !== null)
            $value = $this->evaluateExpression($this->value, array('data' => $data, 'row' => $row));
        elseif ($this->name !== null)
            $value = CHtml::value($data, $this->name);
        echo $value === null ? $this->grid->nullDisplay : $this->grid->getFormatter()->format($value, $this->type);
    }

    protected function registerScript() {
        // print_r($data);
        $this->editable['modelClass'] = $this->grid->dataProvider->modelClass;
        $this->editable['field'] = $this->name;
        $this->editable['grid']=$this->grid->id;
        $options = CJavaScript::encode($this->editable);
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile(Yii::app()->getModule('admin')->assetsUrl . '/js/editable.js');
        $cs->registerScript('editable', "
            $('.editable').editable();
	");
    }

}
