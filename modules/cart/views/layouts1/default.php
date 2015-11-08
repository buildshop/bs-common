<?php
$min = YII_DEBUG ? '' : '.min';
$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery');
$cs->registerCoreScript('jquery.ui');
$cs->registerScriptFile($this->baseAssetsUrl . "/js/application.js");
$cs->registerCssFile($this->baseAssetsUrl . "/css/icons{$min}.css");
$cs->registerCssFile($this->assetsUrl . "/css/bootstrap.css");
$cs->registerCssFile($this->assetsUrl . "/css/bootstrap-theme.css");
$cs->registerScriptFile($this->assetsUrl . "/js/bootstrap.js");
Yii::import('ext.jgrowl.Jgrowl');
Jgrowl::register();
/**
 * Global js vars
 */
$cs->registerScript('app', "
var token = '" . Yii::app()->request->csrfToken . "';
var lang_name = '" . Yii::app()->language . "';
", CClientScript::POS_HEAD);

$cs->registerScript('app2', "
cart.spinnerRecount = true;
app.language = 'ru';
app.token = '" . Yii::app()->request->csrfToken . "';
app.debug = true;
app.flashMessage = true;
", CClientScript::POS_HEAD);

$this->widget('ext.uniform.UniformWidget', array(
    'theme' => 'default',
));

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="<?= Yii::app()->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= Html::encode($this->pageTitle) ?></title>
        <meta name="description" content="<?= $this->pageDescription; ?>" />
        <meta name="keywords" content="<?= $this->pageKeywords; ?>" />
        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]>
        <script src="<?= $this->assetsUrl ?>/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->
         <!--<script src="<?= $this->assetsUrl ?>/js/ie-emulation-modes-warning.js"></script>-->
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]
        <!--<?= $this->assetsUrl ?>/js/bootstrap.min.js-->
          <!--  <script src="<?= $this->assetsUrl ?>/js/bootstrap.js"></script>-->
    </head>
    <body>
        <?php
        //$this->widget('ext.admin.sitePanel.PanelWidget');
        ?>
        <!-- Fixed navbar -->
        <?php $this->renderPartial('mod.shop.views.layouts._navbar'); ?>

        <div class="container padding-t" role="main">
                <?php
            $this->widget('Breadcrumbs', array(
                'links' => $this->breadcrumbs,
            ));
            ?>

            <?= $content ?>

            <?php if (Yii::app()->hasModule('compare')) {
                 
                Yii::import('mod.compare.components.*');
                Yii::import('mod.compare.CompareModule');
                ?>
                <li>
                    <span class="icon-medium icon-contract-2"></span><a href="<?php echo Yii::app()->createUrl('/compare/default/index') ?>">
                        <?php echo Yii::t('CompareModule.default', 'COMPARE', array('{c}' => CompareProductsComponent::countSession())) ?>
                    </a>
                </li>
            <?php } ?>
            <?php if (Yii::app()->hasModule('wishlist')) {
                Yii::import('mod.wishlist.WishlistModule');
                Yii::import('mod.wishlist.models.*');
                ?>
                <li>
                    <span class="icon-medium icon-heart"></span><a href="<?php echo Yii::app()->createUrl('/wishlist/default/index') ?>">
                        <?php echo Yii::t('WishlistModule.default', 'WISHLIST', array('{c}' => Wishlist::countByUser())) ?>
                    </a>                  
                </li>
            <?php } ?>


        </div> <!-- /container -->
        <?php $this->renderPartial('//layouts/_footer'); ?>


        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="<?= $this->assetsUrl ?>/js/ie10-viewport-bug-workaround.js"></script>
    </body>
</html>
