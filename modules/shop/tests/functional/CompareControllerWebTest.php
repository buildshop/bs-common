<?php

/**
 * Test add/removing products to compare
 */
class CompareControllerWebTest extends WebTestCase
{
	public function testCompareIsWorkingOk()
	{
		$product = ShopProduct::model()->active()->find();
		$this->assertTrue($product instanceof ShopProduct);
		$this->open(Yii::app()->createUrl('/shop/Product/view', array('url'=>$product->url)));
		$this->clickAndWait('css=div.silver_clean.silver_button > button');
		$this->assertTrue($this->isTextPresent('Продукт успешно добавлен в список сравнения'));
		$this->clickAndWait('xpath=//a[contains(.,"Товары на сравнение")]');
		$this->assertTrue($this->isTextPresent(str_replace('  ',' ',$product->name)));
		$this->clickAndWait('link=Удалить');
		$this->assertTrue($this->isTextPresent('Нет результатов'));
	}
}
