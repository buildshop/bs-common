<?php if (isset($menu)) { ?>
    <div class="breadLinks">
        <ul>
            <?php
            foreach ($menu as $param) {
                if (isset($param['visible']) && $param['visible'] == true) {
                    if (isset($param['items'])) {
                        echo Html::openTag('li',array('class'=>'has')); //Обязательный класс has.
                        echo Html::link('<span class="' . $param['icon'] . '"></span>' . $param['label'], 'javascript:void(0)',$param['linkOptions']);
                        echo Html::openTag('ul',$param['itemsHtmlOptions']);
                        foreach ($param['items'] as $sparam) {
                            echo Html::openTag('li');
                            echo Html::link('<span class="' . $sparam['icon'] . '"></span>' . $sparam['label'], $sparam['url'],$sparam['linkOptions']);
                            echo Html::closeTag('li');
                        }
                        echo Html::closeTag('li');
                        echo Html::closeTag('ul');
                    } else {
                        echo Html::openTag('li');
                        echo Html::link('<span class="' . $param['icon'] . '"></span>' . $param['label'], $param['url'],$param['linkOptions']);
                        echo Html::closeTag('li');
                    }
                }
            }
            ?>
        </ul>
        <div class="clear"></div>
    </div>
<?php } ?> 
