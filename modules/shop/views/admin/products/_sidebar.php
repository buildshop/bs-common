<script>
                $(function(){
                $('#tab-container').tabs({
                    collapsible: true,

                    beforeActivate: function (event, ui) {
                        window.location.hash = ui.newPanel.selector;
                    }
                });
        
            });
    </script>
<div id="tab-container" class="tab-container">
            <ul class="iconsLine ic3 etabs">
                <li><a href="#menu"><span class="icon-home-2"></span></a></li>
                <li><a href="#search"><span class="icon-search"></span></a></li>
                <li><a href="#settings"><span class="icon-cog"></span></a></li>
            </ul>

            <div class="divider"><span></span></div>
            <div id="menu"><?php

    $this->widget('zii.widgets.CMenu', array(
        'htmlOptions'=>array('class'=>'subNav'),
        'activeCssClass'=>'activeli',
        'lastItemCssClass'=>'noBorderB',
		'items'=>array(
			array(
				'label'=>Rights::t('core', 'Assignments'),
				'url'=>array('assignment/view'),
				'active'=>(Yii::app()->controller->action->id=='view')?true:false
			),
			array(
				'label'=>Rights::t('core', 'Permissions'),
				'url'=>array('authItem/permissions'),
				'active'=>(Yii::app()->controller->action->id=='permissions')?true:false
			),
			array(
				'label'=>Rights::t('core', 'Roles'),
				'url'=>array('authItem/roles'),
                                'active'=>(Yii::app()->controller->action->id=='roles')?true:false
			),
			array(
				'label'=>Rights::t('core', 'Tasks'),
				'url'=>array('authItem/tasks'),
				'active'=>(Yii::app()->controller->action->id=='tasks')?true:false
			),
			array(
				'label'=>Rights::t('core', 'Operations'),
				'url'=>array('authItem/operations'),
				'active'=>(Yii::app()->controller->action->id=='operations')?true:false
			),
		)
    ));
    ?></div>
            <div id="search"  class="sideWidget">
                


                <div class="formRow">
        
                    <?php echo CHtml::textField('search', '', array('onkeyup'=>'$("#ShopCategoryTreeFilter").jstree("search", $(this).val())')); ?>

                </div>    
                
                
                

	<?php
		$this->beginWidget('zii.widgets.jui.CJuiButton', array(
			'buttonType'=>'buttonset',
			'name'=>'tree-set',
			'htmlOptions'=>array(
				'style'=>'padding-top:2px;',
			)
		));



		$this->endWidget();

?>
                        <div class="clear"></div>
                        <?php
// Create jstree to filter products
$this->widget('ext.jstree.SJsTree', array(
	'id'=>'ShopCategoryTreeFilter',
	'data'=>ShopCategoryNode::fromArray(ShopCategory::model()->findAllByPk(1), array('displayCount'=>true)),
	'options'=>array(
		'core'=>array('initially_open'=>'ShopCategoryTreeFilterNode_1'),
		'plugins'=>array('themes','html_data','ui','crrm', 'search'),
		'cookies'=>array(
			'save_selected'=>false,
		),
	),
));

// Category id to select in sidebar.
$activeCategoryId = Yii::app()->request->getQuery('category', 0);

if(is_array($activeCategoryId))
	$activeCategoryId=0;

Yii::app()->getClientScript()->registerScript('insertAllCategory', '
$("#ShopCategoryTreeFilter").bind("loaded.jstree", function (event, data) {
	$(this).jstree("create",-1,false,{attr:{id:"ShopCategoryTreeFilterNode_0"}, data:{title:"'.Yii::t('ShopModule.admin', 'Все категории').'"}},false,true);
	$(this).jstree("select_node","#ShopCategoryTreeFilterNode_'.$activeCategoryId.'");
});
');

Yii::app()->getClientScript()->registerCss("ShopCategoryTreeStyles","
	#ShopCategoryTree { width:90% }
	#ShopCategoryTreeFilter {width: 255px}
");

?>
                
                
                
                
                
                
                
                
            </div>  <div class="divider"><span></span></div>
        </div>
   




