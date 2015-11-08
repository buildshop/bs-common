<?php
$min = YII_DEBUG ? '' : '.min';
$cs = Yii::app()->clientScript;
//$cs->registerCoreScript('jquery');
$cs->registerCoreScript('jquery.ui');
$cs->registerCssFile($this->baseAssetsUrl . "/css/icons{$min}.css");
$cs->registerCssFile($this->assetsUrl . "/css/bootstrap{$min}.css");
$cs->registerCssFile($this->assetsUrl . "/css/bootstrap-theme.css");
$cs->registerScriptFile($this->assetsUrl . "/js/bootstrap.js");
if (Yii::app()->hasModule('shop')) {
    // Yii::import('mod.shop.components.SCompareProducts');
    // Yii::import('mod.shop.models.ShopWishlist');
}
//Yii::import('ext.jgrowl.Jgrowl');
//Jgrowl::register();
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
        <script src="<?= $this->assetsUrl ?>/js/ie-emulation-modes-warning.js"></script>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body role="document">
        <!-- Fixed navbar -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Bootstrap theme</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dropdown <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li class="dropdown-header">Nav header</li>
                                <li><a href="#">Separated link</a></li>
                                <li><a href="#">One more separated link</a></li>
                            </ul>
                        </li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </nav>

        <div class="container theme-showcase" role="main">

            <!-- Main jumbotron for a primary marketing message or call to action -->
            <div class="jumbotron">
                <h1>Hello, world!</h1>
                <p>This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
                <p><a href="#" class="btn btn-primary btn-lg" role="button">Learn more &raquo;</a></p>
            </div>


            <div class="page-header">
                <h1>Buttons</h1>
            </div>
            <p>
                <button type="button" class="btn btn-lg btn-default">Default</button>
                <button type="button" class="btn btn-lg btn-primary">Primary</button>
                <button type="button" class="btn btn-lg btn-success">Success</button>
                <button type="button" class="btn btn-lg btn-info">Info</button>
                <button type="button" class="btn btn-lg btn-warning">Warning</button>
                <button type="button" class="btn btn-lg btn-danger">Danger</button>
                <button type="button" class="btn btn-lg btn-link">Link</button>
            </p>
            <p>
                <button type="button" class="btn btn-default">Default</button>
                <button type="button" class="btn btn-primary">Primary</button>
                <button type="button" class="btn btn-success">Success</button>
                <button type="button" class="btn btn-info">Info</button>
                <button type="button" class="btn btn-warning">Warning</button>
                <button type="button" class="btn btn-danger">Danger</button>
                <button type="button" class="btn btn-link">Link</button>
            </p>
            <p>
                <button type="button" class="btn btn-sm btn-default">Default</button>
                <button type="button" class="btn btn-sm btn-primary">Primary</button>
                <button type="button" class="btn btn-sm btn-success">Success</button>
                <button type="button" class="btn btn-sm btn-info">Info</button>
                <button type="button" class="btn btn-sm btn-warning">Warning</button>
                <button type="button" class="btn btn-sm btn-danger">Danger</button>
                <button type="button" class="btn btn-sm btn-link">Link</button>
            </p>
            <p>
                <button type="button" class="btn btn-xs btn-default">Default</button>
                <button type="button" class="btn btn-xs btn-primary">Primary</button>
                <button type="button" class="btn btn-xs btn-success">Success</button>
                <button type="button" class="btn btn-xs btn-info">Info</button>
                <button type="button" class="btn btn-xs btn-warning">Warning</button>
                <button type="button" class="btn btn-xs btn-danger">Danger</button>
                <button type="button" class="btn btn-xs btn-link">Link</button>
            </p>


            <div class="page-header">
                <h1>Tables</h1>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Username</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Jacob</td>
                                <td>Thornton</td>
                                <td>@fat</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Larry</td>
                                <td>the Bird</td>
                                <td>@twitter</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Username</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Jacob</td>
                                <td>Thornton</td>
                                <td>@fat</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Larry</td>
                                <td>the Bird</td>
                                <td>@twitter</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Username</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td rowspan="2">1</td>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                            </tr>
                            <tr>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@TwBootstrap</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Jacob</td>
                                <td>Thornton</td>
                                <td>@fat</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td colspan="2">Larry the Bird</td>
                                <td>@twitter</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Username</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Jacob</td>
                                <td>Thornton</td>
                                <td>@fat</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td colspan="2">Larry the Bird</td>
                                <td>@twitter</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>








            <div class="row">
                <div class="col-md-2">
                    <img data-src="holder.js/200x250" class="img-thumbnail" alt="">
                    <h2>Heading</h2>
                    <a class="btn btn-success" role="button" href="#">Купить</a>
                </div>
                <div class="col-md-2">
                    <img data-src="holder.js/200x250" class="img-thumbnail" alt="">
                    <h2>Heading</h2>
                    <a class="btn btn-success" role="button" href="#">Купить</a>
                </div>
                <div class="col-md-2">
                    <img data-src="holder.js/200x250" class="img-thumbnail" alt="">
                    <h2>Heading</h2>
                    <a class="btn btn-success" role="button" href="#">Купить</a>
                </div>
            </div>












        </div> <!-- /container -->


        <!-- Bootstrap core JavaScript -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="<?= $this->assetsUrl ?>/js/bootstrap.min.js"></script>
        <script src="<?= $this->assetsUrl ?>/js/docs.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="<?= $this->assetsUrl ?>/js/ie10-viewport-bug-workaround.js"></script>
        
            <footer class="footer">
      <div class="container">
        <p class="text-muted">Place sticky footer content here.</p>
      </div>
    </footer>
        
    </body>
</html>
