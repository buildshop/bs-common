<?php

class SystemSettingsWebTest extends WebTestCase {

    public function testSystemSettings() {
        $siteName = microtime();
        $ppa = rand(3, 10);

        $this->adminLogin();
        $this->open('/admin/core/settings');
        $this->type('id=SettingsCoreForm_site_name', $siteName);
        $this->type('id=SettingsCoreForm_pagenum', $ppa);
        $this->check('id=SettingsCoreForm_multi_language');
        $this->clickAndWait('css=.buttons > input.buttonS');

        Yii::import('mod.core.models.SettingsCoreForm');
        Yii::app()->settings->init();
        $model = new SettingsCoreForm;
        $this->assertEquals($model->core_site_name, $siteName);
        $this->assertEquals($model->core_pagenum, $ppa);
        $this->assertEquals(Yii::app()->settings->get('core', 'site_name'), $siteName);
        $this->assertEquals(Yii::app()->settings->get('core', 'multi_language'), 1);
    }

    public function testTitle() {
        Yii::app()->settings->set('core', array(
            'site_name' => microtime()
        ));
        $this->open('/');
        $this->assertEquals(Yii::app()->settings->get('core', 'site_name'), $this->getTitle());

        // Find any active product
        $product = ShopProduct::model()->active()->find();
        $this->assertTrue($product instanceof ShopProduct);

        // Open product page
        $this->open(Yii::app()->createUrl('/shop/product/view', array('seo_alias' => $product->seo_alias)));

        $this->assertEquals($product->name . ' / ' . Yii::app()->settings->get('core', 'site_name'), $this->getTitle());
    }

}
