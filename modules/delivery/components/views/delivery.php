<script>
    function send(formid, reload){
        var str = $(formid).serialize();
        $.ajax({
            url: $(formid).attr('action'),
            type: 'POST',
            data: str,
            success: function(data){
                $(reload).html(data);
            },
            complete: function(){

            } 
        });
    }
</script>
<div id="delivery-responce"><?php echo $this->render('_delivery', array( 'model' => $model)); ?></div>
