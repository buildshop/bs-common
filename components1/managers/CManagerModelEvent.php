<?php

/**
 * Implements `global` application events.
 * Helps to attach events to any BaseModel class.
 *
 * All events are stored in protected/all_events.php.
 * This file rebuilds on add/remove modules from admin panel or just simply delete all_events.php to rebuild.
 *
 * Usage:
 * 1. Create events class in module config directory and name it like {module}ModuleEvents.php
 * 2. Events class must have method named `getEvents` that describes events.
 *
 *        public function getEvents()
 *        {
 *            return array(
 *                  array('Page', 'onAfterSave', array($this, 'pageAfterSave')),
 *            );
 *        }
 */
class CManagerModelEvent {

    /**
     * @var boolean is class initialized
     */
    public static $initialized = false;

    /**
     * Stores all events as array.
     * array(
     *      className=>array(eventName, handler)
     * )
     * @var array
     */
    public static $events = array();

    /**
     * Initialize class.
     * Search all events in modules and cache them.
     * @static
     */
    public static function init() {
        self::$initialized = true;
        self::loadEventsFile();

        foreach (Yii::app()->getModules() as $mid=>$module) {
            $className = ucfirst($mid) . 'ModuleEvents';

            if (class_exists($className, false))
                self::loadEventsClass($className);
        }
    }

    
    
    
    
    
    public static function loadEventsFile() {
        $path = self::allEventsFilePath();

        if (YII_DEBUG)
            self::buildEventsFile();

        if (file_exists($path))
            require $path;
        else {
            self::buildEventsFile();
            require $path;
        }
    }
    public static function buildEventsFile() {
        $contents = '<?php ';

        foreach (Yii::app()->getModules() as $mid=>$module) {
            $className = ucfirst($mid) . 'ModuleEvents';
            $path = Yii::getPathOfAlias('mod.' . $mid . '.config.' . $className) . '.php';

            if (file_exists($path)) {
                $code = file_get_contents($path);
                $contents .= str_replace('<?php', '', $code);
            }
        }

        file_put_contents(self::allEventsFilePath(), $contents);
    }
    
        public static function allEventsFilePath() {
        return Yii::getPathOfAlias('application.runtime.all_events') . '.php';
    }
    
    
    
    
    
    
    
    
    
    
    /**
     * Attach events to object
     * @static
     * @param CActiveRecord $object
     */
    public static function attachEvents(CActiveRecord $object) {
        if (self::$initialized === false)
            self::init();

        if (isset(self::$events[get_class($object)])) {
            $events = self::$events[get_class($object)];
            foreach ($events as $e)
                $object->attachEventHandler($e[0], $e[1]);
        }
    }

    /**
     * Load and process events class
     * @static
     * @param $className
     */
    public static function loadEventsClass($className) {
        $eventsClass = new $className;
        $events = $eventsClass->getEvents();

        if (empty($events))
            return;

        foreach ($events as $event)
            self::cacheEvent($event[0], $event[1], $event[2]);
    }

    /**
     * Cache all found event for current request.
     * @static
     * @param string $class model class name
     * @param string $event event name
     * @param array $handler event handler array(object, method)
     */
    public static function cacheEvent($class, $event, $handler) {
        self::$events[$class][] = array($event, $handler);
    }

}
