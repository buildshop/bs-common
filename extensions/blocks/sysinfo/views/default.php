
<div class="tab_content fluid ">
    <div>CMS version: <b><?= Yii::app()->version; ?></b></div>
    <div>Yii framework:  <b><?= Yii::getVersion(); ?></b></div>
    <div>PDO extension:  <b><?= $pdo ?></b></div>
    <div>OS:  <b><?= @php_uname('s') ?></b></div>
    <div>PHP version:  <b><?= $php ?></b></div>
    <div>Register_globals:  <b><?= $globals ?></b></div>
    <div>Safe_mode: <b><?= $safe_mode ?></b></div>
    <div>Magic_quotes_gpc: <b><?= $magic_quotes ?></b></div>
    <div>Post_max_size: <b><?= $p_max ?></b></div>
    <div>Upload_max_filesize: <b><?= $u_max ?></b></div>
    <div>Memory_limit: <b><?= $m_max ?></b></div>
    <div>Libery GD: <b><?= $gd ?></b></div>
</div>


<form action="/admin/" method="post">
<div class="formRow">
    <div class="grid8">
        <label>Кэш:</label>
    </div>

<?= Html::hiddenField('clear_cache', 1); ?>
        <?//=Html::dropDownList('cache_id', '', array('cached_settings' => 'settings','cached_widgets' => 'cached_widgets','url_manager_urls' => 'url_manager_urls'), array('empty' => Yii::t('core', 'EMPTY_DROPDOWNLIST', 1)));?>

    <div class="grid4">
        <input class="buttonS bGreen" style="margin-left:10px;" type="submit" value="<?= Yii::t('core', 'CLEAR'); ?>"> 
    </div>
    <div class="clear"></div>
</div>
</form>
<form action="/admin/" method="post">
    <div class="formRow">
        <div class="grid8">
            <label>Активы (/assets):</label>
        </div>
        <div class="grid4">
            <?= Html::hiddenField('clear_assets', 1); ?>
            <input class="buttonS bGreen" style="margin-left:10px;" type="submit" value="<?= Yii::t('core', 'CLEAR'); ?>"> 
        </div>
        <div class="clear"></div>
    </div>
</form>