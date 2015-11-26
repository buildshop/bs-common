<?php

class CMapWidget extends CWidget {

    public $options = array();

    /**
     * Язык отображение карты
     * @var string 
     */
    protected $map_language = 'ru-RU';

    public function init() {
        if (Yii::app()->languageManager->active->locale)
            $this->map_language = Yii::app()->languageManager->active->locale;
        $this->registerScript();
    }

    /**
     * Отображаем елемент карты.
     * @param string $mapid
     * @param array $option
     */
    protected function renderMap($mapid, array $option) {
        echo Html::tag('div', array(
            'id' => $mapid,
            'class' => 'ymap',
            'style' => 'width:' . $option['width'] . ';height:' . $option['height'] . ''), '', true);
    }

    /**
     * Регистрируем js карт.
     */
    protected function registerScript() {
        $cs = Yii::app()->clientScript;
        if (static::MAP_VERION > 2.0) {
            $cs->registerScriptFile('http://api-maps.yandex.ru/' . static::MAP_VERION . '/?lang=' . $this->map_language, CClientScript::POS_HEAD);
        } else {
            $cs->registerScriptFile('http://api-maps.yandex.ru/' . static::MAP_VERION . '/?load=package.full&lang=' . $this->map_language . '', CClientScript::POS_HEAD);
        }

        $apiJS = (static::MAP_VERION > 2.0) ? 'yandexmap2.1.js' : 'yandexmap2.0.js';
        $cs->registerScriptFile(Yii::app()->getModule('contacts')->assetsUrl . '/js/' . $apiJS, CClientScript::POS_HEAD);
    }

    /**
     * Получяем список маркеров карты.
     * @param ContactsMaps $model
     * @return array
     */
    protected function getMapMarkers(ContactsMaps $model) {
        $result = array();
        foreach ($model->markers as $marker) {
            $coords = explode(',', $marker->coords);
            if (empty($marker->icon_content)) {
                $preset = $marker->preset;
                $color = $marker->color;
            } else {
                $preset = 'islands#blackStretchyIcon';
                $color = null;
            }
            if ($marker->hasIcon()) {
                $iconArray = array(
                    'iconLayout' => 'default#imageWithContent',
                    'iconImageHref' => $marker->getImageUrl(),
                    'iconImageSize' => array($marker->imageSize[0], $marker->imageSize[1]), //array($marker->imageSize['width'], $marker->imageSize['height'])
                    'iconImageOffset' => array((int) $marker->icon_file_offset_x, (int) $marker->icon_file_offset_y)
                );
            } else {
                $iconArray = array();
            }
            $result[] = array(
                // 'coords' => array((float) $coords[0],(float) $coords[1]),
                //  'coords' => array($coords[0],$coords[1]),
                'coordy' => (float) $coords[0],
                'coordx' => (float) $coords[1],
                'name' => $marker->name,
                'properties' => array(
                    'balloonContent' => Yii::app()->controller->renderPartial('mod.contacts.widgets.map.views.balloon', array('data' => $marker), true, false),
                    'iconContent' => $marker->icon_content,
                    'hintContent' => $marker->hint_content,
                //'balloonContentHeader' => $marker->balloon_content_header,
                //'balloonContentBody' => $marker->balloon_content_body,
                //'balloonContentFooter' => $marker->balloon_content_footer,
                ),
                'options' => CMap::mergeArray(array(
                    'preset' => $preset,
                    'color' => $color,
                        ), $iconArray),
            );
        }
        return $result;
    }

    /**
     * Получаем опции карты
     * @param ContactsMaps $model
     * @return array
     */
    protected function getOptions(ContactsMaps $model) {
        $coords_center = explode(',', $model->center);
        $routers = array();
        foreach ($model->routers as $route) {
            $routers[] = CJSON::decode($route->getJsonRoute());
        }
        return array(
            'width' => $model->width,
            'height' => $model->height,
            'mapTools' => (int) $model->mapTools,
            // 'zoomControl' => (int) $model->zoomControl,
            'zoomControl' => array(
                'enable' => (int) $model->zoomControl,
                'top' => is_null($model->zoomControl_top) ? null : (int) $model->zoomControl_top,
                'bottom' => is_null($model->zoomControl_bottom) ? null : (int) $model->zoomControl_bottom,
                'left' => is_null($model->zoomControl_left) ? null : (int) $model->zoomControl_left,
                'right' => is_null($model->zoomControl_right) ? null : (int) $model->zoomControl_right,
            ),
            'zoom' => (int) $model->zoom,
            'scrollZoom' => (int) $model->scrollZoom,
            'auto_show_routers' => (int) $model->auto_show_routers,
            'routes' => $routers,
            'center' => array(
                'let' => $coords_center[0],
                'lng' => $coords_center[1]
            ),
            'type' => $model->type,
            'drag' => (int) $model->drag,
        );
    }

    /**
     * Получаем опции карты
     * @param ContactsMaps $model
     * @return array
     */
    protected function getRouters(ContactsMaps $model) {
        $coords_center = explode(',', $model->center);
        return array(
            'width' => $model->width,
            'height' => $model->height,
            'mapTools' => (int) $model->mapTools,
            'zoomControl' => (int) $model->zoomControl,
            'zoom' => (int) $model->zoom,
            'auto_show_routers' => (int) $model->auto_show_routers,
            'mapStateAutoApply' => (int) $model->mapStateAutoApply,
            'center' => array(
                'let' => $coords_center[0],
                'lng' => $coords_center[1]
            ),
            'type' => $model->type,
            'drag' => (int) $model->drag,
        );
    }

}
