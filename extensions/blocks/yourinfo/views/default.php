<?php
if (Yii::app()->hasComponent('geoip')) {
    $geoip = Yii::app()->geoip;
    $package = Yii::app()->package;
    $ip = Yii::app()->request->getUserHostAddress();

    $code = $geoip->lookupCountryCode($ip);
    $name = $geoip->lookupCountryName($ip);
    ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4><?= $this->title ?></h4>
        </div>
        <div class="panel-body panel-body-static">
            <ul class="list-unstyled">
                <li>Тарифный план: <span class="label label-default"><?= $package->shop[0]->plan; ?></span></li>
                <li><i class="flaticon-email"></i> <?= Yii::app()->user->getName() . ' ' . CMS::ip($ip); ?></li>
                <li><i class="<?= $browserIcon ?>"></i> <?= $browser->getBrowser() ?> (<?= $browser->getVersion() ?>)</li>
                <li><i class="<?= $platformIcon ?>"></i> <?= $browser->getPlatform() ?></li>
                <li><i class="flaticon-map-pointer"></i> IP address: 
                    <?= $ip . ' ' . $name . '(' . $code . ')'; ?></li>
            </ul>

        </div>
    </div>

    <?php
} else {
    echo ';(';
}