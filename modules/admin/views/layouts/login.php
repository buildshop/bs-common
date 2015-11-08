<?php
$min = YII_DEBUG ? '' : '.min';
$adminAssetsUrl = Yii::app()->getModule('admin')->assetsUrl;
$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery');
$cs->registerCoreScript('jquery.ui');
$cs->registerCssFile($adminAssetsUrl . '/css/bootstrap.min.css');
$cs->registerCssFile($adminAssetsUrl . '/css/font-awesome.min.css');
$cs->registerCssFile($adminAssetsUrl . '/css/theme.css');
?>
<!doctype html> 
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::app()->charset ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <title><?php echo Yii::t('app', 'ADMIN_PANEL', array('{sitename}' => Yii::app()->settings->get('core', 'site_name'))) ?></title>
    </head>
    <body>

        <?php echo $content; ?>

    </body>
</html>
