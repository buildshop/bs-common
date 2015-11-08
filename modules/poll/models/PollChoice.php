<?php

/**
 * This is the model class for table "{{poll_choice}}".
 *
 * The followings are the available columns in table '{{poll_choice}}':
 * @property string $id
 * @property string $poll_id
 * @property string $label
 * @property string $votes
 * @property integer $weight
 *
 * The followings are the available model relations:
 * @property Poll $poll
 * @property PollVote[] $pollVotes
 */
class PollChoice extends ActiveRecord
{
  /**
   * Returns the static model of the specified AR class.
   * @return PollChoice the static model class
   */
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function tableName()
  {
    return '{{poll_choice}}';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    return array(
      array('poll_id, name', 'required'),
      array('ordern', 'numerical', 'integerOnly'=>true),
      array('poll_id, votes', 'length', 'max'=>11),
      array('name', 'length', 'max'=>255),
      array('name, votes, ordern', 'safe', 'on'=>'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations()
  {
    return array(
      'poll' => array(self::BELONGS_TO, 'Poll', 'poll_id'),
      'pollVotes' => array(self::HAS_MANY, 'PollVote', 'choice_id'),
    );
  }

  /**
   * @return array default scope.
   */
  public function defaultScope()
  {
    return array(
      'order' => 'ordern ASC, name ASC',
    );
  }

  /**
   * @return array of weights for sorting
   */
  public function getWeights()
  {
    $weights = range(-5, 5);

    return array_combine($weights, $weights);
  }
  public function attributeLabels()
  {
    return array(
      'id' => 'ID',
      'name' => 'Название',

    );
  }
  public function search()
  {
    $criteria=new CDbCriteria;

    $criteria->compare('name',$this->name,true);
    $criteria->compare('votes',$this->votes,true);
    $criteria->compare('ordern',$this->ordern);

    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
    ));
  }

}
