<?php
if ($this->isAjax) {
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div', array('class' => 'wrapper'));
}
?>
<script>
    $(function() {    
        $('.dropdown-toggle').dropdown();
        $(".datepicker").datepicker({
            // 'flat':true,
            dateFormat:'yy-mm-dd',
            constrainInput: false,
            inline: true
        });

    });
</script>





<script>
    function customActions(that){
        var action = $('.CA option:selected').val();
        customAction(action);
    }
    function customAction(name){
        if(name=='status-hide'){
            setProductsStatus(0)
        } else if (name=='status-show'){
            setProductsStatus(1)
        } else if (name=='delete'){
            alert(name);
        }else{
               
        }
    }
        
</script>


<?php
echo Yii::getPathOfAlias('upload_dir.product');


$this->widget('ext.fancybox.Fancybox', array('target' => 'td.image a'));
$this->widget('ext.adminList.GridView', array(//zii.widgets.grid.CGridView //ext.adminList.GridView
    'dataProvider' => $dataProvider,
    'filter' => $model,
    'name' => $this->pageName,
    'filterCssClass' => 'filters',
    'customActions' => array(
        array(
            'label' => Yii::t('ShopModule.admin', 'Активен'),
            'url' => 'javascript:void(0)',
            'linkOptions' => array(
                'onClick' => 'return setProductsStatus(1, this); return false;',
            ),
        ),
        array(
            'label' => Yii::t('ShopModule.admin', 'Не активен'),
            'url' => 'javascript:void(0)',
            'linkOptions' => array(
                'onClick' => 'return setProductsStatus(0, this); return false;',
            ),
        ),
        array(
            'label' => Yii::t('ShopModule.admin', 'Назначить категории'),
            'url' => 'javascript:void(0)',
            'linkOptions' => array(
                'onClick' => 'return showCategoryAssignWindow(this); return false;',
            ),
        ),
        array(
            'label' => Yii::t('ShopModule.admin', 'Копировать'),
            'url' => 'javascript:void(0)',
            'linkOptions' => array(
                'onClick' => 'return showDuplicateProductsWindow(this); return false;',
            ),
        ),
        array(
            'label' => Yii::t('ShopModule.admin', 'Установить единую цену'),
            'url' => 'javascript:void(0)',
            'linkOptions' => array(
                'onClick' => 'return setProductsPrice(this); return false;',
            ),
        )
    ),
));
?>

<?php


if ($this->isAjax) echo Html::closeTag('div');

/* * [{"id":1,"text":"Root node","children":[{"id":2,"text":"Child node 1"},{"id":3,"text":"Child node 2"}]}]
 * fixed for action category_assign_window

  $this->widget('mod.shop.widgets.jstree.JsTree', array(
  'id' => 'ShopCategoryTreeFilter',
  'data' => array(),
  'options' => array(
  'plugins' => array('themes', 'html_data', 'ui', 'crrm', 'checkbox', 'search'),
  'cookies' => array(
  'save_selected' => false,
  ),
  ),
  )); */
?>