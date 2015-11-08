<?php

class CManagerDatabase extends CComponent {

    public $backupPath = 'application.backup';
    private $prefix;

    public function init() {
        $this->prefix = Yii::app()->db->tablePrefix;
    }

    public function export($withData = true, $dropTable = false, $savePath = true) {
        $pdo = Yii::app()->db->pdoInstance;
        $mysql = '';
        $statments = $pdo->query("show tables");
        foreach ($statments as $value) {
            $tableName = $value[0];
            if ($dropTable === true) {
                $tableName2 = str_replace($this->prefix, '{prefix}', $tableName);
                $mysql.="DROP TABLE IF EXISTS `$tableName2`;\n";
            }
            $tableQuery = $pdo->query("show create table `$tableName`");
            $createSql = $tableQuery->fetch();
            $createSql['Create Table'] = str_replace($this->prefix, '{prefix}', $createSql['Create Table']);
            $mysql.=$createSql['Create Table'] . ";\r\n\r\n";
            if ($withData != 0) {
                $itemsQuery = $pdo->query("select * from `$tableName`");
                $values = "";
                $items = "";
                while ($itemQuery = $itemsQuery->fetch(PDO::FETCH_ASSOC)) {
                    $itemNames = array_keys($itemQuery);
                    $itemNames = array_map("addslashes", $itemNames);
                    $items = join('`,`', $itemNames);
                    $itemValues = array_values($itemQuery);
                    $itemValues = array_map("addslashes", $itemValues);
                    $valueString = join("','", $itemValues);
                    $valueString = "('" . $valueString . "'),";
                    $values.="\n" . $valueString;
                }
                if ($values != "") {
                    $tableName = str_replace($this->prefix, '{prefix}', $tableName);
                    $insertSql = "INSERT INTO `$tableName` (`$items`) VALUES" . rtrim($values, ",") . ";\n\r";
                    $mysql.=$insertSql;
                }
            }
            $mysql.="/*-----------------------BACKUP CORNER CMS------------------------------*/\n\r";
        }
        ob_start();
        echo $mysql;
        $content = ob_get_contents();
        ob_end_clean();
        $content = gzencode($content, 9);
        $saveName = date('Y-m-d_H-m-s') . ".sql.gz";
        if ($savePath === false) {
            $request = Yii::app()->getRequest();
            $request->sendFile($saveName, $content);
        } else {
            if(file_put_contents(Yii::getPathOfAlias($this->backupPath) . DS . $saveName, $content)){
                  return true;
            }else{
                  return false;
            }
          
        }
    }

    /**
     * import sql from a *.sql file
     *
     * @param string $file: with the path and the file name
     * @return mixed
     */
    public function import($mod) {
        $file = Yii::getPathOfAlias('mod.' . $mod . '.sql') . DS . 'dump.sql';
        $pdo = Yii::app()->db->pdoInstance;
        try {
            if (file_exists($file)) {
                $sqlStream = file_get_contents($file);
                $sqlStream = rtrim($sqlStream);
                $newStream = preg_replace_callback("/\((.*)\)/", create_function('$matches', 'return str_replace(";"," $$$ ",$matches[0]);'), $sqlStream);
                $sqlArray = explode(";", $newStream);
                foreach ($sqlArray as $value) {
                    if (!empty($value)) {
                        $value = str_replace("{prefix}", $this->prefix, $value);
                        $sql = str_replace(" $$$ ", ";", $value) . ";";
                        $pdo->exec($sql);
                    }
                }
                 Yii::log('Success import db '.$mod, 'info', 'install');
                return true;
            }
        } catch (PDOException $e) {
            Yii::log('Error install DB', 'info', 'install');
            echo $e->getMessage();
            exit;
        }
    }

}
