<?php

class BrowsersController extends CStatsController {

    public $topButtons = false;

    public function actionIndex() {
        $pos = $_GET['pos'];
        $engin = $_GET['engin'];
        $dy = $_GET['dy'];
        $domen = $_GET['domen'];
        $brw = $_GET['brw'];
        $str_f = $_GET['str_f'];
        $qq = $_GET['qq'];
        $this->pageName = Yii::t('StatsModule.default', 'BROWSERS');
        $this->breadcrumbs = array(
            Yii::t('StatsModule.default', 'MODULE_NAME') => array('/admin/shop'),
            $this->pageName
        );




        $vse = 0;
        $k = 0;
        if (empty($pos))
            $pos = 10;


        if ($this->sort == "hi") {
            $sql = "SELECT user FROM cms_surf WHERE dt >= '$this->sdate' AND dt <= '$this->fdate' AND " . $this->_zp;
            $command = Yii::app()->db->createCommand($sql);
            foreach ($command->queryAll() as $row) {
                $bmas[StatsHelper::GetBrowser($row['user'])]++;
            }
        } else {
            $sql = "SELECT user, ip FROM cms_surf WHERE dt >= '$this->sdate' AND dt <= '$this->fdate' AND " . $this->_zp . " GROUP BY ip, user";
            $command = Yii::app()->db->createCommand($sql);
            foreach ($command->queryAll() as $row) {
                $gb = StatsHelper::GetBrowser($row['user']);
                if (!isset($ipmas[$row['ip']][$gb])) {
                    $bmas[$gb]++;
                    $ipmas[$row['ip']][$gb] = 1;
                }
            }
        }

        $this->render('index', array(
            'bmas' => $bmas,
            'cnt' => $cnt,
            'vse' => $vse,
            'mmx' => $mmx,
            'brw' => $brw,
            'k' => $k,
        ));
    }

    public function actionView() {
        $this->pageName = 'dsadsa';
        $pos = $_GET['pos'];
        $engin = $_GET['engin'];
        $dy = $_GET['dy'];
        $domen = $_GET['domen'];
        $brw = $_GET['brw'];
        $str_f = $_GET['str_f'];
        $qq = $_GET['qq'];


        $vse = 0;
        $k = 0;
        if (empty($pos))
            $pos = 99999;

        if ($this->sort == "hi") {
            $sql = "SELECT user, COUNT(user) cnt FROM cms_surf WHERE";
             if (!empty($str_f))
                 $sql .= " user LIKE '%$str_f%' AND"; //StatsHelper::GetBrw($brw)
            $sql .= $this->_zp . " AND dt >= '$this->sdate' AND dt <= '$this->fdate' " . (isset($brw) ? StatsHelper::GetBrw($brw) : "") . " GROUP BY user ORDER BY 2 DESC";
            //$res = mysql_query($z);
            $res = Yii::app()->db->createCommand($sql);

            $full_sql = "SELECT SUM(t.cnt) as cnt FROM (" . $sql . ") t";


            $r = Yii::app()->db->createCommand($full_sql);
        } else {
            
            $sql = "CREATE TEMPORARY TABLE tmp_surf SELECT ip, user FROM cms_surf WHERE";
            if (!empty($str_f))
                $sql .= " user LIKE '%$str_f%' AND";
            $sql .= $this->_zp . " AND dt >= '$this->sdate' AND dt <= '$this->fdate' " . (isset($brw) ? StatsHelper::GetBrw($brw) : "") . " GROUP BY ip" . (!isset($brw) ? ",user" : "");
            $sql2 = "SELECT user, COUNT(user) cnt FROM tmp_surf GROUP BY user ORDER BY 2 DESC";
            $res = Yii::app()->db->createCommand($sql);
            $transaction = Yii::app()->db->beginTransaction();
            try {
                Yii::app()->db->createCommand($sql2)->execute();
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
            }


            $z3 = "SELECT SUM(t.cnt) as cnt FROM (" . $sql2 . ") t";
        
            
            $transaction2 = Yii::app()->db->beginTransaction();
            try {
                Yii::app()->db->createCommand($sql)->execute();
                $transaction2->commit();
            } catch (Exception $e) {
                $transaction2->rollBack();
            }

            $r = Yii::app()->db->createCommand($z3);
        }
        $smd = $r->queryRow();
        $cnt = $smd['cnt'];
        if (!empty($brw)) {
            switch ($brw) {
                case "ie.png": $browserName = "MS Internet Explorer";
                    break;
                case "opera.png": $browserName = "Opera";
                    break;
                case "firefox.png": $browserName = "Firefox";
                    break;
                case "chrome.png": $browserName = "Google Chrome";
                    break;
                case "mozilla.gif": $browserName = "Mozilla";
                    break;
                case "safari.png": $browserName = "Apple Safari";
                    break;
                case "mac.gif": $browserName = "Macintosh";
                    break;
                case "maxthon.png": $browserName = "Maxthon (MyIE)";
                    break;
                default: $browserName = "другие";
                    break;
            }
        }

        $this->render('view', array(
            'items' => $res->queryAll(),
            'cnt' => $cnt,
            'max' => $max,
            'browserName' => $browserName,
            'vse' => $vse,
            'k' => $k,
            'pos' => $pos
        ));
    }

}
