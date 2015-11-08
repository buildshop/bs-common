<div class="contentTop">




    <span class="icon-xlarge content-top-icon <?php echo $this->module->info['icon'] ?>"></span>
    <span class="pageTitle"><?php echo $this->pageName; ?></span>
    <div class="moduleButtons">
        <div class="grid6">
            <?php
            if (!isset($this->topButtons)) {
                echo Html::link(Yii::t('core', 'CREATE', 0), $this->createUrl('create'), array('title' => Yii::t('core', 'CREATE', 1), 'class' => 'buttonS bGreen'));
            } else {
                if ($this->topButtons == true) {
                    if (is_array($this->topButtons)) {
                        foreach ($this->topButtons as $button) {
                            if (isset($button['icon'])) {
                                $icon = '<i class="fa ' . $button['icon'] . '"></i>';
                            } else {
                                $icon = '';
                            }
                            echo Html::link($icon . $button['label'], $button['url'], $button['htmlOptions']);
                        }
                    } else {
                        throw new CException('Переменная topButtons не является массивом.');
                    }
                }
            }
            ?>
        </div>
    </div>



    <div class="clear"></div>
</div>
<div class="breadLine">
    <div class="bc">
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
        $this->widget('zii.widgets.CBreadcrumbs', array(
            'homeLink' => Html::link(Yii::t('zii', 'Home'), $this->createUrl('/admin'), array()),
            'links' => $bc
        ));
        ?> 
    </div>
    <?php $this->widget('ext.admin.addonsMenu.AddonsMenuWidget'); ?>
</div>
<div class="wrapper">
                <?php if (file_exists(Yii::getPathOfAlias('webroot') . DS . 'install.php')) { ?>
                    <?php Yii::app()->tpl->alert('warning', Yii::t('CoreModule.admin', 'INSTALL_INFO'), false); ?>
                <?php } ?>
</div>