<?php

/**
 * ButtonColumn class file.
 *
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @uses CButtonColumn
 * @package widgets.adminList.columns
 */
class ButtonColumn extends CButtonColumn {

    public $group = false;
    public $htmlOptions = array('class' => 'text-center');
    public $headerHtmlOptions = array('class' => 'button-column');
    public $footerHtmlOptions = array('class' => 'button-column');
    // public $template = '{view} {update} {delete} {switch}';
    public $viewButtonLabel;
    public $viewButtonIcon = 'fa fa-search';
    public $viewButtonUrl = 'Yii::app()->controller->createUrl("view",array("id"=>$data->primaryKey))';
    public $viewButtonOptions = array('class' => 'view'); //bDefault
    public $updateButtonLabel;
    public $updateButtonUrl = 'Yii::app()->controller->createUrl("update",array("id"=>$data->primaryKey))';
    public $updateButtonOptions = array('class' => 'update');
    public $updateButtonIcon = 'flaticon-edit';
    //public $fastupdateButtonLabel;
    //public $fastupdateButtonUrl = 'Yii::app()->controller->createUrl("update",array("id"=>$data->primaryKey))';
    //public $fastupdateButtonOptions = array('class' => 'update');
    //public $fastupdateButtonIcon = 'icon-pencil-3';
    public $switchButtonLabel;
    public $switchButtonUrl = 'Yii::app()->controller->createUrl("switch",array("model"=>$data->search()->modelClass, "id"=>$data->primaryKey, "switch"=>($data->switch)?0:1))';
    public $switchButtonIcon = 'flaticon-eye';
    public $switchButtonOptions = array('class' => 'switch');
    public $deleteButtonIcon = 'flaticon-delete';
    public $deleteButtonLabel;
    public $deleteButtonUrl = 'Yii::app()->controller->createUrl("delete",array("model"=>$data->search()->modelClass, "id"=>$data->primaryKey))';
    public $deleteButtonOptions = array('class' => 'delete');
    public $deleteConfirmation;
    public $buttonVisible = array();
    public $afterDelete;
    public $hidden = array();

    public function init() {

        $this->initDefaultButtons();
        foreach ($this->buttons as $id => $button) {
            if (strpos($this->template, '{' . $id . '}') === false)
                unset($this->buttons[$id]);
            elseif (isset($button['click'])) {
                if (!isset($button['options']['class']))
                    $this->buttons[$id]['options']['class'] = $id;
                if (!($button['click'] instanceof CJavaScriptExpression))
                    $this->buttons[$id]['click'] = new CJavaScriptExpression($button['click']);
            }
        }

        $this->registerClientScript();
    }

    /**
     * Initializes the default buttons (view, update and delete).
     */
    protected function initDefaultButtons() {
        if ($this->header === null)
            $this->header = Yii::t('app', 'OPTIONS');
        if ($this->viewButtonLabel === null)
            $this->viewButtonLabel = Yii::t('app', 'VIEW');
        if ($this->updateButtonLabel === null)
            $this->updateButtonLabel = Yii::t('app', 'UPDATE', 1);
        if ($this->deleteButtonLabel === null)
            $this->deleteButtonLabel = Yii::t('app', 'DELETE');
        if ($this->switchButtonLabel === null)
            $this->switchButtonLabel = Yii::t('app', 'SWITCH');
        // if ($this->fastupdateButtonLabel === null)
        //     $this->fastupdateButtonLabel = Yii::t('core', 'dialog_update');
        if ($this->deleteConfirmation === null)
            $this->deleteConfirmation = Yii::t('app', 'YOU_ARE_CURE_DEL_ITEM');
        if ($this->group) {
            $this->headerHtmlOptions = array('style' => 'width:50px');
        }
        foreach (array('switch', 'view', 'update', 'delete') as $id) {

            $button = array(
                'label' => $this->{$id . 'ButtonLabel'},
                'url' => $this->{$id . 'ButtonUrl'},
                'icon' => $this->{$id . 'ButtonIcon'},
                'options' => $this->{$id . 'ButtonOptions'},
            );
            if (isset($this->buttons[$id]))
                $this->buttons[$id] = array_merge($button, $this->buttons[$id]);
            else
                $this->buttons[$id] = $button;
        }

        if ($this->afterDelete === null)
            $this->afterDelete = 'function(){}';
        if (!isset($this->buttons['switch']['click'])) {
            if (Yii::app()->request->enableCsrfValidation) {
                $csrfTokenName = Yii::app()->request->csrfTokenName;
                $csrfToken = Yii::app()->request->csrfToken;
                $csrf = "\n\t\tdata:{ '$csrfTokenName':'$csrfToken' },";
            }
            else
                $csrf = '';

            $this->buttons['switch']['click'] = "function() {
                     var th = this
                            var afterDelete = $this->afterDelete;
                            $('#{$this->grid->id}').yiiGridView('update', {
                                    type: 'POST',
                                    url: $(th).attr('href'),
                                    $csrf
                                    success: function(data) {
                                            $('#{$this->grid->id}').yiiGridView('update');
                                            afterDelete(th, true, data);
                                
                                    },
                                    error: function(XHR) {
                                            return afterDelete(th, false, XHR);
                                    }
                            });
                            return false;
            }";
        }


