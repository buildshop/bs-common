<?php
Yii::import('mod.contacts.models.ContactsCountries');
Yii::import('mod.contacts.models.ContactsCities');
?>


<script>
    $(function(){

    });
</script>
<?php
$countries = ContactsCountries::model()->findAll();
?>
<ul class="nav nav-tabs" id="countries-tabs">
    <?php foreach ($countries as $country) { ?>
        <li>
            <a href="#country<?= $country->id ?>" data-toggle="tab">
                <span><img src="/uploads/language/<?= $country->country_code ?>.png" alt="<?= $country->name ?>" /></span> <?= $country->name ?>
            </a>
        </li>
    <?php } ?>

</ul>
<div class="tab-content">
    <?php foreach ($countries as $country) { ?>
        <div class="tab-pane" id="country<?= $country->id ?>">
            <div class="row">
                <?php foreach ($country->cities as $k => $city) { ?>
                    <?php
                    if ($k % 3 == 0) {
                        echo '<div class="clearfix"></div>';
                    }

                    $name = ($city->capital) ? '<b>' . $city->name . '</b>' : $city->name;
                    $class = ($city->name == CMS::getCookieCity()) ? 'btn-default' : 'link';
                    ?>
                    <div class="col-md-4 col-sm-4 col-xs-12 city-list">
                        <?php
                        echo Html::link($name, 'javascript:void(0)', array(
                            'data-name' => $city->name,
                            'class' => 'btn ' . $class
                        ));
                        ?>
                    </div>
                <?php }
                ?>
                <div class="clearfix"></div>
            </div>
        </div>
    <?php } ?>

</div>



<script>
    $(function(){
        $('.city-list > a').on('click',function(){
            var that = $(this);
            var name = that.attr('data-name');
            setCity(that,name);
            return false;
        });
        
        $('#countries-tabs a').click(function (e) {
            e.preventDefault()
            $(this).tab('show');
        });
        $('#countries-tabs a:first').tab('show');
    });
</script>