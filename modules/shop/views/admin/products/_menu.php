
<?php
$c = Yii::app()->controller->id;

    $this->widget('zii.widgets.CMenu', array(
        'htmlOptions'=>array('class'=>'subNav'),
        'activeCssClass'=>'activeli',
        'lastItemCssClass'=>'noBorderB',
		'items'=>array(
			array(
				'label'=>Yii::t('core', 'Products'),
				'url'=>array('admin/products/index'),
				'active'=>($c=='admin/products')?true:false
			),
			array(
				'label'=>Yii::t('core', 'Categories'),
				'url'=>array('admin/category/index'),
				'active'=>($c=='admin/category')?true:false
			),
			array(
				'label'=>Yii::t('core', 'Discounts'),
				'url'=>array('admin/category/index'),
                                'active'=>($c=='roles')?true:false
			),
			array(
				'label'=>Yii::t('core', 'Brands'),
				'url'=>array('admin/category/index'),
				'active'=>($c=='tasks')?true:false
			),
			array(
				'label'=>Yii::t('core', 'Attributes'),
				'url'=>array('admin/category/index'),
				'active'=>($c=='operations')?true:false
			),
		)
    ));

    if(isset($this->clips['sidebarHelpText']))
    {
        echo '<div class="divider"><span></span></div><div class="hint" style="margin-top:25px;padding-right:5px;padding-left:20px;">'.$this->clips['sidebarHelpText'].'</div>'; 
    }
