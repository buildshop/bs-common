<script>
 
                
          
    /*
    $(function(){
        var ip='195.78.247.104';

        $.get("http://ipinfo.io/"+ip, function (response) {
            // $('#current-city').html(response.city);
            console.log(response);
        }, "jsonp");
    })*/

</script>

<a href="javascript:void(0)" id="select-city" data-placement="bottom">Ваш город: <span id="current-city"><?= CMS::getCookieCity() ?></span></a>
<div id="select-city-popover" style="display: none;">
    <div class="text-center">
        <p><small>Ваш город - <b><?= CMS::getCookieCity() ?></b>,<br/>угадали?</small></p>

        <span class="btn-group-sm">
            <a href="javascript:void(0)" class="btn btn-primary" data-button-yes="true" onClick="setCity(this,'<?= CMS::getCookieCity() ?>');"><?= Yii::t('app', 'YES') ?></a>
            <a href="javascript:void(0)" class="btn btn-link" onClick="$('#select-city').popover('hide'); load_select_city();"><?= Yii::t('app', 'NO') ?></a>
        </span>
    </div>
</div>

<script>
    
    console.log($.cookie('city'));
     if(!$.cookie('city')){
        $('#select-city').on('show.bs.popover', function () {
            $('body').append('<div class="ui-widget-overlay"></div>');
        });

        $("#select-city").popover({
            html : true,
            delay: { "show": 0, "hide": 0 },
            content: function() {
                return $('#select-city-popover').html();
            }
        }).popover('toggle');
        
     }else{
        $('#select-city').on('click', function () {
            load_select_city();
        });
        console.log('222222222');
     }
     
     
    /*if(!$.cookie('city')){
        $('#select-city').on('show.bs.popover', function () {
            $('body').append('<div class="ui-widget-overlay"></div>');
        });

        $("#select-city").popover({
            html : true,
            delay: { "show": 0, "hide": 0 },
            content: function() {
                return $('#select-city-popover').html();
            }

        }).popover('toggle');
        console.log('1');
    }else{
        $('#select-city').on('click', function () {
            load_select_city();
        });
       
        console.log($.cookie('city'));
        console.log('2');
    }*/

    
    $('#select-city').on('hide.bs.popover', function () {
        $('.ui-widget-overlay').remove();
    });

</script>






