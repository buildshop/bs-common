<?php
$min = YII_DEBUG ? '' : '.min';
$cs = Yii::app()->clientScript;
//$cs->registerCoreScript('jquery');
//$cs->registerCoreScript('jquery.ui');
$cs->registerCssFile($this->baseAssetsUrl . "/css/icons{$min}.css");
$cs->registerCssFile($this->assetsUrl . "/css/bootstrap.css");
$cs->registerCssFile($this->assetsUrl . "/css/bootstrap-theme.css");

if (Yii::app()->hasModule('shop')) {
    Yii::import('mod.shop.ShopModule');
    Yii::import('mod.shop.components.SCompareProducts');
    Yii::import('mod.shop.models.ShopWishlist');
}
Yii::import('ext.jgrowl.Jgrowl');
Jgrowl::register();

$cs->registerScript('app', "
var token = '" . Yii::app()->request->csrfToken . "';
var lang_name = '" . Yii::app()->language . "';
    
", CClientScript::POS_HEAD);

$this->widget('ext.uniform.UniformWidget');
?>
<!doctype html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=<?= Yii::app()->charset ?>" />
        <title><?= CHtml::encode($this->pageTitle) ?></title>
        <meta name="description" content="<?= $this->pageDescription; ?>" />
        <meta name="keywords" content="<?= $this->pageKeywords; ?>" />
        <link rel="stylesheet" href="<?= $this->assetsUrl ?>/css/jquery-ui.css" type="text/css" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link href='http://fonts.googleapis.com/css?family=Ubuntu:400,400italic&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <div id="wrap">
            <div id="main">
                <div id="header">
                    <div id="header-top">
                        <div class="indent">
                            <span class="left-side-top-nav"><span class="icon-medium icon-point-right"></span><a href="//demo.corner.com.ua">Демо</a>
                            </span>

                            <ul class="float-r" id="top-nav">


                                <?php
                                $this->widget('users.widgets.login.LoginWidget');
                                ?>

                                <?php if (Yii::app()->hasModule('shop')) { ?>
                                    <li>
                                        <span class="icon-medium icon-contract-2"></span><a href="<?php echo Yii::app()->createUrl('/shop/compare/index') ?>">
                                            <?php echo Yii::t('ShopModule.default', 'COMPARE', array('{c}' => SCompareProducts::countSession())) ?>
                                        </a>
                                    </li>
                                    <li>
                                        <span class="icon-medium icon-heart"></span><a href="<?php echo Yii::app()->createUrl('/shop/wishlist/index') ?>">
                                            <?php echo Yii::t('ShopModule.default', 'WISHLIST', array('{c}' => ShopWishlist::countByUser())) ?>
                                        </a>                  
                                    </li>
                                <?php } ?>
                                <?php if(isset(Yii::app()->currency->currencies)){ ?>
                                <li>
                                    
                                    <div id="currency">
                                        <?php echo Yii::t('core', 'Валюта:') ?>
                                        <?php
                                        foreach (Yii::app()->currency->currencies as $currency) {
                                            echo CHtml::ajaxLink($currency->symbol, '/shop/ajax/activateCurrency/' . $currency->id, array(
                                                'success' => 'js:function(){window.location.reload(true)}',
                                                    ), array('id' => 'curreny_' . $currency->id, 'class' => Yii::app()->currency->active->id === $currency->id ? 'active' : ''));
                                        }
                                        ?>
                                    </div>
                                </li>
                                <?php } ?>
                            </ul>



                            <div class="clr"></div>
                        </div>
                    </div>
                    <div id="header-center">
                        <div class="indent">
                            <a href="/" class="logo"></a>
                            <div class="head-text">
                            <?php $this->widget('shop.widgets.search.SearchWidget'); ?>
                                <?php //Yii::app()->blocks->get('fly', 1);  ?></div>
                            <?php

                            if (Yii::app()->hasModule('cart')) $this->widget('cart.widgets.cart.CartWidget');

                            ?>
                            <div class="clr"></div>
                        </div>
                    </div>
                    <div id="header-nav">
                        <div class="indent">


                            <div id="cssmenu">
                                <?php
                                //Yii::import('mod.shop.models.ShopCategory');
                                $items = ShopCategory::model()->language(Yii::app()->language->active)->findByPk(1);
                                if (isset($items)) {
                                    $menuArray = $items->menuArray();
                                } else {
                                    die("не могу найти root category.");
                                }


                                if (isset($menuArray['items'])) {
                                    $this->widget('ext.mbmenu.MbMenu', array(
                                        'cssFile' => Yii::app()->theme->baseUrl . '/assets/css/menu.css',
                                        'htmlOptions' => array('class' => '', 'id' => ''),
                                        'items' => $menuArray['items'])
                                    );
                                }
                                ?></div>



                            <div class="clr"></div>
                        </div>
                    </div>
                </div>
                <div id="content" class="fullcontainer">
         
                            <?php
                            $this->widget('Breadcrumbs', array(
                                'links' => $this->breadcrumbs,
                            ));
                            ?>

                            <?php
                            if (Yii::app()->user->hasFlash('success')) {
                                Yii::app()->tpl->alert('success', Yii::app()->user->getFlash('success'));
                            }
                            if (Yii::app()->user->hasFlash('failure')) {
                                Yii::app()->tpl->alert('failure', Yii::app()->user->getFlash('failure'));
                            }
                            ?>
                            <?php //echo $content ?>
                
                </div>
            </div>
        </div>
        <div id="footer">
            <div class="indent"></div>
        </div>

    </body>
</html>

