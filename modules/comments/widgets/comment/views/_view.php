<div class="row" id="commend_<?= $data->id ?>" name="commend_<?= $data->id ?>">
    <div class="col-sm-1">
        <div class="thumbnail">
            <img class="img-responsive" src="<?= Html::encode($data->user->avatarPath); ?>">
        </div>
    </div>
    <div class="col-sm-11">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><?= Html::encode($data->user_name); ?></strong> <?= Html::link('#' . $data->id, Yii::app()->request->getUrl() . '#comment_' . $data->id) ?>
                <div class="pull-right">
                    <span class="label">
                        <span class="text-muted"><?= CMS::date($data->date_create); ?></span>
                    </span>
                    <?php if ($data->controlTimeout()) { ?>
                        <div class="btn-group" id="comment-panel<?= $data->id ?>">
                            <?= $data->editLink ?>
                            <?= $data->deleteLink ?>

                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="panel-body">
                <div id="comment_<?= $data->id; ?>"><?= nl2br(CMS::bb_decode(Html::text($data->text))); ?></div>
                <a class="reply" href="javascript:void(0)" onClick='$("#comment_<?= $data->id; ?>").comment("reply_form",{pk:"<?= $data->id; ?>", model:"<?= $data->model; ?>"}); return false;'><?= Yii::t('CommentsModule.default', 'REPLY'); ?></a>
            </div>
        </div>
        <div class="" id="comment-reply-form-<?= $data->id; ?>"></div>
    </div>

    <?php
    $descendants = $data->descendants()->active()->findAll(array('order' => '`t`.`rgt` DESC'));
    if (isset($descendants)) {
        foreach ($descendants as $children) {
            ?>
            <div class="" name="commend_<?= $children->id ?>">
                <div class="col-sm-offset-<?= ($children->level <= 4) ? $children->level - 1 : 4 ?>"  lavel="<?= $children->level ?>">
                    <div class="col-sm-1">
                        <div class="thumbnail">
                            <img class="img-responsive" src="<?= Html::encode($children->user->avatarPath); ?>">
                        </div>
                    </div>
                    <div class="col-sm-11">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong><?= Html::encode($children->user_name); ?></strong> <?= Html::link('#' . $children->id, Yii::app()->request->getUrl() . '#comment_' . $children->id) ?>
                                <div class="pull-right">
                                    <span class="label">
                                        <span class="text-muted"><?= CMS::date($children->date_create); ?></span>
                                    </span>
                                    <?php if ($children->controlTimeout()) { ?>

                                        <div class="btn-group" id="comment-panel<?= $children->id ?>">
                                            <?= $children->editLink ?>
                                            <?= $children->deleteLink ?>

                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div id="comment_<?= $children->id; ?>"><?= nl2br(CMS::bb_decode(Html::text($children->text))); ?></div>
                                <a class="reply" href="javascript:void(0)" onClick='$("#comment_<?= $children->id; ?>").comment("reply_form",{pk:"<?= $children->id; ?>", model:"<?= $children->model; ?>"}); return false;'><?= Yii::t('CommentsModule.default', 'REPLY'); ?></a>
                            </div>
                        </div>
                        <div class="" id="comment-reply-form-<?= $children->id; ?>"></div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>











