<?php if (Yii::app()->user->hasFlash('config_error')) { ?>
    <div class="flash-alert-error" id="flash-alert">
        <?php echo Yii::app()->user->getFlash('config_error') ?>
    </div>
<?php } ?>
<?php if (Yii::app()->user->hasFlash('config_success')) { ?>
    <div class="flash-alert-success" id="flash-alert">
        <?php echo Yii::app()->user->getFlash('config_success') ?>
    </div>
<?php } ?>






<div class="secNav" style="display: block;">
    <div class="secWrapper">
        <div class="secTop">
            <div class="balance">
                <div class="balInfo" stlye="display:none">Новости:</div>
            </div>
        </div>
        <script>
            $(function(){
                $('#tab-container').tabs({
                    collapsible: true,

                    beforeActivate: function (event, ui) {
                        window.location.hash = ui.newPanel.selector;
                    }
                });
                //init_uniform();
            });
            



            tinymce.init({
                selector: ".editor-small",
                language : "ru",
                toolbar_items_size: 'small',
                menubar: false,
                plugins: [
                    "advlist autolink lists link image charmap preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste moxiemanager"
                ],
                toolbar1: "styleselect",
                toolbar2: "bullist numlist outdent indent"
            });
        </script>

   
        
        <div id="tab-container" class="tab-container">
            <ul class="iconsLine ic3 etabs">
                <li><a href="#general"><span class="icon-home-2 "></span></a></li>
                <li><a href="#seo"><span class="icon-earth-2"></span></a></li>
                <li><a href="#settings"><span class="icon-cog"></span></a></li>
            </ul>

            <div class="divider"><span></span></div>
            <div id="general">
                <ul class="subNav">
                    <li><a data-full="true" class="ajaxUrl this" data-complete-text="<?php echo Yii::t('admin', 'The module is successfully loaded') ?>" href="/admin/shop/category"><span class="icon-folder-open icon-medium"></span> Категории</a></li>
                    <li><a data-full="true" class="ajaxUrl" data-complete-text="<?php echo Yii::t('admin', 'The module is successfully loaded') ?>" href="/admin/shop/products"><span class="icon-file icon-medium"></span> Товары</a></li>
                    <li><a data-full="true" class="ajaxUrl" data-complete-text="<?php echo Yii::t('admin', 'The module is successfully loaded') ?>" href="/admin/shop/news"><span class="icon-yelp icon-medium"></span> Бренды</a></li>
                    <li><a data-full="true" class="ajaxUrl" data-complete-text="<?php echo Yii::t('admin', 'The module is successfully loaded') ?>" href="/admin/shop/filters"><span class="icon-filter icon-medium"></span> Фильтры</a></li>
                    <li><a data-full="true" class="ajaxUrl" data-complete-text="<?php echo Yii::t('admin', 'The module is successfully loaded') ?>" href="/admin/shop/news"><span class="icon-earth icon-medium"></span> Страны</a></li>
                    <li><a data-full="true" class="ajaxUrl" data-complete-text="<?php echo Yii::t('admin', 'The module is successfully loaded') ?>" href="/admin/shop/news"><span class="icon-cart-4 icon-medium"></span> Заказы</a></li>
                    <li><a data-full="true" class="ajaxUrl" data-complete-text="<?php echo Yii::t('admin', 'The module is successfully loaded') ?>" href="/admin/shop/news"><span class="icon-bars icon-medium"></span> Статистика</a></li>
                    <li><a data-full="true" class="ajaxUrl  noBorderB" data-complete-text="<?php echo Yii::t('admin', 'The module is successfully loaded') ?>" href="/admin/shop/news"><span class="icon-cogs icon-medium"></span> Настройки</a></li>
                </ul>






            </div>
            <div class="sideWidget" id="seo">

     
                     
            </div>

            <div class="sideWidget" id="settings">


      
            </div>



        </div>


        <div class="divider"><span></span></div>

    </div> 
    <div class="clear"></div>
</div>
