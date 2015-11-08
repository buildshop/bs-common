<?php



Yii::app()->tpl->alert('info',Yii::t('core','Чтобы атрибут отображался в товарах его необходимо добавить к необходимому {productType}',array('{productType}'=>Html::link('типу товара','/admin/shop/productType'))));
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => '')
));
 ?>


	<?php echo $form->tabs(); ?>

<?php Yii::app()->tpl->closeWidget(); ?>