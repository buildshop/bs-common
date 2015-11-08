<?php

/**
 * @var ShopProduct $data
 */
?>

<div class="product_block">
	<div class="image">
		<?php
		if($data->mainImage)
			$imgSource = $data->mainImage->getUrl('190x150');
		else
			$imgSource = 'http://placehold.it/190x150';
		echo CHtml::link(CHtml::image($imgSource, $data->mainImageTitle), array('Product/view', 'seo_alias'=>$data->seo_alias), array('class'=>'thumbnail'));
		?>
	</div>
	<div class="name">
		<?php echo CHtml::link(CHtml::encode($data->name), array('Product/view', 'seo_alias'=>$data->seo_alias)) ?>
	</div>
	<div class="price">

		<?php echo $data->priceRange() ?>
	</div>
	<div class="actions">
			<?php
				echo CHtml::form(array('/orders/cart/add'));
				echo CHtml::hiddenField('product_id', $data->id);
				echo CHtml::hiddenField('product_price', $data->price);
				echo CHtml::hiddenField('use_configurations', $data->use_configurations);
				echo CHtml::hiddenField('currency_rate', Yii::app()->currency->active->rate);
				echo CHtml::hiddenField('configurable_id', 0);
				echo CHtml::hiddenField('quantity', 1);

		if($data->getIsAvailable())
		{
			echo CHtml::ajaxSubmitButton(Yii::t('ShopModule.core','Купить'), array('/orders/cart/add'), array(
				'id'=>'addProduct'.$data->id,
				'dataType'=>'json',
				'success'=>'js:function(data, textStatus, jqXHR){processCartResponseFromList(data, textStatus, jqXHR, "'.Yii::app()->createAbsoluteUrl('/shop/Product/view', array('seo_alias'=>$data->seo_alias)).'")}',
			), array('class'=>'blue_button'));
		}
		else
		{
			echo CHtml::link('Нет в наличии', '#', array(
				'onclick' => 'showNotifierPopup('.$data->id.'); return false;',
				'class'   => 'notify_link',
			));
		}


			?>
			<button class="small_silver_button" title="<?=Yii::t('core','Сравнить')?>" onclick="return addProductToCompare(<?php echo $data->id ?>);"><span class="compare">&nbsp</span></button>
			<button class="small_silver_button" title="<?=Yii::t('core','В список желаний')?>" onclick="return addProductToWishList(<?php echo $data->id ?>);"><span class="heart">&nbsp;</span></button>
			<?php echo CHtml::endForm() ?>
	</div>
</div>