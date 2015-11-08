


<?php
$rows = 4;
if (isset($result['items'])) {


    foreach ($result['items'] as $k => $item) {

        if ($k % $rows == 0) {
            echo '<div class="row">';
        }
        ?>
        <?php
        if (isset($item['items'])) {
            $href = '#';
            $datatoggle = 'dropdown';
        } else {
            $datatoggle = '';
            $href = Yii::app()->createUrl($item['url'][0], array('seo_alias' => $item['url']['seo_alias']));
        }
        ?>
        <div class="col-sm-3">

            <a href="<?= $href; ?>" class="dropdown-toggle " data-hover="<?= $datatoggle ?>" data-toggle="<?= $datatoggle ?>"><?= $item['label'] ?></a>
            <?php if (isset($item['items'])) { ?>
                <ul class="links list-unstyled">
                    <?php
                    $i = 0;
                    foreach ($item['items'] as $subitem) {
                        $i++;
                        echo Html::tag('li', array(), Html::link($subitem['label'], $subitem['url']), true);
                    }
                    ?>
                </ul>
            <?php } ?>

        </div>
        <?php
        if ($k % $rows == $rows - 1) {
            echo '</div>';
        }
    }
    ?>

<?php } ?>


