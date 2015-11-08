<div class="btn-group control-panel position-<?=$this->options['position']?>">
    <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown"><i class="flaticon-settings"></i></button>
    <ul class="dropdown-menu pull-<?=$this->options['position']?>">
        <?= $this->renderItems(); ?>
    </ul>
</div>

