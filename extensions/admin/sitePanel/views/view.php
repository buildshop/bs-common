<div class="breadLine" id="admin-panel">
    <div class="breadLinks">
        <ul>
            <li id="panel"><a href="javascript:void(0)"><span class="icon-cms"></span></a></li> 
            <?php foreach ($menu as $row) { ?>
                <?php if (isset($row['items'])) { ?>
                    <li <?php echo (isset($row['items'])) ? 'class="panelObj has"' : 'class="panelObj"'; ?>>
                        <a href="<?php echo CHtml::normalizeUrl($row['url']) ?>">
                            <span class="icon-medium <?= $row['icon'] ?>" style="margin-right:5px;"></span>
                            <?= $row['label'] ?></a>
                        <?php if (isset($row['items'])) { ?>
                            <ul>
                                <?php foreach ($row['items'] as $rowItems) { ?>
                                    <?php if ($rowItems['visible'] == true) { ?>
                                        <li>
                                            <a href="<?php echo CHtml::normalizeUrl($rowItems['url']) ?>"><span class="<?= $rowItems['icon'] ?>" style="margin-right:5px;"></span><?= $rowItems['label'] ?></a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        <?php } ?>

                    </li>
                <?php } ?>
            <?php } ?>
            <li class="panelObj posRight has"><a href="javascript:void(0)">Добро пожаловать <?= CHtml::image(Yii::app()->user->avatarPath, Yii::app()->user->username, array("width" => "16", "height" => "16", 'class' => 'user-avatar')) ?> <?= Yii::app()->user->username ?></a>
                <ul>
                    <!--<li><a href="#"><span class="icon-user"></span> Профиль</a></li>-->
                    <li><a href="/admin/users/default/update?id=<?= Yii::app()->user->id ?>"><span class="icon-pencil-3"></span> Редактировать</a></li>
                    <li><a href="/admin/auth/logout"><span class="icon-exit"></span> Выход</a></li>
                </ul>
            </li> 
        </ul>
        <div class="clear"></div>
    </div>
</div>
