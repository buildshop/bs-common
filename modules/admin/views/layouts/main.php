<?php
Yii::import('mod.core.components.yandexTranslate');

$adminAssetsUrl = Yii::app()->getModule('admin')->assetsUrl;
//$min = YII_DEBUG ? '' : '.min';
$package = Yii::app()->package;

$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery');
$cs->registerCoreScript('jquery.ui');
$cs->registerCoreScript('cookie');
$cs->registerCoreScript('maskedinput');
$cs->registerCssFile($adminAssetsUrl . "/css/flaticon.css");
//$cs->registerCssFile("//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css");
$cs->registerCssFile($adminAssetsUrl . "/css/bootstrap.css");
$cs->registerCssFile($adminAssetsUrl . "/css/bootstrap-theme.css");
$cs->registerCssFile($adminAssetsUrl . "/css/dashboard.css");
$cs->registerCssFile($adminAssetsUrl . "/css/breadcrumbs.css");
$cs->registerCssFile($adminAssetsUrl . "/css/bootstrap-select.css");
$cs->registerCssFile($adminAssetsUrl . "/css/jquery-ui.css");

$cs->registerScriptFile($this->baseAssetsUrl . "/js/application.js");
$cs->registerScriptFile($adminAssetsUrl . "/js/bootstrap.js");
$cs->registerScriptFile($adminAssetsUrl . "/js/bootstrap-select.js");



$cs->registerScriptFile($adminAssetsUrl . '/js/translitter.js');
$cs->registerScriptFile($adminAssetsUrl . '/js/init_translitter.js');
$cs->registerScriptFile($adminAssetsUrl . '/js/counters.js');
// jGrowl
Yii::import('ext.jgrowl.Jgrowl');
Jgrowl::register();

