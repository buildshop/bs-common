









<div class="product">
    <div class="product-box clearfix">

        <div class="clearfix gtile-i-150-minheight">
            <?php
            if ($data->mainImage) {
                $imgSource = $data->mainImage->getUrl(Yii::app()->settings->get('shop', 'img_preview_size_list')); //
            } else {
                $imgSource = 'http://placehold.it/' . Yii::app()->settings->get('shop', 'img_preview_size_list');
            }
            echo Html::link(Html::image($imgSource, $data->mainImageTitle), array('/shop/product/view', 'seo_alias' => $data->seo_alias), array('class' => 'thumbnail'));
            ?>
        </div>


        <div class="product-title">
            <?php echo Html::link(Html::encode($data->name), array('/shop/product/view', 'seo_alias' => $data->seo_alias)) ?>
        </div>

        <div class="product-price">
            <span class="price">
                <?php
                if (Yii::app()->hasModule('discounts')) {
                    if ($data->appliedDiscount) {
                        echo '<span style="color:red; "><s>' . $data->toCurrentCurrency('originalPrice') . '</s></span>';
                    }
                }
                ?>
                <?= $data->priceRange() ?></span>
            <sup><?= Yii::app()->currency->active->symbol ?></sup>
        </div>



<?php
		echo Html::link(Yii::t('ShopModule.core', 'Удалить'), array('/compare/default/remove','id'=>$data->id),array(
			'class'=>'remove',
		));?>



        <ul class="product-detail">
            <li><?php echo $data->short_description; ?></li>
        </ul>

    </div>
</div>









