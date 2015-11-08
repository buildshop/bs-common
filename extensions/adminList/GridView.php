<?php

Yii::import('zii.widgets.grid.CGridView');
Yii::import('ext.adminList.columns.ButtonColumn');
Yii::import('ext.adminList.columns.CEditableColumn');
Yii::import('ext.adminList.columns.HandleColumn');
//Yii::import('ext.adminList.columns.CheckBoxColumn');
Yii::import('ext.adminList.columns.EmailColumn');
Yii::import('ext.adminList.columns.DataColumn');


Yii::import('app.forms.FormButtonElement');
Yii::import('app.forms.FormInputElement');

/**
 * 
 * <b>Пример:</b>
 * <code>
 * $this->widget('ext.adminList.GridView',array());
 * </code>
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package widgets.adminList
 * @uses CGridView 
 */
class GridView extends CGridView {

    public $itemsCssClass = 'table table-striped';
    public $headerOptions = true;
    public $autoColumns = true;
    public $enableHeader = true;
    public $headerButtons = array();
    public $name;
    public $rowCssStyleExpression;
    public $template = '{items}';
    public $selectableRows = 2;
    public $enableDragDropSorting = true;

    /**
     * @var string the field name in the database table which stores the order for the record. This should be a positive integer field. Defaults to 'order'
     */
    public $orderField = 'ordern';

    /**
     * @var string the field name in the database table which stores the id for the record. Defaults to 'id'
     */
    public $idField = 'id';

    /**
     * @var string the action name that will be used to trigger drag and drop sorting (through the AjaxSortingAction located in the "actions" directory in this extension). Defaults to 'order'. This must be defined in the "actions" method of the controller in which this widget is called. For example: "public function actions() {return array('order' => array('class' => 'ext.yiisortablemodel.actions.AjaxSortingAction'))}. If needed, its acces rules have to be defined, too"
     */
    public $orderUrl = 'order';
    public $jqueryUiSortableOptions = array("handle" => '.handle', "axis" => "y", "placeholder" => 'ui-state-highlight', "forcePlaceholderSize" => true);
    public $descSort = false;
    public $updateAfterSorting = true;
    public $allItemsInOnePage = false;
    public $successMessage = '';
    public $errorMessage = 'An error has occured while sorting';

    /**
     * @var array List of custom actions to display in footer.
     * See example in {@link GridView::getFooterActions}
     */
    protected $_customActions;

    /**
     * @var bool Set to false to hide `Delete` button.
     */
    public $hasDeleteAction = true;

    /**
     * @var bool Display custom actions
     */
    public $enableCustomActions = true;
    public $enableHistory = true;
    public $genId = true;

    public function run() {
        $this->registerClientScript();

        echo Html::openTag($this->tagName, $this->htmlOptions) . "\n";
        echo Html::openTag('div', array('id' => 'grid-loading'));
        echo Html::closeTag('div');
        $this->renderContent();
        $this->renderKeys();

        echo Html::closeTag($this->tagName);
    }

    /**
     * Initializes the grid view.
     */
    public function init() {
        if (isset($this->dataProvider->model)) {
            if ($this->enableDragDropSorting === true && array_key_exists('ordern', $this->dataProvider->model->attributes)) {
                /* To use this widget, data provider must be an instance of CActiveDataProvider */
                if (!($this->dataProvider instanceof ActiveDataProvider)) {
                    throw new CException(Yii::t('zii', 'Data provider must be an instance of ActiveDataProvider'));
                }
                if ($this->allItemsInOnePage === true) {
                    $this->dataProvider->pagination = false;
                }
                // $this->enableSorting = false;
                if ($this->descSort !== true) {
                    $sort_direction = 'DESC';
                } else {
                    $sort_direction = 'ASC';
                }
                $this->dataProvider->setSort(array('defaultOrder' => '`t`.`' . $this->orderField . '`' . $sort_direction));
            }
        }


        // Uhhhh! ugly copy-paste from CBaseListView::init()!
        if ($this->dataProvider === null)
            throw new CException(Yii::t('zii', 'The "dataProvider" property cannot be empty.'));


        // $this->dataProvider->getData();

        $this->htmlOptions['id'] = $this->getId();
        $this->htmlOptions['class'] = 'grid-view';

        if ($this->enableSorting && $this->dataProvider->getSort() === false)
            $this->enableSorting = false;
        if ($this->enablePagination && $this->dataProvider->getPagination() === false)
            $this->enablePagination = false;
        // End of ugly

        if ($this->baseScriptUrl === null) {
            $this->baseScriptUrl = Yii::app()->getAssetManager()->publish(
                    Yii::getPathOfAlias('ext.adminList.assets'), false, -1, YII_DEBUG
            );
        }

        if ($this->cssFile !== false) {
            if ($this->cssFile === null)
                $this->cssFile = $this->baseScriptUrl . '/styles.css';
            Yii::app()->getClientScript()->registerCssFile($this->cssFile);
        }




        $this->pager = array(
            'cssFile' => $this->baseScriptUrl . '/pager.css',
            'htmlOptions' => array('class' => 'pagination'),
            'header' => (Yii::app()->controller instanceof AdminController) ? '' : null
                // 'pageSize'=>Yii::app()->settings->get('core', 'pagenum')
        );


        if ((isset($this->dataProvider->model)) && $this->autoColumns) {  //@remove isset($this->dataProvider->model->gridColumns) && 
            $cr = new CDbCriteria;
            $cr->order = '`t`.`ordern` ASC';
            $cr->condition = '`t`.`grid_id`=:grid';
            $cr->params = array(
                'grid' => $this->id
            );
            $model = GridColumns::model()->findAll($cr);
            $colms = array();
            if (isset($model)) {
                foreach ($model as $k => $col) {
                    $colms[$col->key] = $col->key;
                }
            }


            $this->columns = $this->dataProvider->model->getColumnSearch($colms);
        }

        $this->initColumns();
    }

    
    
