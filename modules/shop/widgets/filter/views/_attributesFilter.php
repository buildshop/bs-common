<?php
if ($config['filter_enable_attr']) {
    foreach ($attributes as $attrData) {
        ?>

        <div class="sidebar-widget outer-bottom-xs wow fadeInUp">
            <div class="widget-header">
                <h4 class="widget-title"><?= Html::encode($attrData['title']) ?></h4>
            </div>
            <div class="sidebar-widget-body m-t-10">
                <ul class="list">
                    <?php
                    foreach ($attrData['filters'] as $filter) {
                        $url = Yii::app()->request->addUrlParam('/shop/category/view', array($filter['queryKey'] => $filter['queryParam']), $attrData['selectMany']);
                        $queryData = explode(',', Yii::app()->request->getQuery($filter['queryKey']));

                        echo Html::openTag('li');
                        // Filter link was selected.
                        if (in_array($filter['queryParam'], $queryData)) {
                            // Create link to clear current filter
                            $url = Yii::app()->request->removeUrlParam('/shop/category/view', $filter['queryKey'], $filter['queryParam']);
                            echo Html::link($filter['title'], $url, array('class' => 'active'));
                       // } elseif ($filter['count'] > 0) {
                            } else {
                            echo Html::link($filter['title'], $url);
                       // } else {
                        //    echo $filter['title'];
                        }
                        echo Html::closeTag('li');
                    }
                    ?>
                </ul>
            </div>
        </div>

        <?php
    }
}
