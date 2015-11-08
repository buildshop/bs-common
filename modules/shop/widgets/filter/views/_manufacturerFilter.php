<?php if (!empty($manufacturers['filters']) && $config['filter_enable_brand']) { ?>


        <div class="widget-header">
            <h4 class="widget-title">Производители11</h4>
        </div>

            <ul class="list-filter">
                <?php
                foreach ($manufacturers['filters'] as $filter) {
                    $url = Yii::app()->request->addUrlParam('/shop/category/view', array($filter['queryKey'] => $filter['queryParam']), $manufacturers['selectMany']);
                    $queryData = explode(',', Yii::app()->request->getQuery($filter['queryKey']));
                    $count = ($filter['count'] > 0) ? '<span>(' . $filter['count'] . ')</span>' : '<span>(0)</span>';
                    echo Html::openTag('li');
                    // Filter link was selected.
                    if ($filter['count'] > 0) {
                        if (in_array($filter['queryParam'], $queryData)) {
                            // Create link to clear current filter
                            $url = Yii::app()->request->removeUrlParam('/shop/category/view', $filter['queryKey'], $filter['queryParam']);
                            echo Html::link($filter['title'], $url, array('class' => 'active'));
                        } else {
                            echo Html::link($filter['title'], $url);
                        }
                    } else {
                        echo $filter['title'];
                    }
                    echo $count;
                    echo Html::closeTag('li');
                }
                ?>
            </ul>

<?php } ?>