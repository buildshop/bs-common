<li class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
        <?= Html::image('/uploads/language/' . $language->active->flag_name); ?>
        <?= $language->active->name ?> <span class="caret"></span></a>
    <ul class="dropdown-menu" role="menu">
        <?php
        foreach ($language->getLanguages() as $lang) {
            $classLi = ($lang->code == Yii::app()->language) ? $lang->code . ' active' : $lang->code;
            $link = ($lang->default) ? CMS::currentUrl() : '/' . $lang->code . CMS::currentUrl();
            //Html::link(Html::image('/images/language/' . $lang->flag_name, $lang->name), $link, array('title' => $lang->name));
            ?>
            <li>
                <?php
                echo Html::link(Html::image('/uploads/language/' . $lang->flag_name, $lang->name) . ' ' . $lang->name, $link);
                ?>
            </li>
        <?php } ?>
    </ul>
</li>
