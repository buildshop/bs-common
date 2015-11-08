<?php

/**
 * @author Andrew (panix) S. <andrew.panix@gmail.com>
 * 
 * @uses CAction
 */
class CheckCityAction extends CAction {

    public function run() {
        if (Yii::app()->request->isAjaxRequest) {
            if (Yii::app()->request->getPost('city')) {
                Yii::import('mod.contacts.models.ContactsCities');
                $city = ContactsCities::model()->findByAttributes(array('name' => Yii::app()->request->getPost('city')));
                $params = array(
                    'name' => $city->name,
                    'phone' => $city->phone
                );
                $this->saveCookieGeo($params);
                echo CJSON::encode($params);
                Yii::app()->end();
            }
            $this->controller->render('ext.checkCity.views._city', array(
            ));
        } else {
            throw new CHttpException(403);
        }
    }

    protected function saveCookieGeo($params) {
        $cookie = new CHttpCookie('city', $params['name']);
        $cookie->expire = time() + 3600 * 7;
        Yii::app()->request->cookies['city'] = $cookie;
    }

}