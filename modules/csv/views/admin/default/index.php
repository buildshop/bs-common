<div class="widget"><?php
$this->widget('zii.widgets.jui.CJuiTabs', array(
    'tabs' => array(
        'Импорт' => array('ajax' => $this->createUrl('/admin/csv/default/import'), 'id' => 'import'),
        'Экспорт' => array('ajax' => $this->createUrl('/admin/csv/default/export'), 'id' => 'export'),
    ),
    'options' => array(
        'collapsible' => true,
        'beforeLoad' => 'js:function (e, ui) {
           // $.jGrowl("Загрузка раздела");
            //$(ui.panel).html("Загрузка раздела");
        }',
        'load' => 'js:function(e, ui) {
          //   init_uniform(); 
        }',
    ),
));
?>
</div>
