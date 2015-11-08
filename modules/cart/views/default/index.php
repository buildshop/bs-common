<?php
$cs = Yii::app()->clientScript;
//$cs->registerScriptFile($this->module->assetsUrl . '/cart.js', CClientScript::POS_END);
$cs->registerScript('cart', "
//cart.selectorTotal = '#total';
var orderTotalPrice = '$totalPrice';

", CClientScript::POS_HEAD);


?>
<script>
    $(function(){
 
        
        
        
        
        $('.payment_checkbox').click(function(){
            $('#payment').text($(this).attr('data-value'));
        });
        $('.delivery_checkbox').click(function(){
            $('#delivery').text($(this).attr('data-value'));
          
        });
        // if($('#cart-check').length > 0){
        //     $('#cart-check').stickyfloat({ duration: 800 });
        // }
        hasChecked('.payment_checkbox','#payment');
        hasChecked('.delivery_checkbox','#delivery');
    });
    
    function hasChecked(selector,div){
        $(selector).each(function(k,i){
            var inp = $(i).attr('checked');
            if(inp=='checked'){
                $(div).text($(this).attr('data-value'))
            }
        });
    }
    function submitform(){
        if(document.cartform.onsubmit &&
            !document.cartform.onsubmit())
        {
            return;
        }
        document.cartform.submit();
    }
</script>


<?php
if (empty($items)) {
    echo Html::openTag('div', array('id' => 'container-cart', 'class' => 'indent'));
    echo Html::openTag('h1');
    echo Yii::t('CartModule.default', 'CART_EMPTY');
    echo Html::closeTag('h1');
    echo Html::closeTag('div');
    return;
}
$this->widget('ext.fancybox.Fancybox', array('target' => 'a.thumbnail'));
?>


<h1><?= $this->pageName ?></h1>
<div id="cart-left" class="fluid">
    <?php echo Html::form(array('/cart/'), 'post', array('id' => 'cart-form', 'name' => 'cartform')) ?>
    <div class="table-responsive">
        <table id="cart-table" class="table table-striped table-condensed" width="100%" border="0" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th></th>
                    <th>Товар</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $index => $product) { ?>
                    <?php
                    // print_r($product);die;
                    $price = ShopProduct::calculatePrices($product['model'], $product['variant_models'], $product['configurable_id']);
                    ?>
                    <tr id="product-<?= $index ?>">
                        <td width="110px" align="center">
                            <?php
                            // Display image
                            $thumbSize = Yii::app()->settings->get('shop', 'img_view_thumbs_size');
                            if (isset($product['model']->mainImage)) {
                                $imgSource = $product['model']->mainImage->getUrl($thumbSize);
                                echo Html::link(Html::image($imgSource, ''), $product['model']->mainImage->getUrl($thumbSize), array('class' => 'thumbnail'));
                            } else {
                                $imgSource = 'http://placehold.it/' . $thumbSize;
                                echo Html::image($imgSource, '', array('class' => 'thumbnail img-responsive'));
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            // Display product name with its variants and configurations
                            echo Html::link(Html::encode($product['model']->name), array('/shop/product/view', 'seo_alias' => $product['model']->seo_alias));
                            ?>

                            <span class="box-unit">(<?= $product['pcs'] ?> л)</span><br/> <?php
                        // Price

                        echo Html::openTag('span', array('class' => 'price'));
                        echo ShopProduct::formatPrice(Yii::app()->currency->convert($price));
                        echo ' ' . Yii::app()->currency->active->symbol;
                        //echo ' '.($product['currency_id']) ? Yii::app()->currency->getSymbol($product['currency_id']) : Yii::app()->currency->active->symbol;
                        echo Html::closeTag('span');

                        // Display variant options
                        if (!empty($product['variant_models'])) {
                            echo Html::openTag('span', array('class' => 'cartProductOptions'));
                            foreach ($product['variant_models'] as $variant)
                                echo ' - ' . $variant->attribute->title . ': ' . $variant->option->value . '<br/>';
                            echo Html::closeTag('span');
                        }

                        // Display configurable options
                        if (isset($product['configurable_model'])) {
                            $attributeModels = ShopAttribute::model()->findAllByPk($product['model']->configurable_attributes);
                            echo Html::openTag('span', array('class' => 'cartProductOptions'));
                            foreach ($attributeModels as $attribute) {
                                $method = 'eav_' . $attribute->name;
                                echo ' - ' . $attribute->title . ': ' . $product['configurable_model']->$method . '<br/>';
                            }
                            echo Html::closeTag('span');
                        }
                            ?>
                        </td>
                        <td>

                            <?php echo Html::textField("quantities[$index]", $product['quantity'], array('class' => 'spinner btn-group form-control', 'product_id' => $index)) ?>

                        </td>
                        <td id="price-<?= $index ?>">
                            <?php
                            echo Html::openTag('span', array('class' => 'price cart-total-product', 'id' => 'row-total-price' . $index));
                            echo (Yii::app()->settings->get('shop', 'wholesale')) ? ShopProduct::formatPrice(ShopProduct::formatPrice(Yii::app()->currency->convert($price * $product['model']->pcs * $product['quantity']))) : ShopProduct::formatPrice(Yii::app()->currency->convert($price * $product['quantity']));
                            echo Html::closeTag('span');
                            //echo $convertTotalPrice;// echo ShopProduct::formatPrice(Yii::app()->currency->convert($convertPrice, $product['currency_id']));
                            echo ' ' . Yii::app()->currency->active->symbol;
                            //echo ' '.($product['currency_id'])? Yii::app()->currency->getSymbol($product['currency_id']): Yii::app()->currency->active->symbol;
                            ?>
                        </td>
                        <td style="vertical-align:middle;" width="20px">
                            <?= Html::link('', array('cart/remove', 'id' => $index), array('class' => 'remove')) ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>


    </div>
    <?php
    Yii::app()->tpl->alert('info', Yii::t('CartModule.default','ALERT_CART'))
    ?>
    <div class="row container-fluid">
        <div class="col-md-5 img-rounded form-horizontal">
            <h3><?= Yii::t('CartModule.default','USER_DATA');?></h3>
            <?php
            $this->renderPartial('_fields_user', array('form' => $this->form));
            ?>
        </div>
        <div class="col-md-7">
            <div class="row">
                <div class="col-md-6 col-md-offset-1">
                    <?php
                    $this->renderPartial('_fields_delivery', array(
                        'form' => $this->form,
                        'deliveryMethods' => $deliveryMethods)
                    );
                    $this->renderPartial('_fields_payment', array(
                        'form' => $this->form,
                        'paymenyMethods' => $paymenyMethods)
                    );
                    ?>
                </div>


                <div id="cart-check" class="text-center col-md-5 col-md-offset-0 bg-success img-rounded padding-tb">
                    <div>Сумма заказа</div>
                    <div><span class="price"><span id="total"><?= ShopProduct::formatPrice($totalPrice) ?></span> <i><?php echo Yii::app()->currency->active->symbol; ?></i></span></div>
                    <div style="margin-top:40px"><?= Yii::t('CartModule.default','PAYMENT'); ?>:</div>
                    <div id="payment">---</div>
                    <div><?= Yii::t('CartModule.default','DELIVERY'); ?>:</div>
                    <div id="delivery">---</div>
                    <a href="javascript:submitform();" class="btn btn-lg btn-success btn-block"><?= Yii::t('CartModule.default','BUTTON_CHECKOUT'); ?></a>
                </div>

            </div>
        </div>
    </div>
    <input class="button btn-green" type="hidden" name="create" value="1">
    <?php echo Html::endForm() ?>
</div>
