<?php

/**
 * This is the model class for table "EngineLanguage".
 *
 * The followings are the available columns in table 'EngineLanguage':
 * @property integer $id
 * @property string $name Language name
 * @property string $code Url prefix
 * @property string $locale Language locale
 * @property boolean $default Is lang default
 * @property boolean $flag_name Flag image name
 */
class LanguageModel extends ActiveRecord {

    const MODULE_ID = 'core';
    private static $_languages;

    public function getForm() {
        return new CMSForm(array(
                    'id' => __CLASS__,
                    'attributes' => array(
                        'class' => 'form-horizontal'
                    ),
                    'elements' => array(
                        'name' => array(
                            'type' => 'text',
                        ),
                        'code' => array(
                            'type' => 'text',
                            'hint' => Yii::t('CoreModule.core', 'Например: en'),
                        ),
                        'locale' => array(
                            'type' => 'text',
                            'hint' => Yii::t('CoreModule.core', 'Например: en, en_us'),
                        ),
                        'flag_name' => array(
                            'type' => 'dropdownlist',
                            'items' => self::getFlagImagesList(),
                            'empty' => '---',
                        //'encode'=>false,
                        ),
                        'default' => array(
                            'type' => 'checkbox',
                        )
                    ),
                    'buttons' => array(
                        'submit' => array(
                            'type' => 'submit',
                            'class' => 'btn btn-success',
                            'label' => $this->isNewRecord ? Yii::t('app', 'CREATE', 0) : Yii::t('app', 'SAVE')
                        )
                    )
                        ), $this);
    }

    /**
     * Returns the static model of the specified AR class.
     * @return EngineLanguage the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{language}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('name, code, locale', 'required'),
            array('name, locale', 'length', 'max' => 100),
            array('flag_name', 'length', 'max' => 255),
            array('code', 'length', 'max' => 25),
            array('default', 'in', 'range' => array(0, 1)),
            array('id, name, code, locale', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('locale', $this->locale, true);
        $criteria->compare('default', $this->default);

        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function scopes() {
        return array(
            'noDefault' => array(
                'condition' => '`t`.`default`!=1'
            ),
        );
    }

    public function afterSave() {
        // Leave only one default language
        /* if ($this->default)
          {
          self::model()->updateAll(array(
          'default'=>0,
          ), 'id != '.$this->id);
          } */
        return parent::afterSave();
    }

    public function beforeDelete() {
        if ($this->default)
            return false;
        return parent::beforeDelete();
    }

    public static function getArrayLanguage() {
        if ($this->default)
            return false;
        return parent::beforeDelete();
    }

    public static function getFlagImagesList() {
        Yii::import('system.utils.CFileHelper');
        $flagsPath = 'webroot.uploads.language';

        $result = array();
        $flags = CFileHelper::findFiles(Yii::getPathOfAlias($flagsPath));

        foreach ($flags as $f) {
            $parts = explode(DIRECTORY_SEPARATOR, $f);
            $fileName = end($parts);
            $result[$fileName] = $fileName;
        }

        return $result;
    }

}