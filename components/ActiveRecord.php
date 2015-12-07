<?php

/**
 * ActiveRecord class
 * 
 */
Yii::import('app.traits.ModelTranslate');

class ActiveRecord extends CActiveRecord {

    use ModelTranslate;

    const MODULE_ID = null;

    public $timeline = false;
    protected $_attrLabels = array();
    public $maxOrdern;

    const route_update = 'update';
    const route_delete = 'delete';
    const route_switch = 'switch';
    const route = null;

    /**
     * Special for widget ext.admin.frontControl
     * @return string
     */
    public function getDeleteUrl() {
        if (static::route) {
            return Yii::app()->createUrl(static::route . '/' . static::route_delete, array(
                        'model' => get_class($this),
                        'id' => $this->id
            ));
        } else {
            throw new Exception(Yii::t('app', 'Unknown const {param} in model {model}', array(
                '{param}' => 'route_delete',
                '{model}' => get_class($this)
            )));
        }
    }

    /**
     * Special for widget ext.admin.frontControl
     * @return string
     */
    public function getUpdateUrl() {
        if (static::route) {
            return Yii::app()->createUrl(static::route . '/' . static::route_update, array(
                        'id' => $this->id
            ));
        } else {
            throw new Exception(Yii::t('app', 'Unknown const {param} in model {model}', array(
                '{param}' => 'route_update',
                '{model}' => get_class($this)
            )));
        }
    }

    /**
     * Special for widget ext.admin.frontControl
     * @return string
     */
    public function getSwitchUrl() {
        if (static::route) {
            return Yii::app()->createUrl(static::route . '/' . static::route_switch, array(
                        'model' => get_class($this),
                        'switch' => 0,
                        'id' => $this->id
            ));
        } else {
            throw new Exception(Yii::t('app', 'Unknown const {param} in model {model}', array(
                '{param}' => 'route_switch',
                '{model}' => get_class($this)
            )));
        }
    }

    public function uploadFile($attr, $dir, $old_image = null) {
        $file = CUploadedFile::getInstance($this, $attr);
        $path = Yii::getPathOfAlias($dir) . DS;

        if (isset($file)) {
            
            if ($old_image)
                unlink($path . $old_image);
            
            $newname = CMS::gen(10) . "." . $file->extensionName;

            $img = Yii::app()->img;
            $img->load($file->tempName);
            $img->save($path . $newname);
            $this->$attr = (string) $newname;
        } else {
            $this->$attr = (string) $old_image;
        }
    }

    public function getColumnSearch($array = array()) {

        $col = $this->gridColumns;

        $result = array();
        if (isset($col['DEFAULT_COLUMNS'])) {
            foreach ($col['DEFAULT_COLUMNS'] as $t) {
                $result[] = $t;
            }
        }
        foreach ($array as $key => $s) {
            $result[] = $col[$key];
        }

        if (isset($col['DEFAULT_CONTROL']))
            $result[] = $col['DEFAULT_CONTROL'];

        return $result;
    }

    public function init() {
        Yii::import('app.managers.CManagerModelEvent');
        CManagerModelEvent::attachEvents($this);
    }

    public function save($mSuccess = true, $mError = true, $runValidation = true, $attributes = null) {
        if (parent::save($runValidation, $attributes)) {
            if ($mSuccess) {
                $message = Yii::t('app', ($this->isNewRecord) ? 'SUCCESS_CREATE' : 'SUCCESS_UPDATE');
                if (Yii::app()->controller->edit_mode && Yii::app()->request->isAjaxRequest) {
                    echo CJSON::encode(array('message' => $message, 'valid' => true));
                    Yii::app()->end();
                } else {
                    Yii::app()->controller->setFlashMessage($message);
                }
            }
            //if($this->timeline){
            //    Yii::app()->timeline->set('UPDATE_RECORD',array(
            //         '{model}'=>  get_class($this)
            //         ));
            //  }
            //print_r($this);
            return true;
        } else {
            if ($mError) {
                Yii::app()->controller->setFlashMessage(Yii::t('app', ($this->isNewRecord) ? 'ERROR_CREATE' : 'ERROR_UPDATE'));
            }
            return false;
        }
    }

    public function validate($attributes = null, $clearErrors = true) {
        if (parent::validate($attributes, $clearErrors)) {
            return true;
        } else {
            $message = Yii::t('app', 'ERROR_VALIDATE');
            if (Yii::app()->controller->edit_mode && Yii::app()->request->isAjaxRequest) {
                echo CJSON::encode(array(
                    'message' => $message,
                    'valid' => false,
                    'errors' => $this->getErrors()
                ));
                Yii::app()->end();
            } else {
                Yii::app()->controller->setFlashMessage($message);
            }
            return false;
        }
    }

    public function attributeLabels() {
        $lang = Yii::app()->languageManager->active->code;
        $model = get_class($this);
        $filePath = Yii::getPathOfAlias('mod.' . static::MODULE_ID . '.messages.' . $lang) . DS . $model . '.php';
        foreach ($this->behaviors() as $key => $b) {
            if (isset($b['translateAttributes'])) {
                foreach ($b['translateAttributes'] as $attr) {
                    $this->_attrLabels[$attr] = self::t(strtoupper($attr));
                }
            }
        }
        foreach ($this->attributes as $attr => $val) {
            $this->_attrLabels[$attr] = self::t(strtoupper($attr));
        }
        if (!file_exists($filePath)) {
            Yii::app()->user->setFlash('warning', 'Модель "' . $model . '", не может найти файл переводов: <b>' . $filePath . '</b> ');
        }
        return $this->_attrLabels;
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            //create
            if ($this->isNewRecord) {
                if (isset($this->tableSchema->columns['ip_create'])) {
                    //Текущий IP адресс, автора добавление
                    $this->ip_create = Yii::app()->request->userHostAddress;
                }
                if (isset($this->tableSchema->columns['user_id'])) {
                    $this->user_id = (Yii::app()->user->isGuest) ? 0 : Yii::app()->user->id;
                }
                if (isset($this->tableSchema->columns['user_agent'])) {
                    $this->user_agent = Yii::app()->request->userAgent;
                }
                if (isset($this->tableSchema->columns['date_create'])) {
                    $this->date_create = date('Y-m-d H:i:s');
                }
                if (isset($this->tableSchema->columns['ordern'])) {
                    if (!isset($this->ordern)) {
                        $row = $this->model()->find(array('select' => 'max(ordern) AS maxOrdern'));
                        $this->ordern = $row['maxOrdern'] + 1;
                    }
                }
                //update
            } else {
                if (isset($this->tableSchema->columns['date_update'])) {
                    $this->date_update = date('Y-m-d H:i:s');
                }
            }
            return true;
        } else {
            return false;
        }
    }

}
