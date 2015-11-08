<h1>Обувь оптом</h1>

<?php
if(isset($model['items'])){
foreach ($model['items'] as $row) {
    ?>
    <div style="width:400px;float:left;">
        <div class="shoe-type">
            <div class="browseBlock">
                <img border="0" alt="<?php echo $row['label'] ?>" src="<?= $row['imagePath']; ?>">
            </div>
        </div>
        <div class="shoe-type-link">
            <h2><?php echo $row['label'] ?></h2>
            <?php
            if(isset($row['items'])){
            foreach ($row['items'] as $key => $subcat) { ?>
                <p><a href="<?= $subcat['url']['url'] ?>"><?= $subcat['label'] ?></a></p>
            <?php } ?>
  <?php } ?>
        </div>
    </div>
<?php } ?>
<?php } ?>