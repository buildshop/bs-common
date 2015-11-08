<?php

// Display product custom options table.
if ($model->getEavAttributes()) {
    $this->widget('mod.shop.components.AttributesRender', array(
        'model' => $model,
        'htmlOptions' => array(
            'class' => 'attributes'
        ),
    ));
}
