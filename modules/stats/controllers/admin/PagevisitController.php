<?php

class PagevisitController extends CStatsController {

    public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('StatsModule.default', 'BROWSERS');
        $this->breadcrumbs = array(
            Yii::t('StatsModule.default', 'MODULE_NAME') => array('/admin/shop'),
            $this->pageName
        );
        $stats = Yii::app()->stats->initRun();
        $zp = $stats['zp'];

        if ($this->sort == "hi") {
            $z = "SELECT req, COUNT(req) cnt FROM cms_surf WHERE";
            $z .= $zp . " AND dt >= '$this->sdate' AND dt <= '$this->fdate' GROUP BY req ORDER BY 2 DESC";
            $res = Yii::app()->db->createCommand($z)->queryAll();

            $z2 = "SELECT SUM(t.cnt) as cnt FROM (" . $z . ") t";
            $r = Yii::app()->db->createCommand($z2)->queryRow();
        } else {
            $z = "CREATE TEMPORARY TABLE IF NOT EXISTS tmp_surf SELECT ip, req FROM cms_surf WHERE";
            $z .= $zp . " AND dt >= '$this->sdate' AND dt <= '$this->fdate' GROUP BY ip, req";
            $z2 = "SELECT req, COUNT(req) cnt FROM tmp_surf GROUP BY req ORDER BY 2 DESC";

            $transaction = Yii::app()->db->beginTransaction();
            try {
                Yii::app()->db->createCommand($z)->execute();
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
            }


            $res = Yii::app()->db->createCommand($z2)->queryAll();

            $z3 = "SELECT SUM(t.cnt) as cnt FROM (" . $z2 . ") t";

            $transaction2 = Yii::app()->db->beginTransaction();
            try {
                Yii::app()->db->createCommand($z)->execute();
                $transaction2->commit();
            } catch (Exception $e) {
                $transaction2->rollBack();
            }
            
            $r = Yii::app()->db->createCommand($z3)->queryRow();
        }

        $cnt = $r['cnt'];


        $k = 0;


        foreach ($res as $row) {
            if ($k == 0)
                $max = $row['cnt'];
            if ($row['req'] == "")
                $row['req'] = "<font color=grey>неизвестно</font>";
            $k++;


            $result[] = array(
                'num' => $k,
                'req' => Html::link($row['req'], $row['req'], array('traget' => '_blank')),
                'h' => $row['cnt'],
                'graphic' => "<img align=left src=/stats/px" . (($this->sort == "hi") ? "h" : "u") . ".gif width=" . ceil(($row['cnt'] * 100) / $max) . " height=11 border=0>",
                'pracent' => (number_format((($row['cnt'] * 100) / $cnt), 1, ',', '')),
                'detail' => Html::link('>>>', "?pz=1&tz=1&item=req&s_date=" . StatsHelper::dtconv($this->sdate) . "&f_date=" . StatsHelper::dtconv($this->fdate) . "&qs=" . urlencode($row['req']) . "&sort=" . (empty($this->sort) ? "ho" : $this->sort), array('traget' => '_blank'))
            );
        }



        $dataProvider = new CArrayDataProvider($result, array(
                    'sort' => array(
                        // 'defaultOrder'=>'id ASC',
                        'attributes' => array(
                            'ip' => array(
                                'asc' => 'ip DESC',
                                'desc' => 'ip ASC',
                            ),
                            'refer' => array(
                                'asc' => 'refer DESC',
                                'desc' => 'refer ASC',
                            ),
                        ),
                    ),
                    'pagination' => array(
                        'pageSize' => 10,
                    ),
                ));

        $this->render('index', array(
            'dataProvider' => $dataProvider
        ));
    }

}
