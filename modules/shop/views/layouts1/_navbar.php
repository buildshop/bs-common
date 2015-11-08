<?php if ($this->beginCache('navbar', array('duration' => 3600))) { ?>




<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav nav-weight">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="/news">Новости</a></li>
                <li><a href="//cms.corner.com.ua/forum"><b>Форум</b></a></li>

                <?php
                $this->widget('users.widgets.login.LoginWidget');
                ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Валюта <span class="badge"><?= Yii::app()->currency->active->symbol ?></span> <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <?php
                        foreach (Yii::app()->currency->currencies as $currency) {
                            echo Html::openTag('li');
                            echo Html::ajaxLink($currency->symbol, '/shop/ajax/activateCurrency/' . $currency->id, array(
                                'success' => 'js:function(){window.location.reload(true)}',
                                    ), array('id' => 'sw' . $currency->id, 'class' => Yii::app()->currency->active->id === $currency->id ? 'active' : ''));
                            echo Html::closeTag('li');
                        }
                        ?>
                    </ul>
                </li>

                <?php
                if (Yii::app()->hasModule('cart'))
                    $this->widget('cart.widgets.cart.CartWidget', array('type' => 'bootstrap'));
                ?>
                <?php Yii::app()->blocks->get('fly',5); ?>
                <?php //$this->widget('ext.widgets.chooseLanguage.ChooseLanguage', array('type' => 'bootstrap')); ?>
            </ul>
        </div>
    </div>
</nav>

<?php
$this->endCache();
} ?>