<?php

/**
 * Handle ajax requests
 */
class AjaxController extends Controller {

    public function actionRating($id) {
        $request = Yii::app()->request;
        $session = Yii::app()->session;
        if ($request->isAjaxRequest) {
            $model = ShopProduct::model()->active()->findByPk($id);

            $cookieName = 'rating_' . $model->id;
            $rating = (int) $_GET['rating'];
            if ($model && in_array($rating, array(1, 2, 3, 4, 5))) {
                $model->saveCounters(array(
                    'votes' => 1,
                    'rating' => $rating
                ));

                $cookie = new CHttpCookie($cookieName, true);
                $cookie->expire = time() + 60 * 60 * 24 * 60;
                Yii::app()->request->cookies[$cookieName] = $cookie;
                $session->timeout=time() + 60 * 60 * 24 * 60;
                $session['rating']['products'][$id]=$rating;
                  
            }
        }
    }

    /**
     * Set currency for user session.
     */
    public function actionActivateCurrency() {
        Yii::app()->currency->setActive(Yii::app()->request->getParam('id'));
    }

    /**
     * Rate product
     * @param integer $id product id
     */
    public function actionRateProduct($id) {
        $request = Yii::app()->request;
        if ($request->isAjaxRequest) {
            $model = ShopProduct::model()->active()->findByPk($id);

            $mod = 'product';
            $rating = (int) $_GET['rating'];
            if ($model && in_array($rating, array(1, 2, 3, 4, 5))) {



                $model->votes +=1;
                $model->rating += $rating;
                $model->save();
                $new = time();
                $ratingModel = new RatingModel;
                $ratingModel->mid = $id;
                $ratingModel->modul = $mod;
                $ratingModel->time = $new;
                $ratingModel->user_id = Yii::app()->user->getId();
                $ratingModel->host = '127.0.0.1';
                $ratingModel->save();
                $cookie = new CHttpCookie($mod . "-" . $id, $id);
                $cookie->expire = time() + 60 * 60 * 24 * 60;
                Yii::app()->request->cookies[$mod . "-" . $id] = $cookie;

                return $this->widget('ext.rating.Rating', array(
                            'pid' => $id,
                            'rating' => $model->rating,
                            'votes' => $model->votes
                        ));
                /*  if($model->saveCounters(array(
                  'votes' => 1,
                  'rating' => $rating
                  ))){
                  die('sss');
                  }else{
                  die($rating);
                  } */
            }
        }
    }

}