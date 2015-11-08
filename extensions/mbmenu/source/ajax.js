var ajax = window.CMS_ajax || {};
ajax = {
    loaderClass:'#loading',
    showLoading:function(){
        $(ajax.loaderClass).css('background-position', function () {
            var top = $(ajax.loaderClass).show().offset().top;
            $(ajax.loaderClass).css({
                height:$(window).height()-top
            });
            $(ajax.loaderClass).hide();
            return '50% 50%';
        // return '50% ' + Math.floor($(window).height() + $(window).scrollTop() - top) / 2 + 'px';
        }).fadeIn(100);
    },
    hideLoading:function(){
        $(ajax.loaderClass).fadeOut(100);
    },
    init:function(){
        console.log('ajax mode init()');
    }
}
ajax.init();



$(function(){
    $('.subNav li a, .moduleButtons div a').click(function(e){
        var obj=$(this);
        var url=obj.attr('href');
        var activeClass='activeli';
        ajax.showLoading();
        $.ajax({
            type:'GET',
            url:url,
            data:{
                _ajax:true
            },
            success:function(data){
                $(obj).parent().parent().find('li').removeClass(activeClass);
                $(obj).parent().addClass(activeClass);
                if(window.History.enabled){
                    var url2 = url.split('?');
                    window.History.pushState(null, document.title, decodeURIComponent(url2));
                }
                $('#ajax-content').html(data);
                ajax.hideLoading();
            }
        });
        return false;
    });
});
