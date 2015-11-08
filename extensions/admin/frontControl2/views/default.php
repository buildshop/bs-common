<div class="btn-group ControlPanel position-<?=$this->options['position']?>">
    <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gear"></i></button>
    <ul class="dropdown-menu pull-<?=$this->options['position']?>">
        <?= $this->renderItems(); ?>
    </ul>
</div>

