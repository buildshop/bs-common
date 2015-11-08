<?php

class DetailController extends CStatsController {

    public $topButtons = false;

    public function actionIndex($date) {
        
    }

    public function actionOther($date) {
        $stats = Yii::app()->stats->initRun();
        $zp = $stats['zp'];
        $site = $stats['site'];
        $se_n = $stats['se_n'];
        $se_nn = $stats['se_nn'];
        $sql = "SELECT i,refer,ip,proxy,host,lang,user,tm,req from cms_surf WHERE dt='" . $date . "' AND " . $zp . " ORDER BY i ASC";
        $cmd = Yii::app()->db->createCommand($sql);
        $result = array();

        $f_se = array("yand", "google.", "go.mail.ru", "rambler.", "search.yahoo", "search.msn", "bing", "search.live.com");
        $f_se = array_merge($f_se, $se_nn);

        foreach ($cmd->queryAll(false) as $row) {
            //while ($row = mysql_fetch_row($rs)) {
            $refer = StatsHelper::Ref($row[1]);
            $skip = 0;
            foreach ($f_se as $val) {
                if (@stristr($refer, $val))
                    $skip = 1;
            }
            if (@stristr($refer, $site) and @stripos($refer, $site) == 0)
                $skip = 1;

            if (@array_key_exists($row[2], $i1_ip)) {
                if ((is_array($refer) or (($refer != "") and !(stristr($refer, "://" . $site) and stripos($refer, "://" . $site, 6) == 0) and !(stristr($refer, "://www." . $site) and stripos($refer, "://www." . $site, 6) == 0)) or $skip == 1))
                    ;
                else
                    $i2[$i1_ip[$row[2]]][] = array($row[7], $row[8]);
            }
            if (is_string($refer) and $refer != "" and !(stristr($refer, "://" . $site) and stripos($refer, "://" . $site, 6) == 0) and !(stristr($refer, "://www." . $site) and stripos($refer, "://www." . $site, 6) == 0) and $skip == 0) {
                $i1[$row[0]] = array($row[1], $row[2], $row[3], $row[4], $row[5], $row[6]);
                $i1_ip[$row[2]] = $row[0];
                $i2[$i1_ip[$row[2]]][] = array($row[7], $row[8]);
            }
        }
        $i1 = array_reverse($i1, true);
        foreach ($i1 as $id => $row) {


            $result[] = array(
                'refer' => StatsHelper::checkIdna($row[0]),
                'ip' => StatsHelper::getRowIp($row[1], $row[2]),
                'host' => StatsHelper::getRowHost($row[1], $row[2], $row[3], $row[4]),
                'user_agent' => StatsHelper::getRowUserAgent($row[5], $row[3]),
                'timelink' => StatsHelper::timeLink($i2, $id),
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

        $this->render('other', array(
            'dataProvider' => $dataProvider
        ));
    }

    public function actionSearch($date) {
        $this->pageName = 'search';
        $stats = Yii::app()->stats->initRun();
        $zp = $stats['zp'];
        $site = $stats['site'];
        $sql = "SELECT i,refer,ip,proxy,host,lang,user,tm,req from cms_surf WHERE dt='" . $date . "' AND " . $zp . " ORDER BY i ASC";
        $cmd = Yii::app()->db->createCommand($sql);
        $result = array();
        foreach ($cmd->queryAll(false) as $row) {
            $refer = StatsHelper::Ref($row[1]);
            if (@array_key_exists($row[2], $i1_ip)) {
                if ((is_array($refer) or (($row[1] != "") and !stristr($row[1], "://" . $site) and !stristr($row[1], "://www." . $site))))
                    ;
                else
                    $i2[$i1_ip[$row[2]]][] = array($row[7], $row[8]);
            }
            if (is_array($refer)) {
                $i1[$row[0]] = array($row[1], $row[2], $row[3], $row[4], $row[5], $row[6]);
                $i1_ip[$row[2]] = $row[0];
                $i2[$i1_ip[$row[2]]][] = array($row[7], $row[8]);
            }
        }
        $i1 = array_reverse($i1, true);
        foreach ($i1 as $id => $row) {
            $refer = StatsHelper::Ref($row[0]);
            if (is_array($refer)) {
                list($engine, $query) = $refer;
                $refer1 = StatsHelper::checkSearchEngine($row[0], $engine, $query);
            } else {
                $refer1 = StatsHelper::checkIdna($row);
            }

            $result[] = array(
                'refer' => $refer1,
                'ip' => StatsHelper::getRowIp($row[1], $row[2]),
                'host' => StatsHelper::getRowHost($row[1], $row[2], $row[3], $row[4]),
                'user_agent' => StatsHelper::getRowUserAgent($row[6], $row[1]),
                'timelink' => StatsHelper::timeLink($i2, $id),
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

        $this->render('search', array(
            'dataProvider' => $dataProvider
        ));
    }

    public function actionHits($date) {
        $this->pageName = 'HIts';
        $stats = Yii::app()->stats->initRun();
        $zp = $stats['zp'];
        $sql = "SELECT i,refer,ip,proxy,host,lang,user,tm,req from cms_surf WHERE dt='" . $date . "' AND " . $zp . " ORDER BY i ASC";
        $cmd = Yii::app()->db->createCommand($sql);
        $result = array();
        foreach ($cmd->queryAll(false) as $row) {
            //TODO: need fix, bag @array_key_exists "array_key_exists() expects parameter 2 to be array, null given"
            if (@array_key_exists($row[2], $i1_ip)) {
                $i2[$i1_ip[$row[2]]][] = array($row[7], $row[8]);
            } else {
                $i1[$row[0]] = array($row[1], $row[2], $row[3], $row[4], $row[5], $row[6]);
                $i1_ip[$row[2]] = $row[0];
                $i2[$i1_ip[$row[2]]][] = array($row[7], $row[8]);
            }
        }
        $i1 = array_reverse($i1, true);
        foreach ($i1 as $id => $row) {
            $refer = StatsHelper::Ref($row[0]);
            if (is_array($refer)) {
                list($engine, $query) = $refer;
                $refer1 = StatsHelper::checkSearchEngine($row[0], $engine, $query);
            } else {
                $refer1 = StatsHelper::checkIdna($row);
            }

            $result[] = array(
                'refer' => $refer1,
                'ip' => StatsHelper::getRowIp($row[1], $row[2]),
                'host' => StatsHelper::getRowHost($row[1], $row[2], $row[3], $row[4]),
                'user_agent' => StatsHelper::getRowUserAgent($row[6], $row[1]),
                'timelink' => StatsHelper::timeLink($i2, $id),
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

        $this->render('hits', array(
            'dataProvider' => $dataProvider
        ));
    }

    public function actionHosts($date) {
        $this->pageName = 'Hosts';
        $stats = Yii::app()->stats->initRun();
        $zp = $stats['zp'];

        $sql = "SELECT tm,refer,ip,proxy,host,lang,user,req from cms_surf WHERE dt='" . $date . "' AND " . $zp . " GROUP BY ip ORDER BY i DESC";
        $cmd = Yii::app()->db->createCommand($sql);
        $result = array();
        foreach ($cmd->queryAll(false) as $row) {
            $refer = StatsHelper::Ref($row[1]);
            if (is_array($refer)) {
                list($engine, $query) = $refer;
                $refer1 = StatsHelper::checkSearchEngine($row[1], $engine, $query);
            } else {
                $refer1 = StatsHelper::checkIdna($row);
            }
            $result[] = array(
                'time' => $row[0],
                'refer' => $refer1,
                'ip' => StatsHelper::getRowIp($row[2], $row[3]),
                'host' => StatsHelper::getRowHost($row[2], $row[3], $row[4], $row[5]),
                'user_agent' => StatsHelper::getRowUserAgent($row[6], $row[1]),
                'timelink' => Html::link($row[7], $row[7]),
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
                            'time' => array(
                                'asc' => 'time DESC',
                                'desc' => 'time ASC',
                            ),
                        ),
                    ),
                    'pagination' => array(
                        'pageSize' => 10,
                    ),
                ));

        $this->render('hosts', array(
            'dataProvider' => $dataProvider
        ));
    }

}
