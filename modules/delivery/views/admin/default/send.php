<script>  
    
    
var tasks = <?php echo json_encode($mails) ?>;

function doTask(taskNum, next, i,num){
    var time = Math.floor(Math.random()*3000);
$('.progress').removeClass('hidden');
$("#sended-result").prepend("<div class='senden-row"+i+"'Ожидание...</div>");
    setTimeout(function(){
    //    console.log(taskNum);

         $.ajax({
                type:"POST",
                url:"/admin/delivery/default/sendmail",
                data: {email:taskNum,text:"<?php echo $model->text ?>",themename:"<?php echo $model->themename ?>"},
                success:function(){
                    var result = Math.round((num/tasks.length*100),2);
                    $('.progress .bar').css({'width':result+'%'});
                    $("#sended").text(i+1);
                   $(".senden-row"+i).html("<div class='senden-row'><span class='icon-checkmark-circle' style='color:#97AF32'></span> "+taskNum+"</div>");

                        next();
                },
                beforeSend:function(){
                    $(".senden-row"+i).text("Идет отправка...");
                },
                complate:function(){
                  
                },
                error:function(XHR, textStatus, errorThrown){
                    $(".senden-row"+i).text("Error: "+XHR.status+" "+XHR.statusText);
                     $.jGrowl("Error: "+XHR.status+" "+XHR.statusText, {position:'top-right',sticky:true});
                }
            });
            
            
      
    },time)
}

function createTask(taskNum,i,num){
    return function(next){
        doTask(taskNum, next,i,num);
    }
}


 $("#progress-send").html("Отправлено: <span id='sended'>0</span> из <span id='total'>"+tasks.length+"</span>");
for (var i = 0; i < tasks.length; i++){
    var num = i+1
    $(document).queue('tasks', createTask(tasks[i],i,num));
}

$(document).queue('tasks', function(){
    console.log("all done");
    $("#sended-result").prepend("<div><b>Готово!</b></div>");
    $("#sendmail").remove(); //удаляем кнопку отправки когда все отправлено
});

$(document).dequeue('tasks');

    

     
        function sendMail(mails){
                  var total = mails.length;
var ema;
var test;
        var ajaxQuery = function( i, mail){
    
            $.ajax({
                type:"POST",
                data:{test:i},
                url:'http://novatec.loc/',
                complate:function(){
                    ajaxQuery(i, mail);
                }
            });
      
        }
       // var d = $.when();
  
        $('.progress').removeClass('hidden');
        $("#progress-send").html("Отправлено: <span id='sended'>0</span> из <span id='total'>"+total+"</span>");
        $.each(mails, function( i, mail){
 //d = $.when(d, $.post('groups.php',{'action':'add','name':$(this).text()}));
        });
//d.done(function() { alert('сохранено'); });

    
    
    
            }
    
    
    function sendMail222(mails){

        var total = mails.length;
        $('.progress').removeClass('hidden');
        $("#progress-send").html("Отправлено: <span id='sended'>0</span> из <span id='total'>"+total+"</span>");
        $.each(mails, function( i, mail ) {
            var num =  i+1;
     
            
    
            xhr = $.ajax({
                type:"POST",
                async:true,
                url:"/delivery/admin/sendmail",
                data: {email:mail,text:"<?php echo $model->text ?>",themename:"<?php echo $model->themename ?>"},
                success:function(){
                    var result = Math.round((num/total*100),2);
                    $('.progress .bar').css({'width':''+result+'%'});
                    $("#sended").text(i+1);
                    $(".senden-row"+num).html("<div class='senden-row'><span class='icon-checkmark-circle' style='color:#97AF32'></span> "+mail+"</div>");
                    if(i+1 == total){
                        $("#sended-result").prepend("<div><b>Готово!</b></div>");
                        $("#sendmail").remove(); //удаляем кнопку отправки когда все отправлено
                    }
                },
                beforeSend:function(){
                    $("#sended-result").prepend("<div class='senden-row"+num+"'>Идет отправка...</div>");
                }
            });

        });

    }
    
    

                            
                            
</script>
<div class="formRow">
    <div class="grid4">Тема письма:</div>
    <div class="grid8"><?php echo $model->themename ?></div>
    <div class="clear"></div>
</div>
<div class="formRow">
    <div class="grid4">Содержание письма:</div>
    <div class="grid8"><?php echo $model->text ?></div>
    <div class="clear"></div>
</div>
<div class="formRow">
    <div class="grid4">Кому отправлять</div>
    <div class="grid8"><?php echo $model->from ?></div>
    <div class="clear"></div>
</div>

<div class="formRow fluid">
    <div class="textC"><a href="/admin/delivery" class="buttonS bGreen">Назад</a>
        <a id="sendmail" href="javascript:void(0)" onClick='sendMail(<?php echo json_encode($mails) ?>)' class="buttonS bGreen">Начать отправку</a></div>
    <div class="clear"></div>
</div>


