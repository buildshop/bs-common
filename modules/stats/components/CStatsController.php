<?php

class CStatsController extends AdminController {

    public $topButtons = false;
    public $_zp; //zp
    public $_cse_m = ''; //$cse_m
    public $_cot_m = ''; //cot_m
    public $_site; //site
    public $_zfx; //$zfx
    public $rbd;
    public $robo;

    public function init() {

     //   list($s_date, $f_date) = str_replace("+", "", array($this->sdate, $this->fdate));
        
       if (!preg_match("/^[0-9]{4}([0-9]{2})([0-9]{2})$/",$this->fdate) && !preg_match("/^[0-9]{4}([0-9]{2})([0-9]{2})$/",$this->sdate)){
           throw new CException('Не верный формат даты!');

    }
        //$this->sdate = StatsHelper::dtconv2(trim($s_date));
        //$this->fdate = StatsHelper::dtconv2(trim($f_date));
       // $this->setSdate(StatsHelper::dtconv2(trim($s_date)));
        //$this->setFdate(StatsHelper::dtconv2(trim($f_date)));
        /*
          if ($robots = file(Yii::getPathOfAlias('webroot.stats') . "/robots.dat")) {
          $i = 0;
          for ($i = 0; $i < count($robots); $i++)
          $robots[$i] = iconv("CP1251", "UTF-8", $robots[$i]);
          foreach ($robots as $val) {
          list($rb1, $rb2) = explode("|", $val);
          $rb2 = trim($rb2);
          $this->rbd[$i++] = rtrim($rb1);
          if (!empty($rb2))
          $rbdn[$rb2][] = rtrim($rb1);
          $robo[] = $rb2;
          }
          }
          if ($hosts = file(Yii::getPathOfAlias('webroot.stats') . "/hosts.dat")) {
          $i = 0;
          for ($i = 0; $i < count($hosts); $i++)
          $hosts[$i] = iconv("CP1251", "UTF-8", $hosts[$i]);
          foreach ($hosts as $val) {
          list($hb1, $hb2) = explode("|", $val);
          $hb2 = trim($hb2);
          $hbd[$i++] = rtrim($hb1);
          if (!empty($hb2))
          $hbdn[$hb2][] = rtrim($hb1);
          $robo[] = $hb2;
          }
          }
          $this->robo = array_unique($robo);

          foreach ($this->rbd as $val)
          $this->_zp .= " LOWER(user) NOT LIKE '%" . mb_strtolower($val) . "%' AND";
          if (filesize(Yii::getPathOfAlias('webroot.stats') . "/hosts.dat"))
          foreach ($hbd as $val)
          $this->_zp .= " LOWER(host) NOT LIKE '%" . mb_strtolower($val) . "%' AND";
          $this->_zp .= " LOWER(user) NOT LIKE '' AND";
          if (file_exists(Yii::getPathOfAlias('webroot.stats') . "/skip.dat")) {
          if ($skip = file(Yii::getPathOfAlias('webroot.stats') . "/skip.dat")) {
          foreach ($skip as $vl) {
          list($s1, $s2) = explode("|", $vl);
          $zp2 .= " $s1 NOT LIKE '%" . rtrim($s2) . "%' AND";
          }
          }
          }

          $this->_zp .= $zp2;
          $this->_zp = substr($this->_zp, 0, -4);

          if ($se_m = file(Yii::getPathOfAlias('webroot.stats') . "/se.dat")) {
          for ($i = 0; $i < count($se_m); $i++)
          $se_m[$i] = iconv("CP1251", "UTF-8", $se_m[$i]);
          foreach ($se_m as $vl) {
          list($s1, $s2, $s3) = explode("|", $vl);
          $se_n[$s1] = rtrim($s3);
          $se_nn[$s1] = $s2;
          }
          }
          if (file_exists(Yii::getPathOfAlias('webroot.stats') . "/fix.dat")) {
          if ($fx_m = file(Yii::getPathOfAlias('webroot.stats') . "/fix.dat")) {
          $this->_zfx = "";
          $pf = "";
          for ($i = 0; $i < count($fx_m); $i++)
          $fx_m[$i] = iconv("CP1251", "UTF-8", $fx_m[$i]);
          foreach ($fx_m as $vl) {
          list($s1, $s2, $s3) = explode("|", $vl);
          $this->_zfx .= $pf . "LOWER(" . $s1 . ") LIKE '%" . mb_strtolower($s2) . "%'";
          $pf = " OR ";
          $s3 = rtrim($s3);
          if (!empty($s3))
          $fxn[$s3][] = $s1 . "|" . $s2;
          $fxo[] = $s3;
          }
          }
          }

          foreach ($se_nn as $val) {
          $this->_cse_m .= " OR LOWER(refer) LIKE '%$val%'";
          $this->_cot_m .= " AND LOWER(refer) NOT LIKE '%$val%'";
          }

          /* $pages = $_GET['pages'];
          if ($pages == "0" and file_exists("pages.dat"))
          unlink("pages.dat");
          if ($pages == "1") {
          $fp = fopen("pages.dat", "w");
          fwrite($fp, "1");
          fclose($fp);
          }
          if (file_exists("pages.dat"))
          $pages = 1; */

        $this->_site = str_replace("www.", "", $_SERVER["HTTP_HOST"]);
        parent::init();
    }

