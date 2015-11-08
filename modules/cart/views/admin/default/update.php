<div class="row">
    <div class="col-lg-6">
        <?php
        Yii::app()->tpl->openWidget(array(
            'title' => Yii::t('CartModule.admin', 'UPDATE_ORDER', array(
                        '{order_id}' => CHtml::encode($model->id)
                    )),
            'htmlOptions' => array('class' => '')
        ));
// Render tabs
        $tabs = array(
            Yii::t('CartModule.admin', 'ORDER', 1) => $this->renderPartial('_order_tab', array(
                'model' => $model,
                'statuses' => $statuses,
                'deliveryMethods' => $deliveryMethods,
                'paymentMethods' => $paymentMethods
                    ), true),
        );

        if (!$model->isNewRecord) {
            // Add history tab
            $tabs[Yii::t('CartModule.admin', 'HISTORY')] = array(
                'ajax' => $this->createUrl('history', array('id' => $model->id))
            );
        }
        ?>


        <?php
        $this->widget('zii.widgets.jui.CJuiTabs', array(
            'tabs' => $tabs,
            'options' => array(
                //'collapsible'=>true,
                'selected' => 0,
                'beforeLoad' => 'js:function(event,object){
                    app.addLoader();

           // object.ajaxSettings.url="index.php?r=test/ajaxTab";
                    object.ajaxSettings.complete=function(jqXHR,errorcode){

                        if(errorcode=="success"){
                            app.removeLoader();
                        }
                    };
                        
                }'
            ),
        ));
        ?>

        <?php Yii::app()->tpl->closeWidget(); ?>
    </div>
    <div class="col-lg-6">
        <?php if (!$model->isNewRecord) { ?>
            <div id="dialog-modal" style="display: none;" title="<?= Yii::t('CartModule.admin', 'CREATE_PRODUCT') ?>">
                <?php
                $this->renderPartial('_addProduct', array(
                    'model' => $model,
                ));
                ?>
            </div>
            <div id="orderedProducts">
                <?php
                if (!$model->isNewRecord) {
                    $this->renderPartial('_orderedProducts', array(
                        'model' => $model,
                    ));
                }
                ?>
            </div>
        <?php } else { ?>
            <?php Yii::app()->tpl->alert('info', Yii::t('CartModule.admin', 'ALERT_CREATE_PRODUCT'), false); ?>
        <?php } ?>



    </div></div>