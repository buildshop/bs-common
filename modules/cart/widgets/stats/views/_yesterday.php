  <?php
  $this->widget('ext.highcharts.HighchartsWidget', array(
                    'scripts' => array(
                        'highcharts-more', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
                    // 'modules/exporting', // adds Exporting button/menu to chart
                    //  'themes/grid'        // applies global 'grid' theme to all charts
                    ),
                    'options' => array(
                        'chart' => array(
                            'type' => 'column',
                            'plotBackgroundColor' => null,
                            'plotBorderWidth' => null,
                            'plotShadow' => false,
                            'backgroundColor' => 'rgba(255, 255, 255, 0)'
                        ),
                        'title' => null,
                        'yAxis' => array(
                            'title' => null
                        ),
                        'plotOptions' => array(
                            'areaspline' => array(
                                'fillOpacity' => 0.5
                            )
                        ),
                        'tooltip' => array(
                            'shared' => true,
                        //  'valueSuffix' => ' кол.'
                        ),
                        'series' => array(
                            array('name' => 'Сумма заказов', 'data' => array(20)),
                            array('name' => 'Заказы', 'data' => array('30.000')),
                        ),
                        'credits' => array(
                            'enabled' => false
                        )
                    )
                ));
                ?>