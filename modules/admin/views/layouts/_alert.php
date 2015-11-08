<div class="alert alert-<?= $type ?> <? if ($close) { ?>alert-dismissible fade in<? } ?>" id="alert<?= md5($type . CMS::translit($message)) ?>">
    <? if ($close) { ?>
        <button type="button" onClick="alertClose('<?= md5($type . CMS::translit($message)) ?>'); return false;" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <? } ?>
    <?php
    if ($type == 'success') {
        $icon = 'fa-check';
    } elseif ($type == 'danger') {
        $icon = 'flaticon-warning';
    }elseif ($type == 'info'){
           $icon = 'flaticon-info';
    }
    ?>
        <i class="<?= $icon ?>"></i> <?= $message ?>
</div>
<? if ($close) { ?>

<script>
$(function(){
   $('.alert-dismissible').delay(4000).fadeOut(500);
});
</script>
<? } ?>
