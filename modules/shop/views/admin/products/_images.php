<script>
    $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    $(document).ready( function() {
        $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
        
            var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;
        
            if( input.length ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }
        
        });
    });
</script>

<div class="clearfix"></div>

<?php
/* $this->widget('xupload.XUpload', array(
  'url' => Yii::app()->createUrl("admin/shop/products/upload"),
  // 'url' => Yii::app()->createUrl("admin/shop/products/getImages/?id=2"),
  'model' => $uploadModel,
  'attribute' => 'files',
  'multiple' => true,
  )); */

/**
 * Images tabs
 */
Yii::import('ext.jqPrettyPhoto');
//Yii::import('application.modules.shop.components.ShopImagesConfig');
// Register view styles
Yii::app()->getClientScript()->registerCss('infoStyles', "
	table.imagesList {
                border: 1px solid #dddddd;
		float: left;
		width: 30%;
		min-width:250px;
		margin: 10px;
	}

");
?>
<div class="col-xs-12">
    <div class="text-center">
        <span class="btn btn-primary btn-file"> Выбрать изображение
            <?php
            $this->widget('ext.multifile.MultiFileUpload', array(
                'name' => 'ShopProductImages',
                'model' => $model,
                'attribute' => 'files',
                'accept' => implode('|', array('jpg', 'jpeg', 'png', 'gif')),
                'htmlOptions' => array(
                    'class' => ''
                )
            ));
            ?>
        </span>
    </div>
</div>

<?php
// Images
if ($model->images) {
    ?>

    <script>
        $(function(){
            $(".gallery ul li").hover(
            function() { $(this).children(".actions").show("fade", 200); },
            function() { $(this).children(".actions").hide("fade", 200); }
        );
        });
    </script>


    <?php foreach ($model->images as $image) { ?>
        <div class="col-lg-3 " id="ProductImage<?= $image->id ?>"><div class="product-image">
                <?php echo Html::link(Html::image($image->getUrl(false, false, true), Html::encode($image->name), array('height' => '150px')), $image->getUrl(false, false, true), array('style' => 'display: block;', 'class' => 'fancybox thumbnail', 'title' => CHtml::encode($model->name))); ?>
                <?php
                echo Html::label('Главное', 'main_image_' . $image->id);
                echo Html::radioButton('mainImageId', $image->is_main, array(
                    'value' => $image->id,
                    'class' => 'check',
                    'id' => 'main_image_' . $image->id
                ));
                ?>
                <div class="input-group">
                    <?php echo Html::textField('image_titles[' . $image->id . ']', $image->title, array('placeholder' => 'Image name', 'class' => 'form-control')); ?>
                    <span class="input-group-btn">
                        <?php
                        echo Html::ajaxLink('<i class="flaticon-delete"></i>', $this->createUrl('deleteImage', array('id' => $image->id)), array(
                            'type' => 'POST',
                            'data' => array(Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken),
                            'success' => "js:$('#ProductImage$image->id').hide().remove()",
                                ), array(
                            'id' => 'DeleteImageLink' . $image->id,
                            'class' => 'btn btn-danger',
                            'confirm' => Yii::t('ShopModule.admin', 'Вы действительно хотите удалить это изображение?'),
                        ));
                        ?></span>
                </div>
                <div class="actions" style="display: none;">
                    Дата: <?= CMS::date($image->date_create) ?><br/>
                    Загрузил: <?= ($image->author) ? Html::encode($image->author->login) : '' ?>

                </div>
            </div>

        </div>
        <?php
    }
    ?>


<?php } ?>
<div class="clearfix"></div>
<?php
// Fancybox ext
$this->widget('ext.fancybox.Fancybox', array(
    'target' => 'a.fancybox',
    'config' => array(),
));
