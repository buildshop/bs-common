<?php
Yii::app()->clientScript->registerScript('tabs', "
(function($){
    $('.loganalyzer').on('click','.stack-btn',function(e){
        $(this).nextAll('.stack-pre').slideToggle('fast');
        e.preventDefault();
        return false;
    });
    
    $('#stack-showall').click(function(e){
        $('.stack-pre').slideDown('fast');
        e.preventDefault();
        return false;
    });
    
    $('#stack-collapseall').click(function(e){
        $('.stack-pre').slideUp('fast');
        e.preventDefault();
        return false;
    });
    
    $('#clear').click(function(e){
        if(!confirm('" . Yii::t('LogAnalyzer.main', 'Are you sure you want to clear this log file?') . "')) {
            e.preventDefault();
            return false;
        }
    });
    
    $('.filter-log').click(function (e) {
        var rel   = $(this).attr('rel'),
            error = $('.log-list .error-line'),
            warn  = $('.log-list .warning-line'),
            info  = $('.log-list .info-line');
            sql  = $('.log-list .sql-line');

        if (rel == 'error') {
            error.slideDown('fast');
            warn.slideUp('fast');
            info.slideUp('fast');
        } else if (rel == 'warning') {
            error.slideUp('fast');
            warn.slideDown('fast');
            info.slideUp('fast');
        } else if (rel == 'info') {
            error.slideUp('fast');
            warn.slideUp('fast');
            info.slideDown('fast');
        } else if (rel == 'sql') {
            error.slideUp('fast');
            warn.slideUp('fast');
            info.slideDown('fast');
        }else if (rel == 'all') {
            error.slideDown('fast');
            warn.slideDown('fast');
            info.slideDown('fast');
        }
        
        e.preventDefault();
        return false;
    });
})(jQuery);
"
);

Yii::app()->tpl->openWidget(array(
    'title' => $this->title,
    'htmlOptions' => array('class' => 'fluid')
));
?>

<div class="loganalyzer">

        <div class="col-lg-12">
            <a href="<?php echo $this->getUrl(); ?>" id="clear" class="btn btn-default"><?php echo Yii::t('LogAnalyzer.main', 'Clear Log') ?></a>



            <?php echo Yii::t('LogAnalyzer.main', 'Log Filter') ?>:
            <div class="btn-group">
                <a href="#" class="btn btn-default" rel='all'><?php echo Yii::t('LogAnalyzer.main', 'All') ?></a>
                <a href="#" class="btn btn-danger" rel='error'>Error</a>
                <a href="#" class="btn btn-warning" rel='warning'>Warning</a>
                <a href="#" class="btn btn-info" rel='info'>Info</a>
                <a href="#" class="btn btn-default" rel='sql'>SQL</a>
            </div>


            Stack Trace:
            <div class="btn-group">
                <a href="#" class="btn btn-default" id="stack-showall"><?php echo Yii::t('LogAnalyzer.main', 'Show All') ?></a>
                <a href="#" class="btn btn-default" id="stack-collapseall"><?php echo Yii::t('LogAnalyzer.main', 'Collapse All') ?></a>
            </div>
            <hr>
        </div>


        <div class="log-list col-lg-12">
            <?php
            $flag = false;
            if ($log) {
                foreach ($log as $l) {
                    if ($this->filterLog($l)) {
                        $status = $this->showStatus($l);
                        ?>
                        <div class="<?php echo $status['status'] ?>-line">
                            <span class="label <?php echo $status['class'] ?>"><?php echo ucfirst($status['status']); ?></span>
                            <span class="label label-info"><?php echo $this->showDate($l); ?></span> 
                            <a href="#" class="stack-btn btn btn-default btn-xs"><?php echo Yii::t('LogAnalyzer.main', 'Show') ?> Stack trace</a>
                            <pre><?php echo $this->showError($l); ?></pre>
                            <pre class="stack-pre" style="display:none;"><?php echo $this->showStack($l); ?></pre>
                        </div>
                        <?php
                    }
                }
            }
            ?>
        </div>

</div>
<?php Yii::app()->tpl->closeWidget(); ?>