    /* public function c_other($dt) {
      $sql = "SELECT COUNT(refer) FROM cms_surf WHERE dt='" . $dt . "' AND refer <> '' AND LOWER(refer) NOT REGEXP '^(ftp|http|https):\/\/(www.)*" . $this->_site . "' AND (LOWER(refer) NOT LIKE '%yand%' AND LOWER(refer) NOT LIKE '%google.%' AND LOWER(refer) NOT LIKE '%go.mail.ru%' AND LOWER(refer) NOT LIKE '%rambler.%' AND LOWER(refer) NOT LIKE '%search.yahoo%' AND LOWER(refer) NOT LIKE '%search.msn%' AND LOWER(refer) NOT LIKE '%bing%' AND LOWER(refer) NOT LIKE '%search.live.com%' AND LOWER(refer) NOT LIKE '%?q=%' AND LOWER(refer) NOT LIKE '%&q=%' AND LOWER(refer) NOT LIKE '%query=%'" . $this->_cot_m . ") AND " . $this->_zp . "";
      $command = Yii::app()->db->createCommand($sql);
      $res = $command->queryRow(false);
      return $res[0];
      }

      public function c_fix($dt) {
      $sql = "SELECT COUNT(i) FROM cms_surf WHERE (" . $this->_zfx . ") AND dt='" . $dt . "' AND " . $this->_zp . "";
      $command = Yii::app()->db->createCommand($sql);
      $res = $command->queryRow(false);
      return $res[0];
      }

      public function c_se($dt) {
      $sql = "SELECT COUNT(refer) FROM cms_surf WHERE dt='" . $dt . "' AND (LOWER(refer) LIKE '%yand%' OR LOWER(refer) LIKE '%google.%' OR LOWER(refer) LIKE '%go.mail.ru%' OR LOWER(refer) LIKE '%rambler.%' OR LOWER(refer) LIKE '%search.yahoo%' OR LOWER(refer) LIKE '%search.msn%' OR LOWER(refer) LIKE '%bing%' OR LOWER(refer) LIKE '%search.live.com%'" . $this->_cse_m . ") AND LOWER(refer) NOT LIKE '%@%' AND " . $this->_zp . "";
      $command = Yii::app()->db->createCommand($sql);
      $res = $command->queryRow(false);
      return $res[0];
      }

      public function c_uniqs_hits($dt) {
      $this->
      $sql = "SELECT COUNT(DISTINCT ip),COUNT(i) FROM cms_surf WHERE dt='" . $dt . "' AND " . $this->_zp . "";
      $command = Yii::app()->db->createCommand($sql);
      return $command->queryRow(false);
      } */