    	protected function initColumns()
	{
		if($this->columns===array())
		{
			if($this->dataProvider instanceof CActiveDataProvider)
				$this->columns=$this->dataProvider->model->attributeNames();
			elseif($this->dataProvider instanceof IDataProvider)
			{
				// use the keys of the first row of data as the default columns
				$data=$this->dataProvider->getData();
				if(isset($data[0]) && is_array($data[0]))
					$this->columns=array_keys($data[0]);
			}
		}
		$id=$this->getId();
		foreach($this->columns as $i=>$column)
		{
			if(is_string($column))
				$column=$this->createDataColumn($column);
			else
			{
				if(!isset($column['class']))
					$column['class']='DataColumn';
				$column=Yii::createComponent($column, $this);
			}
			if(!$column->visible)
			{
				unset($this->columns[$i]);
				continue;
			}
			if($column->id===null)
				$column->id=$id.'_c'.$i;
			$this->columns[$i]=$column;
		}

		foreach($this->columns as $column)
			$column->init();
	}
        
        
    /**
     * Renders a table body row.
     * @param integer $row the row number (zero-based).
     */
    public function renderTableRow($row) {
        $data = $this->dataProvider->data[$row];
        echo '<tr';
        if ($this->rowCssClassExpression !== null) {
            echo ' class="' . $this->evaluateExpression($this->rowCssClassExpression, array('row' => $row, 'data' => $data)) . '"';
            //} else if ($this->rowCssStyleExpression !== null) {
            //    echo ' style="' . $this->evaluateExpression($this->rowCssStyleExpression, array('row' => $row, 'data' => $data)) . '"';
        } else if (is_array($this->rowCssClass) && ($n = count($this->rowCssClass)) > 0) {

            if ($this->rowCssStyleExpression !== null) {
                echo ' class="' . $this->rowCssClass[$row % $n] . '" style="' . $this->evaluateExpression($this->rowCssStyleExpression, array('row' => $row, 'data' => $data)) . '"';
            } else {
                echo ' class="' . $this->rowCssClass[$row % $n] . '"';
            }
        }

        if ($this->enableDragDropSorting === true) {
            echo ' data-id="' . Html::value($data, $this->idField) . '"';
        }
        echo '>';
        foreach ($this->columns as $column) {
            $column->renderDataCell($row);
        }
        echo "</tr>\n";
    }

