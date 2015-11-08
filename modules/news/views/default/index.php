<h1><?php echo $this->pageName; ?></h1>

<?php
$this->widget('ListView', array(
    'dataProvider' => $provider->search(),
    'id' => 'news-list',
    'ajaxUpdate' => true,
    'template' => '{items} {pager}',
    'itemView' => '_list',
    'pager' => array(
        'header' => ''
    ),
));
?>
