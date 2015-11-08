<div class="services">
    <ul class="auth-services clear">
        <?php
        foreach ($services as $name => $service) {
            echo '<li class="auth-service ' . $service->id . '">';
            echo Html::link('<span class="auth-icon ' . $service->id . '"></span>', array('/users/login', 'service' => $name), array(
                'class' => 'auth-link ' . $service->id,
            ));
            echo '</li>';
        }
        ?>
    </ul>
</div>
