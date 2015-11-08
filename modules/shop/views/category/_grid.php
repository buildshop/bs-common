
<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 product text-left">
    <div class="product-box">
        <div class="product-image-box">
            <?php
             if ($data->mainImage) {
              $imgSource = $data->mainImage->getUrl($this->config['img_view_size']); //
              } else {
              $imgSource = 'http://placehold.it/' . $this->config['img_view_size'];
              }
              echo Html::link(Html::image($imgSource, $data->mainImageTitle, array('class' => 'img-responsive', 'height' => 240)), array('product/view', 'seo_alias' => $data->seo_alias), array('class' => 'thumbnail'));
             


            /*$image = $data->getImageUrl('image', $this->config['img_view_size']);
            if ($image) {
                $img = $image;
            } else {
                $img = 'http://placehold.it/' . $this->config['img_view_size'];
            }
            echo Html::link(Html::image($img, $data->name, array('class' => 'img-responsive', 'height' => 240)), array('product/view', 'seo_alias' => $data->seo_alias), array('class' => 'thumbnail'));
            */?>
        </div>


        <div class="product-title h4">
            <?php echo Html::link(Html::encode($data->name), array('product/view', 'seo_alias' => $data->seo_alias)) ?>
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
        if (Yii::app()->hasModule('cart'))
            $this->widget('cart.widgets.addcart.AddcartWidget', array('data' => $data));
        ?>
        <?php
        if(Yii::app()->user->isSuperuser)
            echo Html::link('ред.',array('/shop/admin/products/update','id'=>$data->id),array('class'=>'btn btn-xsm btn-info'));
        ?>

        <?php
        if (Yii::app()->hasModule('compare'))
            $this->widget('compare.widgets.CompareWidget', array('pk' => $data->id));

        if (Yii::app()->hasModule('wishlist'))
            $this->widget('wishlist.widgets.WishlistWidget', array('pk' => $data->id));
        ?>

        <ul class="product-detail list-unstyled">
            <li><?php echo $data->short_description; ?></li>
        </ul>
    </div>
</div>
