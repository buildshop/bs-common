


	<?php if(!empty($this->model->products)): ?>
	<table width="100%" cellpadding="3" cellspacing="3" class="compareTable">
		<thead>
		<tr>
			<td width="200px"></td>
			<?php foreach($this->model->products as $p): ?>
			<td>
				<div class="products_list wish_list">
					<?php $this->renderPartial('_product', array('data'=>$p)) ?>
				</div>
			</td>
			<?php endforeach; ?>
		</tr>
		</thead>
		<?php if(!empty($this->model->attributes)): ?>
		<tbody>
			<?php foreach($this->model->attributes as $attribute): ?>
			<tr>
				<td class="attr"><?php echo $attribute->title ?></td>
				<?php foreach($this->model->products as $product): ?>
				<td>
					<?php
					$value=$product->{'eav_'.$attribute->name};
					echo $value===null ? Yii::t('ShopModule.core','Не указано') : $value;
					?>
				</td>
				<?php endforeach; ?>
			</tr>
			<?php endforeach ?>
		</tbody>
		<?php endif ?>
	</table>
	<?php else: ?>
		<?php echo Yii::t('ShopModule.core','Нет результатов.'); ?>
	<?php endif ?>
