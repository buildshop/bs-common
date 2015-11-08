<?php

class translitFilter extends CValidator {

    public $translitAttribute = 'title';

    protected function validateAttribute($object, $attribute) {
        $config = Yii::app()->settings->get('core');


        if (isset($object->translateAttributes)) {
            if (in_array($this->translitAttribute, $object->translateAttributes)) {
                $p=array();
                foreach($object->translateAttributes as $param){
                    $p[$param]=$param;
                }
                $attr = $p[$this->translitAttribute];
            }
        } else {
            $attr = $this->translitAttribute;
            if (!$object->hasAttribute($attr))
                throw new CException(Yii::t('yii', 'Active record "{class}" is trying to select an invalid column "{column}". Note, the column must exist in the table or be an expression with alias.', array('{class}' => get_class($object), '{column}' => $attr)));
        }
        if ($config['translate_object_url']) {
            Yii::import('mod.core.components.yandexTranslate');
            $t = new yandexTranslate;
            $tr = $t->translitUrl(array('ru', 'en'), $object->getAttribute($attr));
            //die($tr);
        } else {
            $tr = CMS::translit($object->getAttribute($attribute));
        }

        if ($object->isNewRecord) {
            $model = $object::model()->find(array('condition' => '`t`.`' . $attribute . '`=:alias', 'params' => array(':alias' => $tr)));
        } else {
            $model = $object::model()->find(array('condition' => '`t`.`' . $attribute . '`=:alias AND `t`.`id`!=' . $object->id, 'params' => array(':alias' => $tr)));
        }

        if (isset($model)) {
            $this->addError($object, $attribute, Yii::t('app', 'ERROR_DUPLICATE_URL', array('{url}' => $tr)));
        } else {
            $object->$attribute = $tr;
        }
    }

}