    public function Ref($ref) {

        if (($ref != "") and !(stristr($ref, "://" . $this->_site) and stripos($ref, "://" . $this->_site, 6) == 0) and !(stristr($ref, "://www." . $this->_site) and stripos($ref, "://www." . $this->_site, 6) == 0)) {

            $reff = str_replace("www.", "", $ref);
            if (!stristr($ref, "://")) {
                $reff = "://" . $reff;
                $ref = "://" . $ref;
            }
            if (stristr($reff, "://yandex") or stristr($reff, "://search.yaca.yandex") or stristr($reff, "://images.yandex"))
                return se_yandex($ref); else
            if (stristr($reff, "://google"))
                return se_google($ref); else
            if (stristr($reff, "://rambler") or stristr($reff, "://nova.rambler") or stristr($reff, "://search.rambler") or stristr($reff, "://ie4.rambler") or stristr($reff, "://ie5.rambler"))
                return se_rambler($ref); else
            if (stristr($reff, "://go.mail.ru") and stristr($reff, "words="))
                return se_mail1($ref); else
            if (stristr($reff, "://go.mail.ru") or stristr($reff, "://wap.go.mail.ru"))
                return se_mail2($ref); else
            if (stristr($reff, "://search.msn") or stristr($reff, "://search.live.com") or stristr($reff, "://ie.search.msn") or stristr($reff, "://bing"))
                return se_msn($ref); else
            if (stristr($reff, "://search.yahoo"))
                return se_yahoo($ref); else
            if (se_sp($ref) <> -1)
                return se_sp($ref); else
            if (stristr($ref, "?q=") or stristr($ref, "&q="))
                return se_other($ref, "q="); else
            if (stristr($ref, "query="))
                return se_other($ref, "query=");
            else
                return $ref;
        } else
            return $ref;
    }

    public function is_robot($check, $check2) {

        if (empty($check))
            return TRUE;
        if (isset($this->rbd))
            foreach ($this->rbd as $val)
                if (stristr($check, $val))
                    return TRUE;
        if (isset($hbd))
            foreach ($hbd as $val)
                if (stristr($check2, $val))
                    return TRUE;
        return FALSE;
    }

    public function timefilter() {
        //  global $s_date, $f_date, $u;


        $sql = "SELECT DISTINCT dt FROM cms_surf ORDER BY 1 DESC";
        $command = Yii::app()->db->createCommand($sql);



        //$res = mysql_query("SELECT DISTINCT dt FROM cms_surf ORDER BY 1 DESC");
        if ($_GET['dy'])
            switch ($_GET['dy']) {
                case 1:
                    $cmd = $command->queryRow();
                    $s_date = $cmd['dt'];
                    $f_date = $s_date;
                    break;
                case 2:
                    $cmd = $command->queryRow();
                    $s_date = $cmd['dt'];
                    $f_date = $s_date;
                    break;
                case 3:
                    $f_date = mysql_result($res, 0);
                    $s_date = substr($f_date, 0, 6) . "01";
                    break;
                case 4:
                    $cmd = $command->queryRow();
                    $f_date = $cmd['dt'];
                    //die($f_date);
                    //$f_date = mysql_result($res, 0);
                    $f_d = substr($f_date, 0, 4) . substr($f_date, 4, 2);
                    //while ($row = mysql_fetch_row($res)) {
                    foreach ($command->queryAll() as $row) {
                        $s_d = substr($row['dt'], 0, 4) . substr($row['dt'], 4, 2);
                        if ($f_d <> $s_d) {
                            $f_date = $row['dt'];
                            break;
                        }
                    }
                    $s_date = $s_d . "01";
                    break;
                case 5:
                    $s_date = mysql_result($res, mysql_num_rows($res) - 1);
                    $f_date = mysql_result($res, 0);
                    break;
            }
        if (empty($s_date)) {
            $cmd = $command->queryRow();
            $s_date = $cmd['dt'];
        }
        if (empty($f_date)) {
            $cmd = $command->queryRow();
            $f_date = $cmd['dt'];
        }

        $this->renderPartial('stats.views.admin.default._filters', array());
    }

    public function setSdate($date) {
        $this->sdate = $date;
    }

    public function setFdate($date) {
        $this->fdate = $date;
    }

    public function getSdate() {
        $sdate = Yii::app()->request->getParam('s_date');
        return ($sdate) ? $sdate : date('Ymd');
    }

    public function getFdate() {
        $fdate = Yii::app()->request->getParam('f_date');
        return ($fdate) ? $fdate : date('Ymd');
    }

    public function getBwr() {
        return Yii::app()->request->getParam('bwr');
    }

    public function getSort() {
        return Yii::app()->request->getParam('sort');
    }

    public function getPos() {
        return Yii::app()->request->getParam('pos');
    }

}
