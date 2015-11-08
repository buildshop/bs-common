<?php

class CategoriesModel extends ActiveRecord {

    protected $_mod = false;
    const MODULE_ID = 'core';
    public $_url;

    public function getForm() {
        Yii::app()->controller->widget('ext.tinymce.TinymceWidget');
        return new CMSForm(array('id' => __CLASS__,
                    'showErrorSummary' => true,
                    'attributes' => array(
                        'class' => 'form-horizontal'
                    ),
                    'elements' => array(
                        'name' => array('type' => 'text', 'id' => 'title'),
                        'seo_alias' => array(
                            'type' => 'text',
                            'id' => 'alias',
                            'visible' => !Yii::app()->settings->get('core', 'translate_object_url')
                        ),
                        'parent_id' => array(
                            'type' => 'dropdownlist',
                            'items' => Html::listData(CategoriesModel::model()->parent()->findAll(), 'id', 'name'),
                            'empty' => '&mdash; Выбать &mdash;'
                        ),
                        'module' => array(
                            'type' => 'dropdownlist',
                            'items' => CMS::getModules(),
                        ),
                        'text' => array('type' => 'textarea', 'class' => 'editor'),
                        'date_update' => array('type' => 'text', 'value' => date('Y-m-d H:i:s')),
                    ),
                    'buttons' => array(
                        'submit' => array(
                            'type' => 'submit',
                            'class' => 'btn btn-success',
                            'label' => Yii::t('app', 'SAVE')
                        )
                    )
                        ), $this);
    }


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{categories}}';
    }

    public function rules() {
        return array(
            array('module, name', 'required'),
            array('seo_alias', 'translitFilter', 'translitAttribute' => 'name'),
            array('parent_id', 'numerical', 'integerOnly' => true),
            //  array('siteName, siteCloseAccess, adminPagenum, defaultAdminModule, censor_replace', 'length', 'max' => 250),
            array('module', 'safe', 'on' => 'search'),
                //  array('date_update', 'type', 'type' => 'date', 'dateFormat' => 'yyyy-MM-dd'),
        );
    }

    public function scopes() {
        return array(
            'module' => array(
                'condition' => 'module="' . $this->mod . '"'
            ),
            'parent' => array(
                'condition' => 'parent_id="0"'
            )
        );
    }

    public function relations() {
        return array(
            //'news' => array(self::HAS_MANY, 'News', 'catid'),
            //'newsCount' => array(self::STAT, 'News', 'id'),
            'childs' => array(self::HAS_MANY, 'CategoriesModel', 'parent_id', 'order' => '`childs`.`id` ASC'),
        );
    }

    public function getMod() {
        return Yii::app()->controller->module->id;
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('module', $this->module, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('seo_alias', $this->seo_alias, true);
        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function getChilds() {
        $module = array();
        foreach ($this->childs as $child) {
            //if(isset($this->$mod->name)){
            $module[$child->id] = $child->name;
            // }
        }
        return $module;
    }

}