<?php

class Poll extends ActiveRecord {

    const STATUS_CLOSED = 0;
    const STATUS_OPEN = 1;


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{poll}}';
    }

    public function getForm() {
        Yii::app()->controller->widget('ext.tinymce.TinymceWidget');
        return new CMSForm(array('id' => __CLASS__,
                    'showErrorSummary' => false,
                    'elements' => array(
                        'title' => array(
                            'type' => 'text',
                        ),
                        'description' => array(
                            'type' => 'textarea',
                            'class' => 'editor',
                        ),
                        'many' => array('type' => 'checkbox'),
                        'switch' => array(
                            'type' => 'dropdownlist',
                            'items' => $this->statusLabels(),
                        ),
                    ),
                    'buttons' => array(
                        'submit' => array(
                            'type' => 'submit',
                            'class' => 'buttonS bGreen',
                            'label' => ($this->isNewRecord) ? Yii::t('core', 'CREATE', 0) : Yii::t('core', 'SAVE')
                        )
                    ),
                        ), $this);
    }

    public function test($id) {
        $dataProvider = new CActiveDataProvider('PollChoice', array(
                    'criteria' => array(
                        'condition' => '`t`.`poll_id`=' . $id,
                        'order' => 'ordern DESC',
                        'with' => array('poll'),
                    ),
                ));
        return $dataProvider;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('title', 'required'),
            array('switch, many', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            array('description', 'safe'),
            array('id, title, description, switch, many', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'choices' => array(self::HAS_MANY, 'PollChoice', 'poll_id'),
            'choices2' => array(self::BELONGS_TO, 'PollChoice', 'poll_id'),
            'votes' => array(self::HAS_MANY, 'PollVote', 'poll_id'),
            'totalVotes' => array(self::STAT, 'PollChoice', 'poll_id', 'select' => 'SUM(votes)'),
        );
    }

    /**
     * @return array additional query scopes
     */
    public function scopes() {
        return array(
            'active' => array(
                'condition' => 'switch=' . self::STATUS_OPEN,
            ),
            'closed' => array(
                'condition' => 'switch=' . self::STATUS_CLOSED,
            ),
            'latest' => array(
                'order' => 'id DESC',
            ),
            'rand' => array(
                'order' => 'rand()',
            ),
        );
    }

    public function defaultScope() {
        return array(
            'order' => '`t`.`ordern` DESC',
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => 'Название опроса',
            'description' => 'Описание',
            'many' => 'Выбор несколько вариантов',
            'switch' => 'Показать/Скрыть',
        );
    }

    /**
     * @return array customized status labels
     */
    public function statusLabels() {
        return array(
            self::STATUS_CLOSED => 'Closed',
            self::STATUS_OPEN => 'Open',
        );
    }

    /**
     * Returns the text label for the specified status.
     */
    public function getStatusLabel($status) {
        $labels = self::statusLabels();

        if (isset($labels[$status])) {
            return $labels[$status];
        }

        return $status;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;
        //$criteria->with = array('choices');
        $criteria->compare('id', $this->id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('switch', $this->switch);

        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * Determine if a user can vote on a Poll.
     */
    public function userCanVote() {
        if ($this->switch == self::STATUS_CLOSED)
            return FALSE;

        // Setup global query attributes
        $where = array('and', 'poll_id=:poll_id', 'user_id=:user_id');
        $params = array(':poll_id' => $this->id, ':user_id' => (int) Yii::app()->user->id);

        // Add IP restricted attributes if needed
        if (Yii::app()->settings->get('poll', 'ip_restrict') === TRUE && Yii::app()->user->isGuest) {
            $where[] = 'ip_address=:ip_address';
            $params[':ip_address'] = $_SERVER['REMOTE_ADDR'];
        }

        // Retrieve true/false if a vote exists on poll by user
        $result = (bool) Yii::app()->db->createCommand(array(
                    'select' => 'id',
                    'from' => '{{poll_vote}}',
                    'where' => $where,
                    'params' => $params,
                ))->queryRow();

        return !$result;
    }

}
