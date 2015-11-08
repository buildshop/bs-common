


<?php
$config = Yii::app()->settings->get('shop');

$this->widget('ext.fancybox.Fancybox', array(
    'target' => 'a.thumbnail',
));

Yii::app()->controller->renderPartial('_configurations', array('model' => $model));
?>



<div class="container">

    <?php
    $this->widget('Breadcrumbs', array(
        'links' => $this->breadcrumbs,
    ));
    ?>
    <div class="row">
        <div class="col-md-5">
            <div id="images-main">
                <?php
                $image = $model->getImageUrl('image', $this->config['img_view_size']);
                if ($image) {
                    $img = $image;
                } else {
                    $img = 'http://placehold.it/' . $this->config['img_view_size'];
                }
                echo Html::link(Html::image($img, $model->name, array('class' => 'img-responsive', 'height' => 240)), array('product/view', 'seo_alias' => $data->seo_alias), array('class' => 'thumbnail'));
                ?>

            </div>
<?php if (isset($model->imagesNoMain)) { ?>
                <div class="row">
                <?php
                foreach ($model->imagesNoMain as $image) {
                    echo Html::openTag('div', array('class' => 'col-md-3'));
                    echo Html::link(Html::image($image->getUrl($config['img_view_thumbs_size']), $image->title), $image->getUrl(), array('class' => 'thumbnail2', 'rel' => 'gallery'));
                    echo Html::closeTag('div');
                }
                ?>
                </div>
                <?php } ?>
        </div>
        <div class="col-md-7">
            <h3><?php echo Html::encode($model->name); ?></h3>

            <div class="price">
                <span id="productPrice">
<?= ShopProduct::formatPrice($model->toCurrentCurrency()); ?>
                </span>

<?= Yii::app()->currency->active->symbol; ?>
            </div>   
                <?php
                if (Yii::app()->hasModule('discounts')) {
                    if ($model->appliedDiscount) {
                        echo '<span style="color:red; "><s>' . $model->toCurrentCurrency('originalPrice') . '</s></span>';
                    }
                }
                ?>
            <div class="actions row">
                <div class="col-md-5">
<?php
if (Yii::app()->hasModule('cart'))
    $this->widget('cart.widgets.addcart.AddcartWidget', array('data' => $model, 'spinner' => true, 'conf' => true));
?>
                </div>
                <div class="col-md-7" style="padding-top:6px"><?php
                    if (Yii::app()->hasModule('compare'))
                        $this->widget('compare.widgets.CompareWidget', array('pk' => $data->id));

                    if (Yii::app()->hasModule('wishlist'))
                        $this->widget('wishlist.widgets.WishlistWidget', array('pk' => $data->id));
?></div>

            </div>


<?= $model->short_description; ?>
        </div>

    </div>







<?php



$tabs = array();

// EAV tab
if ($model->getEavAttributes()) {
    $tabs[Yii::t('ShopModule.core', 'Характеристики')] = array(
        'content' => $this->renderPartial('_attributes', array('model' => $model), true
            ));
}

// Comments tab
if (Yii::app()->hasModule('comments')) {
    $tabs[Yii::t('ShopModule.core', 'Отзывы') . ' (' . $model->commentsCount . ')'] = array(
        'id' => 'comments_tab',
        'content' => $this->renderPartial('_comments', array('model' => $model), true));
}
// Related products tab
if ($model->relatedProductCount) {
    $tabs[Yii::t('ShopModule.core', 'Сопутствующие продукты') . ' (' . $model->relatedProductCount . ')'] = array(
        'id' => 'related_products_tab',
        'content' => $this->renderPartial('_related', array(
            'model' => $model,
                ), true));
}

// Render tabs
$this->widget('app.jui.JuiTabs', array(
    'id' => 'tabs',
    'ulClass' => 'nav nav-tabs',
    'tabs' => $tabs
));

// Fix tabs opening by anchor
Yii::app()->clientScript->registerScript('tabSelector', '
			$(function() {
				var anchor = $(document).attr("location").hash;
				var result = $("#tabs").find(anchor).parents(".ui-tabs-panel");
				if($(result).length)
				{
					$("#tabs").tabs("select", "#"+$(result).attr("id"));
				}
			});
		');
?>





</div>








