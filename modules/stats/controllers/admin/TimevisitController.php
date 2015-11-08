<?php

class TimevisitController extends CStatsController {

    public $topButtons = false;

    public function actionIndex() {
        $top = $_GET['top'];
        $pos = $_GET['pos'];
        $engin = $_GET['engin'];
        $dy = $_GET['dy'];
        $domen = $_GET['domen'];
        $brw = $_GET['brw'];
        $str_f = $_GET['str_f'];
       // $s_date = $_GET['s_date'];
        //$f_date = $_GET['f_date'];
        $sort = $_GET['sort'];
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



        //$s_date = '20150312';
        //$f_date = '20150312';
        
                if ($sort == "hi") {
                    $z = "SELECT substr(tm,-5,2) as tm FROM cms_surf WHERE";
                    $z .= $this->_zp . " AND dt >= '$this->sdate' AND dt <= '$this->fdate'";
                    $res = Yii::app()->db->createCommand($z);
                    //$res = mysql_query($z);
                } else {
                    $z = "SELECT substr(tm,-5,2) as tm,ip FROM cms_surf WHERE";
                    $z .= $this->_zp . " AND dt >= '$this->sdate' AND dt <= '$this->fdate' GROUP BY 2,1";
                    $res = Yii::app()->db->createCommand($z);
                    
                    //$res = mysql_query($z);
                }
                foreach($res->queryAll() as $row){
                    $tmas[$row['tm']]++;
                }

        $this->render('index', array(
            'tmas' => $tmas,
            'sort' => $sort,
            'cnt'=>$cnt,
            'vse'=>$vse,
            'mmx'=>$mmx,
            'brw'=>$brw,
            'k'=>$k,
        ));
    }




}
