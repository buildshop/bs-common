<?php

class DefaultController extends Controller {

    /**
     * Render sitemap.xml
     */
    public function actionIndex() {
        $cacheKey = 'sitemap.xml.data';
        $data = Yii::app()->cache->get($cacheKey);

        if (!$data) {
            $data = $this->renderPartial('xml', array(
                'urls' => $this->getModule()->getUrls()
                    ), true);

            Yii::app()->cache->set($cacheKey, $data, Yii::app()->settings->get('core','cache_time'));
        }

        if (!headers_sent())
            header('Content-Type: text/xml');

        echo $data;
    }

}