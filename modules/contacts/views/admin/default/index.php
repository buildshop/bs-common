<script src="http://api-maps.yandex.ru/2.0-stable/?load=package.standard&amp;lang=ru-RU" type="text/javascript"></script>
<script type="text/javascript">
    // ymaps.ready(init('<?= CJSON::encode($config); ?>','tester'));
    ymaps.ready(init);

    function init () {
        var myMap = new ymaps.Map("map", {
            center: [<?php echo $config['yandex_map_center'] ?>],
            zoom: <?php echo $config['yandex_map_zoom'] ?>
        }),
   
        myPlacemark = new ymaps.Placemark([<?php echo $config['yandex_map_center'] ?>], {
            // Чтобы балун и хинт открывались на метке, необходимо задать ей определенные свойства.
            balloonContentHeader: "<?= Yii::app()->settings->get('core', 'site_name') ?>",
            balloonContentBody: "<div style='margin:10px 0;'><?php echo $config['yandex_map_balloon_content'] ?></div>",
            //balloonContentFooter: "Подвал",
            hintContent: "<?= Yii::app()->settings->get('core', 'site_name') ?>"
        });
        myMap.controls
        // Кнопка изменения масштаба.
        .add('searchControl')
<?php if ($config['yandex_map_zoomControl']) { ?>
            .add('zoomControl', { left: <?php echo $config['yandex_map_zoomControl_left'] ?>, top: <?php echo $config['yandex_map_zoomControl_top'] ?> })
<?php } ?>
<?php if ($config['yandex_map_typeSelector']) { ?>
            .add('typeSelector', { right: <?php echo $config['yandex_map_typeSelector_right'] ?>, top: <?php echo $config['yandex_map_typeSelector_top'] ?> })
<?php } ?>
<?php if ($config['yandex_map_mapTools']) { ?>
            .add('mapTools', { left: <?php echo $config['yandex_map_mapTools_left'] ?>, top: <?php echo $config['yandex_map_mapTools_top'] ?> })
<?php } ?>
        myMap.geoObjects.add(myPlacemark);
        myMap.events.add('click', function (e) {
            if (!myMap.balloon.isOpen()) {
                var coords = e.get('coordPosition');
                myMap.balloon.open(coords, {
                    contentBody:'<a href="javascript:void(0)" onClick="setCoords(\''+ [coords[0].toPrecision(6),coords[1].toPrecision(6)].join(', ') + '\');">Установить кординаты на центр карты?</a><br/><p>Координаты: ' + [
                        coords[0].toPrecision(6),
                        coords[1].toPrecision(6)
                    ].join(', ') + '</p>'
                });
            }
            else {
                myMap.balloon.close();
            }
        });
    }
    


</script>
<script>


    $(function(){
        $('#uniform-ConfigForm_yandex_map_zoomControl').click(function(){
            $('#yandex_map_zoomControl').toggleClass('hidden');
        });
        if($('#ConfigForm_yandex_map_zoomControl').attr('checked')){
            $('#yandex_map_zoomControl').removeClass('hidden');
        }else{
            $('#yandex_map_zoomControl').addClass('hidden');
        }
            
            
        $('#ConfigForm_yandex_map_typeSelector').click(function(){
            $('#yandex_map_typeSelector').toggleClass('hidden');
        });
        if($('#ConfigForm_yandex_map_typeSelector').attr('checked')){
            $('#yandex_map_typeSelector').removeClass('hidden');
        }else{
            $('#yandex_map_typeSelector').addClass('hidden');
        }
            
        $('#ConfigForm_yandex_map_mapTools').click(function(){
            $('#yandex_map_mapTools').toggleClass('hidden');
        });
        if($('#ConfigForm_yandex_map_mapTools').attr('checked')){
            $('#yandex_map_mapTools').removeClass('hidden');
        }else{
            $('#yandex_map_mapTools').addClass('hidden');
        }
            
            

    });
        
           function setCenterCoords(coordY,coordX){
        $('input#ConfigContactForm_yandex_map_center').val(coordX+','+coordY);
        $.jGrowl('Кординаты центра карты устрановны.')
    } 
        
   
</script>
<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => 'fluid tableTabs')
));

echo $model->getForm()->tabs();
Yii::app()->tpl->closeWidget();
?>
<script type="text/javascript">
    $(function(){
        $('#ConfigContactForm_yandex_map_zoomControl').click(function(){
           
            $('.field_yandex_map_zoom').toggleClass('hidden');
            hasChecked('#uniform-ConfigContactForm_yandex_map_zoomControl span');
        });
        hasChecked('#uniform-ConfigContactForm_yandex_map_zoomControl span');
    });
    
    function hasChecked(has, classes){
        if($(has).hasClass('checked')){
            $(classes).removeClass('hidden');
        }else{
            $(classes).addClass('hidden');
        }
    }

</script>
<?php
Yii::app()->tpl->openWidget(array(
    'title' => 'Карта',
    'htmlOptions' => array('class' => 'fluid tableTabs')
));
?>
<div class="formRow">



    <div id="map" style="margin:0 auto;width:100%;height:<?php echo $config['yandex_map_height'] ?>px;"></div>

</div>

<?php Yii::app()->tpl->closeWidget(); ?>