        if (!isset($this->buttons['delete']['click'])) {

            if (Yii::app()->request->enableCsrfValidation) {
                $csrfTokenName = Yii::app()->request->csrfTokenName;
                $csrfToken = Yii::app()->request->csrfToken;
                $csrf = "\n\t\tdata:{ '$csrfTokenName':'$csrfToken' },";
            }
            else
                $csrf = '';


            if (is_string($this->deleteConfirmation))
            // $confirmation = "if(!confirm(" . CJavaScript::encode($this->deleteConfirmation) . ")) return false;";
                $confirmation = "
                jQuery('#{$this->grid->id}').yiiGridView('confirm', {
                    message:" . CJavaScript::encode($this->deleteConfirmation) . ",
                        afterDelete:" . $this->afterDelete . ",
                            gridId:" . $this->grid->id . ",
                                uri:" . $this->buttons['delete']['url'] . ",
                                csrf:{ '$csrfTokenName':'$csrfToken' }
                        
}); return false;";
            else
                $confirmation = '';

            $this->buttons['delete']['click'] = "function() {
            var th = this
            $('body').append('<div id=\"dialog\"></div>');
            $('#dialog').dialog({
                modal: true,
                resizable: false,
                title:$(th).attr('title'),
                open:function(){
                    $(this).html(" . CJavaScript::encode($this->deleteConfirmation) . ");
                },
                close: function (event, ui) {
                    $(this).remove();
                },
                buttons:[{
                        text:'OK',
                        'class':'btn btn-success btn-sm',
                        click:function(){
                            $(this).dialog('close');
                            var afterDelete = $this->afterDelete;
                            $('#{$this->grid->id}').yiiGridView('update', {
                                    type: 'POST',
                                    url: $(th).attr('href'),
                                    $csrf
                                    success: function(data) {
                                            $('#{$this->grid->id}').yiiGridView('update');
                                            afterDelete(th, true, data);
                                
                                    },
                                    error: function(XHR) {
                                            return afterDelete(th, false, XHR);
                                    }
                            });
                        }
                    },{
                        text:'Cancel',
                        'class':'btn btn-default btn-sm',
                        click:function(){
                            $(this).dialog('close');
                            
                        }
                    }]
            });
            return false;
}";
        }
    }

    /*
      $dd = $this->hidden[$id];
      print_r($data->primaryKey);
      if($this->hidden){

      }else{

      }
     */

    protected function renderDataCellContent($row, $data) {
        $hidden = array();
        $tr = array();
        ob_start();
        $this->group=false;
        if (!$this->group) {
            echo CHtml::openTag('div', array('class' => 'btn-group'));
            foreach ($this->buttons as $id => $button) {
                if (isset($this->hidden[$id])) {
                    if (in_array($data->primaryKey, $this->hidden[$id])) {
                        $hidden[] = "{" . $id . "}";
                    }
                }
             //   echo CHtml::openTag('li');
                $this->renderButton($id, $button, $row, $data);
              //  echo CHtml::closeTag('li');
                $tr['{' . $id . '}'] = ob_get_contents();
                ob_clean();
            }
            echo CHtml::closeTag('div');
        } else {

            echo CHtml::openTag('div', array('class' => 'btn-group'));

?>
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fa fa-bars"></i> <span class="caret"></span>
  </button>
<?php

            echo CHtml::openTag('ul', array('class' => 'dropdown-menu pull-right'));
            foreach ($this->buttons as $id => $button) {
                if (isset($this->hidden[$id])) {
                    if (in_array($data->primaryKey, $this->hidden[$id])) {
                        $hidden[] = "{" . $id . "}";
                    }
                }
                echo CHtml::openTag('li');
                $this->renderGroupButton($id, $button, $row, $data);
                echo CHtml::closeTag('li');
                $tr['{' . $id . '}'] = ob_get_contents();
                ob_clean();
            }

            echo CHtml::closeTag('ul');
   
            // echo '<li><a href="" data-toggle="dropdown">OPEN</a><ul class="dropdown-menu pull-right"><li>sss</li></ul></li>';
            echo CHtml::closeTag('div');
        }
        ob_end_clean();
        echo strtr(str_replace($hidden, "", $this->template), $tr);
    }

    /**
     * Renders a link button.
     * @param string $id the ID of the button
     * @param array $button the button configuration which may contain 'label', 'url', 'imageUrl' and 'options' elements.
     * See {@link buttons} for more details.
     * @param integer $row the row number (zero-based)
     * @param mixed $data the data object associated with the row
     */
    protected function renderButton($id, $button, $row, $data) {



        if (isset($button['visible']) && !$this->evaluateExpression($button['visible'], array('row' => $row, 'data' => $data)))
            return;
        $label = isset($button['label']) ? $button['label'] : $id;
        $url = isset($button['url']) ? $this->evaluateExpression($button['url'], array('data' => $data, 'row' => $row)) : '#';
        $options = isset($button['options']) ? $button['options'] : array();
        if (!isset($options['title']))
            $options['title'] = $label;
        // print_r($button);die();
//iconClass = (isset(Yii::app()->settings->get('core', 'core_tableToolbarClass'))) ? Yii::app()->settings->get('core', 'core_tableToolbarClass') : 'tablectrl_xlarge2';

        $options['class'] = $options['class'] . ' btn btn-default ' . Yii::app()->settings->get('core', 'btn_grid_size');

        if (isset($button['icon']) && is_string($button['icon'])) {
            if ($id == 'switch') {
                if ($data->switch) {
                    $button['icon'] = 'flaticon-eye';
                    $options['class'] = 'switch btn btn-success ' . Yii::app()->settings->get('core', 'btn_grid_size');
                } else {
                    $button['icon'] = 'flaticon-eye-close';
                    $options['class'] = 'switch btn btn-default ' . Yii::app()->settings->get('core', 'btn_grid_size');
                }
            }
            echo CHtml::link(CHtml::openTag('i', array('class' => $button['icon'])) . '' . CHtml::closeTag('i'), $url, $options);
        } else {
            echo CHtml::link($label, $url, $options);
        }
    }

    public function renderHeaderCell() {
        $this->headerHtmlOptions['width'] = 'auto';
        parent::renderHeaderCell();
    }

    protected function renderGroupButton($id, $button, $row, $data) {

        if (isset($button['visible']) && !$this->evaluateExpression($button['visible'], array('row' => $row, 'data' => $data)))
            return;
        $label = isset($button['label']) ? $button['label'] : $id;
        $url = isset($button['url']) ? $this->evaluateExpression($button['url'], array('data' => $data, 'row' => $row)) : '#';
        $options = isset($button['options']) ? $button['options'] : array();
        if (!isset($options['title']))
            $options['title'] = $label;

        $options['class'] = $options['class'];

        if (isset($button['icon']) && is_string($button['icon'])) {
            if ($id == 'switch') {
                if ($data->switch) {
                    $button['icon'] = 'flaticon-eye';
                    $button['label'] = 'Скрыть';
                } else {
                    $button['icon'] = 'flaticon-eye-close';
                    $button['label'] = 'Показать';
                }
            }
            echo CHtml::link(CHtml::openTag('span', array('class' => $button['icon'])) . CHtml::closeTag('span') . ' ' . $button['label'], $url, array('class' => $options['class']));
        } else {
            echo CHtml::link($label, $url, $options);
        }
    }

}

