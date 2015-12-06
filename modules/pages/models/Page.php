<?php

class Page extends ActiveRecord {

    const route = '/pages/admin/default';
    const MODULE_ID = 'pages';

    /**
     * Multilingual attrs
     */
    public $title;
    public $full_text;
    public $seo_title;
    public $seo_description;
    public $seo_keywords;
    public $in_menu;

    /**
     * Name of the translations model.
     */
    public $translateModelName = 'PageTranslate';

    public function getForm() {
        Yii::import('zii.widgets.jui.CJuiDatePicker');
        Yii::app()->controller->widget('ext.tinymce.TinymceWidget');
        return new TabForm(array(
            'showErrorSummary' => true,
            'attributes' => array(
                'class' => 'form-horizontal',
                'id' => __CLASS__,
            ),
            'elements' => array(
                'content' => array(
                    'type' => 'form',
                    'title' => $this->t('TAB_CONTENT'),
                    'elements' => array(
                        'title' => array(
                            'type' => 'text',
                            'id' => 'title'
                        ),
                        'seo_alias' => array(
                            'type' => 'text',
                            'id' => 'alias',
                            'visible' => (Yii::app()->settings->get('core', 'translate_object_url')) ? false : true
                        ),
                        'full_text' => array(
                            'type' => 'textarea',
                            'class' => 'editor'
                        ),
                    ),
                ),
                'seo' => array(
                    'type' => 'form',
                    'title' => $this->t('TAB_META'),
                    'elements' => array(
                        'seo_title' => array(
                            'type' => 'text',
                        ),
                        'seo_keywords' => array(
                            'type' => 'textarea',
                        ),
                        'seo_description' => array(
                            'type' => 'textarea',
                        ),
                    ),
                ),
                'additional' => array(
                    'type' => 'form',
                    'title' => $this->t('TAB_ADDITIONALLY'),
                    'elements' => array(
                        'switch' => array(
                            'type' => 'dropdownlist',
                            'items' => array(0 => Yii::t('app', 'OFF', 0), 1 => Yii::t('app', 'ON', 0))
                        ),
                        'in_menu' => array(
                            'type' => 'checkbox',
                        ),
                        'date_create' => array(
                            'type' => 'CJuiDatePicker',
                            'options' => array(
                                'dateFormat' => 'yy-mm-dd ' . date('H:i:s'),
                            ),
                            'htmlOptions' => array(
                                'value' => ($this->isNewRecord) ? date('Y-m-d H:i:s') : $this->date_create,
                            )
                        ),
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

    public function getGridColumns() {
        return array(
            array(
                'name' => 'title',
                'type' => 'raw',
                'htmlOptions' => array('class' => 'textL'),
                'value' => 'Html::link(Html::encode($data->title),"/page/$data->seo_alias", array("target"=>"_blank"))',
            ),
            array(
                'name' => 'user_id',
                'type' => 'raw',
                'value' => 'CMS::userLink($data->user)',
            ),
            array(
                'name' => 'views',
                'value' => '$data->views',
            ),
            array(
                'name' => 'date_create',
                'value' => 'CMS::date($data->date_create)',
            ),
            array(
                'name' => 'date_update',
                'value' => 'CMS::date($data->date_update)',
            ),
            'DEFAULT_CONTROL' => array(
                'class' => 'ButtonColumn',
                'template' => '{switch}{update}{delete}',
            ),
            'DEFAULT_COLUMNS' => array(
                array('class' => 'CCheckBoxColumn'),
                array('class' => 'HandleColumn')),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @return Page the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{page}}';
    }

    public function defaultScope() {
        return array(
            'order' => 'date_create DESC',
        );
    }

    public function scopes() {
        return array(
            'published' => array(
                'condition' => 'date_create <= :date AND switch = :switch',
                'params' => array(
                    ':date' => date('Y-m-d H:i:s'),
                    ':switch' => 1
                ),
            ),
            'inMenu' => array(
                'condition' => 'in_menu = 1',
            ),
        );
    }

    /**
     * Find page by url.
     * Scope.
     * @param string Page url
     * @return Page
     */
    public function withUrl($url) {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'seo_alias=:url',
            'params' => array(':url' => $url)
        ));

        return $this;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('full_text', 'type', 'type' => 'string'),
            array('title, full_text', 'required'),
            array('seo_alias', 'translitFilter', 'translitAttribute' => 'title'),
            array('date_create, date_update', 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'),
            array('title, seo_alias, seo_title, seo_description, seo_keywords', 'length', 'max' => 255),
            array('title, full_text', 'length', 'min' => 3),
            array('in_menu', 'numerical', 'integerOnly' => true),
            array('id, in_menu, user_id, title, seo_alias, full_text, seo_title, seo_description, seo_keywords, date_update, date_create', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'translate' => array(self::HAS_ONE, $this->translateModelName, 'object_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'category' => array(self::BELONGS_TO, 'CategoriesModel', 'catid')
        );
    }

    /**
     * @return array
     */
    public function behaviors() {
        return array(
            'TranslateBehavior' => array(
                'class' => 'app.behaviors.TranslateBehavior',
                'translateAttributes' => array(
                    'title',
                    'full_text',
                    'seo_title',
                    'seo_description',
                    'seo_keywords',
                ),
            ),
        );
    }

    public function getLinkUrl() {
        return Yii::app()->createUrl('/pages/default/index', array('url' => $this->seo_alias));
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions. Used in admin search.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->with = array('user', 'translate');

        $criteria->compare('t.id', $this->id);
        $criteria->compare('user.username', $this->user_id, true);
        $criteria->compare('translate.title', $this->title, true);
        $criteria->compare('t.seo_alias', $this->seo_alias, true);
        $criteria->compare('translate.full_text', $this->full_text, true);
        $criteria->compare('translate.seo_title', $this->seo_title, true);
        $criteria->compare('translate.seo_description', $this->seo_description, true);
        $criteria->compare('translate.seo_keywords', $this->seo_keywords, true);
        $criteria->compare('t.date_create', $this->date_create, true);
        $criteria->compare('t.date_update', $this->date_update, true);
        $criteria->compare('t.switch', $this->switch);

        // Create sorting by translation title
        $sort = new CSort;
        $sort->attributes = array(
            '*',
            'title' => array(
                'asc' => 'translate.title',
                'desc' => 'translate.title DESC',
            )
        );

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => $sort,
        ));
    }

}
