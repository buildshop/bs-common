<?php

class SMSSystemManager extends CComponent {

    public function getSystemClass($alias) {
        $path = dirname(Yii::getPathOfAlias('mod.sms.components.services.'.$alias));
           // die($alias);
        $fpath = ($path . DS . 'form');

        if (file_exists($fpath)) {
            $fileForm = self::fileHelper($fpath, 1);

            Yii::import('mod.sms.components.services.'.$alias); //import block class
            $fileBlock = self::fileHelper($path);

            $block = new $fileBlock;

            if (isset($block->alias)) {
                Yii::import($block->alias . '.form.' . $fileForm); // import form class
            } else {
                die('Ошибка, в блоке не определен $alias');
            }

            return new $fileForm;
        } else {
            if (Yii::app()->request->isAjaxRequest)
                Yii::app()->tpl->alert('warning', 'система не обнаружела настройки виджета', false);
            return false;
        }
    }

    /**
     * Находим файл по $path & $level
     * 
     * @param string $path
     * @param int $level Default value "0"
     * @return string Название класса
     */
    private static function fileHelper($path, $level = 0) {
        $file = CFileHelper::findFiles($path, array(
                    'level' => $level,
                    'fileTypes' => array('php'),
                    'absolutePaths' => false
                        )
        );
        return self::replcePHP($file[0]);
    }

    /**
     * Убираем со строки ".php"
     * 
     * @param string $name
     * @return string
     */
    private static function replcePHP($name) {
        return str_replace('.php', '', $name);
    }

}
