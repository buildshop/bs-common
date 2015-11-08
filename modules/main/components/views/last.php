<div id="widget-news-box">
    <div id="widget-news-box-title"><img src="<?php echo $this->_assetsUrl?>/images/icon.png" alt="">Новости</div>
<?php if (count($model)) { ?>
    <div id="widget-news-list">
    <?php foreach ($model as $item) { ?>
        <div class="widget-news-row">
            <div class="widget-news-row-date"><?php echo PFunction::date($item->date_create); ?></div>
            <?php echo CHtml::link($item->title_ru, '/news/' . $item->seo_alias); ?>
        </div>
    <?php } ?>
        </div>
    <div style="text-align: center"><a href="/news/" class="all-news">Показать все новости &RightArrow;</a></div>
<?php } else { ?>
    Нет ниодной записи
<?php } ?>

    </div>

    
    
