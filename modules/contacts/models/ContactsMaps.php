<?php

/**
 * @package modules.contacts.models
 */
class ContactsMaps extends ActiveRecord {

    const MODULE_ID = 'contacts';

    public function getForm() {
        Yii::import('ext.bootstrap.selectinput.SelectInput');
        return new TabForm(array(
            'attributes' => array(
                'id' => __CLASS__,
                'class' => 'form-horizontal',
            ),
            'showErrorSummary' => true,
            'elements' => array(
                'general' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_GENERAL'),
                    'elements' => array(
                        'name' => array('type' => 'text'),
                        'zoom' => array('type' => 'SelectInput', 'data' => self::getZoomList()),
                        'center' => array('type' => 'text', 'hint' => self::t('HINT_CENTER')),
                        'width' => array('type' => 'text'),
                        'height' => array('type' => 'text'),
                        'drag' => array('type' => 'checkbox'),
                        'scrollZoom' => array('type' => 'checkbox'),
                        'auto_show_routers' => array('type' => 'checkbox'),
                        'type' => array('type' => 'SelectInput', 'data' => self::getTypeMapList()),
                    ),
                ),
                'panels' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_PANELS'),
                    'elements' => array(
                        // 'searchControl' => array('type' => 'checkbox'),
                        // 'mapTools' => array('type' => 'checkbox'),
                        // 'mapTools_top' => array('type' => 'text'),
                        //'mapTools_left' => array('type' => 'text'),
                        'zoomControl' => array('type' => 'checkbox'),
                        //  '<div class="alert alert-info">'.self::t('ZOOMCONTROL_ALERT').'</div>',
                        'zoomControl_top' => array('type' => 'text'),
                        'zoomControl_bottom' => array('type' => 'text'),
                        'zoomControl_left' => array('type' => 'text'),
                        'zoomControl_right' => array('type' => 'text'),
                    ),
                ),
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

    public static function getZoomList() {
        $result = array();
        foreach (range(1, 19) as $num) {
            $result[$num] = $num;
        }
        return $result;
    }

    public static function getTypeMapList() {
        return array(
            'yandex#map' => self::t('TYPE_MAP'),
            'yandex#satellite' => self::t('TYPE_SATELLITE'),
            'yandex#hybrid' => self::t('TYPE_HYBRID'),
        );
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{contacts_maps}}';
    }

    public function relations() {
        return array(
            'markers' => array(self::HAS_MANY, 'ContactsMarkers', 'map_id'),
            'routers' => array(self::HAS_MANY, 'ContactsRouter', 'map_id'),
        );
    }

    public function rules() {
        return array(
            array('name, width, height, zoom, center, type, drag', 'required'),
            array('mapTools_top, mapTools_left, zoom, zoomControl_top, zoomControl_bottom, zoomControl_left, zoomControl_right', 'numerical', 'integerOnly' => true),
            array('searchControl, mapTools, zoomControl, drag, auto_show_routers, scrollZoom', 'boolean'),
            array('name', 'length', 'max' => 255),
            array('name', 'type', 'type' => 'string'),
            array('center', 'match', 'not' => false, 'pattern' => '/\d+(\.\d+)?,\d+(\.\d+)?/'),
        );
    }

    public function beforeSave() {
        $coords = explode(',', $this->center);
        $this->center = new CDbExpression("GeomFromText(:point)", array(':point' => 'POINT(' . $coords[0] . ' ' . $coords[1] . ')'));

        return parent::beforeSave();
    }

    protected function beforeFind() {
        parent::beforeFind();
        $criteria = new CDbCriteria;
        $criteria->select = "*, CONCAT(X(center),',',Y(center)) AS center"; // YOU MUST TYPE ALL OF YOUR TABLE'S COLUMN NAMES HERE
        $this->dbCriteria->mergeWith($criteria);
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
