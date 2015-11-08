
<?php
if ($config['filter_enable_price']
        && ($this->currentMinPrice > 0 && $this->currentMaxPrice > 0)
        && ($this->currentMinPrice != $this->currentMaxPrice) //Если у товаров онинаковые цены, false
) {
    ?>

    <div class="sidebar-widget outer-bottom-xs wow fadeInUp">
        <div class="widget-header">
            <h4 class="widget-title"><?= Yii::t('ShopModule.default', 'FILTER_PRICE_HEADER') ?> (<?= Yii::app()->currency->active->symbol ?>)</h4>
        </div>
        <?php echo Html::form() ?>
    <?= Html::hiddenField('min_price', (isset($_GET['min_price'])) ? (int) $this->getCurrentMinPrice() : null, array()) ?>
    <?= Html::hiddenField('max_price', (isset($_GET['max_price'])) ? (int) $this->getCurrentMaxPrice() : null, array()) ?>
        <div class="sidebar-widget-body m-t-20">
            <div class="price-range-holder">
                <span class="min-max">
                    <span class="pull-left" id="mn"><?= (isset($_GET['min_price'])) ? (int) $this->getCurrentMinPrice() : null ?></span>
                    <span class="pull-right" id="mx"><?= (isset($_GET['max_price'])) ? (int) $this->getCurrentMaxPrice() : null ?></span>
                </span>


                <?php
                $cm = Yii::app()->currency;
                //$getMin = $this->controller->getMinPrice();
                //$getMax = $this->controller->getMaxPrice();
                $getMax = $this->currentMaxPrice;
                $getMin = $this->currentMinPrice;
                $min = (int) floor($getMin); //$cm->convert()
                $max = (int) ceil($getMax);
              //  echo $cm->convert($getMin);

                echo $this->widget('zii.widgets.jui.CJuiSlider', array(
                    'options' => array(
                        'range' => true,
                        'min' => $min,
                        'cssFile' => false,
                        'max' => $max,
                        'disabled' => (int) $getMin === (int) $getMax,
                        'values' => array($getMin, $getMax),
                        'slide' => 'js:function(event, ui) {
				$("#min_price").val(ui.values[0]);
				$("#max_price").val(ui.values[1]);
                                $("#mn").text(ui.values[0]);
				$("#mx").text(ui.values[1]);
			}',
                        'create' => 'js:function(event, ui){
				$("#min_price").val(' . $min . ');
				$("#max_price").val(' . $max . ');
                                $("#mn").text(' . $min . ');
				$("#mx").text(' . $max . ');
                    }'
                    ),
                    'htmlOptions' => array('class' => 'price-slider'),
                        ), true);
                ?>



            </div><!-- /.price-range-holder -->
            <button type="submit" class="lnk btn btn-primary">OK</button>

        </div><!-- /.sidebar-widget-body -->
    <?php echo Html::endForm() ?>
    </div><!-- /.sidebar-widget -->



<?php } ?>