    protected function getSortScript() {
        return '
         var grid_id = ' . CJavaScript::encode($this->getId()) . ';
         var grid_selector = ' . CJavaScript::encode('#' . $this->getId()) . ';
         var tbody_selector = ' . CJavaScript::encode('#' . $this->getId() . ' tbody') . ';
         /*apply sortable*/
         $(tbody_selector).sortable(' . CJavaScript::encode($this->jqueryUiSortableOptions) . ');
         /*helper to keep each table cell width while dragging*/
         $(tbody_selector).sortable("option", "helper", function(e, ui) {
         console.log("helper");
            ui.children().each(function() {
               $(this).width($(this).width());
              // console.log($(this).width($(this).width()));
        
            });
            return ui;
         });
         /*add dragged row index before moving as an attribute. Used to know if item is moved forward or backward*/
         $(tbody_selector).bind("sortstart", function(event, ui) {
            ui.item.attr("data-prev-index", ui.item.index());
   
         });
         /*trigger ajax sorting when grid is updated*/
         $(tbody_selector).bind("sortupdate", function(event, ui) {
            $(grid_selector).find("#grid-loading").addClass(' . CJavaScript::encode($this->loadingCssClass) . ');
            var data = {};
            data["dragged_item_id"] = parseInt(ui.item.attr("data-id"));
            var prev_index = parseInt(ui.item.attr("data-prev-index"));
            var new_index = parseInt(ui.item.index());
            /*which item place take dragged item*/
            if (prev_index < new_index) {
               data["replacement_item_id"] = ui.item.prev().attr("data-id");
            } else {
               data["replacement_item_id"] = ui.item.next().attr("data-id");
            }
            data["model"] = ' . CJavaScript::encode($this->dataProvider->modelClass) . ';
            data["order_field"] = ' . CJavaScript::encode($this->orderField) . ';
            data["token"] = ' . CJavaScript::encode(Yii::app()->getRequest()->getCsrfToken()) . ';
            ui.item.removeAttr("data-prev-index");
            ' . Html::ajax(array(
                    'type' => 'POST',
                    'url' => Yii::app()->controller->createAbsoluteUrl($this->orderUrl),
                    'data' => 'js:data',
                    'success' => 'js:function() {
                  $(grid_selector).find("#grid-loading").removeClass(' . CJavaScript::encode($this->loadingCssClass) . ');
                  /*update the whole grid again to update row class values*/
                  if ("' . (string) $this->updateAfterSorting . '") {
                     $.fn.yiiGridView.update(grid_id);
                  }
                }
               ',
                    'error' => 'js:function() {
                  $(grid_selector).removeClass(' . CJavascript::encode($this->loadingCssClass) . ');
                  alert(' . CJavascript::encode($this->errorMessage) . ');
                  $.fn.yiiGridView.update(grid_id);
               }
               '
                )) . '
         });
      ';
    }

    /**
     * Registers necessary client scripts.
     */
    public function registerClientScript() {
        $id = $this->getId();

        if ($this->ajaxUpdate === false)
            $ajaxUpdate = false;
        else
            $ajaxUpdate = array_unique(preg_split('/\s*,\s*/', $this->ajaxUpdate . ',' . $id, -1, PREG_SPLIT_NO_EMPTY));
        $options = array(
            'ajaxUpdate' => $ajaxUpdate,
            'ajaxVar' => $this->ajaxVar,
            'pagerClass' => $this->pagerCssClass,
            'loadingClass' => $this->loadingCssClass,
            'filterClass' => $this->filterCssClass,
            'tableClass' => $this->itemsCssClass,
            'selectableRows' => $this->selectableRows,
            'enableHistory' => $this->enableHistory,
            'updateSelector' => $this->updateSelector,
            'filterSelector' => $this->filterSelector
        );
        if ($this->ajaxUrl !== null)
            $options['url'] = CHtml::normalizeUrl($this->ajaxUrl);
        if ($this->ajaxType !== null) {
            $options['ajaxType'] = strtoupper($this->ajaxType);
            $request = Yii::app()->getRequest();
            if ($options['ajaxType'] == 'POST' && $request->enableCsrfValidation) {
                $options['csrfTokenName'] = $request->csrfTokenName;
                $options['csrfToken'] = $request->getCsrfToken();
            }
        }
        if ($this->enablePagination)
            $options['pageVar'] = $this->dataProvider->getPagination()->pageVar;
        foreach (array('beforeAjaxUpdate', 'afterAjaxUpdate', 'ajaxUpdateError', 'selectionChanged') as $event) {
            if ($this->$event !== null) {
                if ($this->$event instanceof CJavaScriptExpression)
                    $options[$event] = $this->$event;
                else
                    $options[$event] = new CJavaScriptExpression($this->$event);
            }
        }

        $options = CJavaScript::encode($options);
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerCoreScript('bbq');
        if ($this->enableHistory && !Yii::app()->request->isAjaxRequest)
            $cs->registerCoreScript('history');
        $cs->registerScriptFile($this->baseScriptUrl . '/jquery.yiigridview.js', CClientScript::POS_END);
        $cs->registerScript(__CLASS__ . '#' . $id, "jQuery('#$id').yiiGridView($options);");

        //  parent::registerClientScript();

        if (isset($this->dataProvider->model) && isset($this->autoColumns))
            $cs->registerScriptFile($this->baseScriptUrl . '/editgridcolums.js', CClientScript::POS_END);


        if (isset($this->dataProvider->model)) {
            if ((count($this->dataProvider->getData()) > 0) && $this->enableDragDropSorting === true && array_key_exists('ordern', $this->dataProvider->model->attributes)) {

                // $cs = Yii::app()->getClientScript();
                $cs->registerScript(__CLASS__ . '-' . $this->id,
                        /* Call sort script when document is ready and each time grid is updated */ $this->getSortScript() . '
            $(document).ajaxSuccess(function(e, xhr, settings) {
               if (settings.url === $.fn.yiiGridView.getUrl(' . CJavaScript::encode($this->getId()) . ')) {
                  ' . $this->getSortScript() . '
               }
            });
      ');
            }
        }


        if (Yii::app()->request->isAjaxRequest) {
            $cs->scriptMap = array(
                    //  'jquery.yiigridview.js'=>false,
                    //  'jquery.js' => false,
                    //  'jquery.min.js' => false,
                    // 'editgridcolums.js'=>false,
                    //      'jquery.ba-bbq.js'=>false,
                    // 'jquery.ba-bbq.min.js'=>false,
                    // 'jquery.history.js'=>false,
                    //'jquery.jgrowl.js'=>false,
            );
        }
    }

    protected static function t($message, $params = array()) {
        return Yii::t('GridView.default', $message, $params);
    }

    /**
     * Renders the data items for the grid view.
     */
    public function renderItems() {
        if ($this->enableHeader) { //$this->enableHeader
            $params = array();
            if (isset($this->name))
                $params['title'] = $this->name;
            if ($this->headerOptions && $this->autoColumns) {
                $params['options'] = array(
                    array(
                        'label' => self::t('CHANGE_TABLE'),
                        'icon' => 'icon-table-2',
                        'href' => 'javascript:grid.editcolums("' . $this->id . '","' . $this->dataProvider->modelClass . '","' . $this->controller->module->id . '");'
                    )
                );
            }
            if (isset($this->headerButtons))
                $params['buttons'] = $this->headerButtons;
            Yii::app()->tpl->openWidget($params);
        }

        parent::renderItems();

        if ($this->selectableRows > 0 && $this->enableCustomActions === true && (count($this->dataProvider->getData()) > 0)) {
            //echo '<select class="CA" name="test" onChange="customActions(this);">';
            if ($this->enableCustomActions === true) {
                


                $this->widget('zii.widgets.CMenu', array(
                    'id' => $this->getId() . 'Actions',
                    'encodeLabel'=>false,
                    'submenuHtmlOptions' => array('class' => 'dropdown-menu '),
                    'htmlOptions' => array(
                        'class' => ' btn-group dropup gridActions',
                    ),
                    'items' => array(
                        array(
                            'label' => 'Выбрать действие <span class="caret"></span>',
                            'url' => '#',
                            'linkOptions' => array(
                                'class' => 'btn btn-default',
                                'data-toggle' => 'dropdown',
                                'aria-haspopup'=>"true",
                                'aria-expanded'=>"false"
                                ),
                            'items' => $this->getCustomActions()
                        ),
                    )//this->getCustomActions()
                ));
            }
        }
        $this->renderPager();
        if ($this->enableHeader) //$this->autoColumns && 
            Yii::app()->tpl->closeWidget();
    }

    public function setCustomActions($actions) {
        foreach ($actions as $action) {
            if (!isset($action['linkOptions']))
                $action['linkOptions'] = $this->getDefaultActionOptions();
            else
                $action['linkOptions'] = array_merge($this->getDefaultActionOptions(), $action['linkOptions']);
            $this->_customActions[] = $action;
        }
    }

    public function getCustomActions() {
        if ($this->hasDeleteAction === true) {
            $this->customActions = array(array(
                    'label' => Yii::t('app', 'DELETE'),
                    'url' => $this->owner->createUrl('delete'),
                    'icon' => 'icon-trashcan',
                    'linkOptions' => array(
                        'class' => 'actionDelete',
                        'data-question' => Yii::t('app', 'PERFORM_ACTION'),
                    )
                    ));
        }
        return $this->_customActions;
    }

    /**
     * @return array Default linkOptions for footer action.
     */
    public function getDefaultActionOptions() {
        return array(
            'data-token' => Yii::app()->request->csrfToken,
            'data-question' => Yii::t('app', 'PERFORM_ACTION'),
            'model' => $this->dataProvider->modelClass,
            'onClick' => strtr('return $.fn.yiiGridView.runAction(":grid", this);', array(
                ':grid' => $this->getId()
                    )
            ),
        );
    }

    public function renderEmptyText() {
        $emptyText = $this->emptyText === null ? Yii::t('app', 'NO_INFO') : $this->emptyText;
        Yii::app()->tpl->alert('info', $emptyText, false);
    }

    public function getId($autoGenerate = true) {
        if (isset($this->dataProvider->modelClass) && $this->genId) {
            return strtolower($this->dataProvider->modelClass) . '-grid';
        } else {
            return parent::getId($autoGenerate);
        }
    }

}

/**
 * Column class to render ID column
 */
class SGridIdColumn extends DataColumn {

    public function renderHeaderCell() {
        $this->headerHtmlOptions['width'] = '20px';
        parent::renderHeaderCell();
    }

}