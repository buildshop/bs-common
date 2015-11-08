<?php

class CManagerFinderWidgets extends CComponent {

    protected $widgetList = array();
    protected $data = array();
    public $cache_key = 'cache_widgets';

    const tableName = '{{widgets}}';
    protected static $denieMods = array('core','admin');

    public function init() {
        $cacheTime = Yii::app()->settings->get('core', 'cache_time');
        $this->data = Yii::app()->cache->get($this->cache_key);
        if (!$this->data) {
            $data = array();
            foreach ($this->getWidgetsSystem() as $key => $w) {
                $data['system'][$key] = $w;
            }
            foreach ($this->getBlocksModules() as $key => $w2) {
                $data['module'][$key] = $w2;
            }

            $this->data = $data;

            Yii::app()->cache->set($this->cache_key, $this->data, $cacheTime);
        }
    }

    private function getBlocksModules() {
        $result = array();
        foreach (Yii::app()->getModules() as $module) {
            $expName = explode('.', $module['class']); //разбиваем строку на массив
            $modname = $expName[0]; // получаем название модуля
            if (!in_array($modname, self::$denieMods)) { // запрещаем выводить приватные модулю
                $path = Yii::getPathOfAlias("mod.{$modname}.blocks");
                if (is_dir($path)) {
                    $file = CFileHelper::findFiles($path, array(
                                'level' => 1,
                                'fileTypes' => array('php'),
                                'absolutePaths' => false
                                    )
                    );

                    Yii::import("mod.{$modname}." . ucfirst($modname) . "Module");
                    foreach ($file as $f) {
                        $rep = str_replace('.php', '', $f);
                        $expFile = explode(DS, $rep);
                        $dir = $expFile[0];
                        $name = $expFile[1];
                        $alias = "mod.{$modname}.blocks.{$dir}.{$name}";
                        Yii::import($alias);
                        $class = new $name;
                        if ($class instanceof BlockWidget) {
                            $result[$alias] = $class->getTitle();
                        } else {
                            $result[$alias] = $name;
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Системные виджеты
     * @return array
     */
    private function getWidgetsSystem() {
        $result = array();
        $extFiles = CFileHelper::findFiles(Yii::getPathOfAlias('ext.blocks'), array(
                    'level' => 1,
                    'fileTypes' => array('php'),
                    'absolutePaths' => false
                        )
        );
        foreach ($extFiles as $file) {
            $rep = str_replace('.php', '', $file);
            $expFile = explode(DS, $rep);
            $dir = $expFile[0];
            $name = $expFile[1];
            $alias = "ext.blocks.{$dir}.{$name}";
            Yii::import($alias);
            $class = new $name;
            if ($class instanceof BlockWidget) {
                $data = array(
                    'name' => $class->getTitle(),
                    'alias_wgt' => $alias,
                );
                $result[$alias] = $class->getTitle();
                $this->set('system', $data);
            } else {
                $data = array(
                    'name' => $name,
                    'alias_wgt' => $alias,
                );
                $result[$alias] = $name;
                $this->set('system', $data);
            }
        }
        return $result;
    }

    public function getData() {
        return $this->data;
    }

    public function getDropDownList() {
        $data = array();
        $data['dropdownlist'] = array(
            'Системные' => $this->getWidgetsSystem(),
            'Модули' => $this->data['module'],
        );
        return $data;
    }

    public function get($type, $alias = null, $default = null) {

        return Yii::app()->db->createCommand()
                        ->from('{{widgets}}')
                        ->where('type=:t AND alias_wgt=:wgt', array(':t' => $type, ':wgt' => $alias))
                        ->queryRow();
    }

    public function set($type, $data = array()) {

        if (!empty($data)) {
            // foreach ($data as $key => $value) {

            if ($this->get($type, $data['alias_wgt'])) {
                Yii::app()->db->createCommand()->update('{{widgets}}', array(
                    'name' => $data['name']), 'type=:type AND alias_wgt=:alias_wgt', array(':type' => $type, ':alias_wgt' => $data['alias_wgt']));
            } else {
                Yii::app()->db->createCommand()->insert('{{widgets}}', CMap::mergeArray(array('type' => $type), $data));
            }
            // }

            if (!isset($this->data[$type]))
                $this->data[$type] = array();
            $this->data[$type] = CMap::mergeArray($this->data[$type], $data);

            // Update cache
            Yii::app()->cache->set($this->cache_key, $this->data);
        }
    }

    public function clear() {
        Yii::app()->cache->delete($this->cache_key);
    }

}
