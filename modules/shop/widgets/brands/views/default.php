<div class="row">
    <div class="col-xs-12"><h3 class="slider-title">Производители </h3></div>
</div>

<div class="owl-carousel brand-slider custom-carousel owl-theme col-xs-12">
    <?php foreach ($result as $row) { ?>
        <div class="item">
            <?= Html::link(Html::image($row->getImageUrl('image', 'manufacturer', '100x100','resize'), '', array()), $row->getViewUrl(), array('class' => 'image')); ?>
        </div>
    <?php } ?>
</div>

