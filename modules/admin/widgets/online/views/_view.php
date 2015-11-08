<li>

    <?php
    $ip = CMS::ip($data->ip_address);
    $userName = (!is_null($data->user)) ? $data->user->login : Yii::t('core', 'CHECKUSER', 0);
    ?>

    <a href="javascript:void(0)" class="exp subClosed">
        <img width="36" src="<?= $data->user_avatar ?>" alt="<?= $userName; ?>" />
        <span class="contactName">
            <strong><?= $userName; ?></strong>
            <i><?php echo Yii::t('core', 'CHECKUSER', (int) $data->user_type); ?></i>
        </span>
        <span class="flag"><?php
    if ($ip) {
        echo $ip;
    } else {
        echo '<span class="icon-question" title="'.$data->ip_address.'"></span>';
    }
    ?></span>
        <span class="clear"></span>
    </a>
    <ul>
        <li style=" padding: 5px 10px;">
            <b>Браузер:</b> <?= CMS::detectBrowser($data->user_agent); ?>
        </li>
        <li style=" padding: 5px 10px;">
            <b>Платформа:</b> <?= CMS::detectPlatform($data->user_agent); ?>
        </li>
        <li style=" padding: 5px 10px;">
            <b>IP:</b> <?= $data->ip_address; ?>
        </li>
        <li style=" padding: 5px 10px;">
            <b><?= Yii::t('default','ONLINE') ?>:</b> <?= ($data->start_expire)?CMS::display_time(time()-$data->start_expire):'unknown'; ?>
        </li>
    </ul>
</li>