$cs->registerScript('app', "
var translate_object_url = " . Yii::app()->settings->get('core', 'translate_object_url') . ";
var yandex_translate_apikey = '" . yandexTranslate::API_KEY . "';

app.token = '" . Yii::app()->request->csrfToken . "';
app.language = '" . Yii::app()->language . "';
app.message = {
    error:{
        404:'" . Yii::t('error', '404') . "'
    },
}
", CClientScript::POS_HEAD);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::app()->charset ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <title><?php echo Yii::t('app', 'ADMIN_PANEL', array('{sitename}' => Yii::app()->settings->get('core', 'site_name'))) ?></title>
        <link rel="icon" type="image/png" href="<?= $adminAssetsUrl; ?>/images/favicon.png" />
    </head>
    <body>
        <script>
            // $('.selectpicker').selectpicker();
        </script>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="http://buildshop.net/" target="_blank"></a>
                </div>
                <div id="navbar2" class="navbar-collapse collapse">
                    <?php $this->widget('ext.mbmenu.AdminMenu'); //ext.mbmenu.AdminMenu ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="/">Витрина</a></li>
                        <li><?= Html::link('Выход ' . Yii::app()->user->login, array('/users/logout'), ['data-method' => "post"]) ?></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div id="wrapper">

            <!-- Sidebar -->
            <div id="sidebar-wrapper">


                <ul id="sidebar_menu" class="sidebar-nav">
                    <li class="sidebar-header">
                        <div id="menu-toggle">
                            <b><?= Yii::app()->user->login ?> <span class="caret"></span></b>
                            <i class="flaticon-menu"></i>
                        </div>

                    </li>
                </ul>



                <?php if (Yii::app()->controller->module->sidebar) { ?>
                    <?php
                    $asm = $this->module->adminSidebarMenu;

                    if ($asm) {
                        $this->widget($this->module->aliasAdminSidebarMenu, array(
                            'htmlOptions' => array('class' => 'sidebar-nav'),
                            'activeCssClass' => 'active',
                            'lastItemCssClass' => '',
                            'items' => $asm
                        ));
                        ?>

                    <?php } ?> 
                <?php } ?>
                <?php //foreach ($this->context->module->nav as $nav) { ?>
                <? //= Html::link($nav['label'] . '<i class="fa ' . $nav['icon'] . '"></i>', $nav['url'], $nav['htmlOptions'])       ?>
                <?php //} ?>


            </div>
            <!-- /#sidebar-wrapper -->

            <!-- Page Content -->
            <div id="page-content-wrapper">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-lg-12 module-header pdd">
                            <div class="pull-left">

                                <h1>
                                    <i class="fa <?= $this->module->info['icon'] ?>"></i>
                                    <?= Html::encode($this->pageName) ?>
                                </h1>
                            </div>


                            <div class="pull-right">
                                <?php
                                if (!isset($this->topButtons)) {
                                    echo Html::link(Yii::t('app', 'CREATE', 0), array('create'), array('title' => Yii::t('app', 'CREATE', 0), 'class' => 'btn btn-success'));
                                } else {
                                    if ($this->topButtons == true) {
                                        if (is_array($this->topButtons)) {
                                            foreach ($this->topButtons as $button) {
                                                if (isset($button['icon'])) {
                                                    $icon = '<i class="' . $button['icon'] . '"></i> ';
                                                } else {
                                                    $icon = '';
                                                }
                                                echo Html::link($icon . $button['label'], $button['url'], $button['htmlOptions']);
                                            }
                                        }
                                    }
                                }
                                ?>
                            </div>


                        </div>
                        <div class="clearfix"></div>
                        <?php if (isset($this->breadcrumbs)) { ?>
                            <div id="page-nav">

                                <?php
                                if (!empty($this->breadcrumbs)) {
                                    $bc = $this->breadcrumbs;
                                } else {
                                    $action = $this->action->id;
                                    $mod = $this->module->id;
                                    if (isset($this->module->info['name'])) {
                                        $name = $this->module->info['name'];
                                    } else {
                                        $name = Yii::t($mod . 'Module.admin', 'MODULE_NAME');
                                    }
                                    if ($action == 'index') {
                                        $bc = array($name);
                                    } elseif ($action == 'create') {
                                        $bc = array(
                                            $name => array('index'),
                                            Yii::t('core', 'CREATE', 1),
                                        );
                                    } elseif ($action == 'update') {
                                        $bc = array(
                                            $name => array('index'),
                                            Yii::t('core', 'UPDATE', 1),
                                        );
                                    }
                                }
                                $this->widget('Breadcrumbs', array(
                                    'homeLink' => '<li>' . Html::link(Yii::t('zii', 'Home'), $this->createUrl('/admin'), array()) . '</li>',
                                    'links' => $bc,
                                    'htmlOptions' => array('class' => 'breadcrumbs pull-left pdd'),
                                    'tagName' => 'ul',
                                    'activeLinkTemplate' => '<li><a href="{url}">{label}</a></li>',
                                    'inactiveLinkTemplate' => '<li class="active">{label}</li>',
                                    'separator' => false
                                ));
                                ?> <div class="clearfix"></div>
                            </div>
                        <?php } ?>
                        <div class="pdd">
                            <div class="row">
                                <div class="col-lg-12">

                                    <?php
                                    $ads = BSAds::model()->active()->findAll();
                                    foreach ($ads as $ad) {
                                        ?>
                                        <div class="alert alert-info">
                                            <div class="row">
                                                <div class="col-lg-1"><i class="flaticon-operator" style="font-size:50px"></i></div>
                                                <div class="col-lg-11">
                                                    <?= $ad->getMessage() ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>



                                    <?php if ($package->demo) { ?>
                                        <div class="alert alert-danger"><b>Внимание!</b> Вы используете пробную версию тарифного пакета "<?= strtoupper($package->plan) ?>", сайт прекратит работу <?= $package->expired ?></div>
                                    <?php } ?>
                                    <?php
                                    if (Yii::app()->user->hasFlash('error')) {
                                        Yii::app()->tpl->alert('danger', Yii::app()->user->getFlash('error'));
                                    }
                                    if (Yii::app()->user->hasFlash('success')) {
                                        Yii::app()->tpl->alert('success', Yii::app()->user->getFlash('success'));
                                    }
                                    ?>

                                    <?= $content ?>
















                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
                <footer class="footer">
                    <p class="col-md-12 text-center">
                        {copyright}
                    </p>
                </footer>
            </div>
            <script>
                $(function() {
                    $(".panel-heading .grid-toggle").click(function(e) {
                        e.preventDefault();
                        $(this).find('i').toggleClass("fa-chevron-down");
                    });       
                    $("#menu-toggle").click(function(e) {
                        e.preventDefault();
                        $("#wrapper").toggleClass("active");
            

                    });
                    $('.fadeOut-time').delay(2000).fadeOut(2000);
                });
            </script>

        </div>
    </body>
</html>
