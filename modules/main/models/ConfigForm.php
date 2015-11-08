<?php

class ConfigForm extends CFormModel {


    public $pagenum; // Количество 
    public $sort;
    public $seoTitle;
    public $seoKeywords;
    public $seoDescription;
public $widgetLastNum;
   // public $siteName; // Название сайта
    //public $multiLanguage; //Много язычность

    public function rules() {
        return array(
            array('pagenum, sort','required'),
            array('pagenum,widgetLastNum', 'numerical', 'integerOnly' => true),
          //  array('', 'length', 'max' => 250),
           array('pagenum, sort, seoTitle, seoKeywords, seoDescription', 'type', 'type' => 'string'),

        );
    }

    public function attributeLabels() {
        return array(

            'pagenum' => 'Пагинация',
            'sort' => 'Сортировка',
            'seoTitle' => 'SEO заголовок',
            'seoKeywords' => 'SEO keywords',
            'seoDescription' => 'SEO описание',
'widgetLastNum' => 'Кол. последних записей',

        );
    }

}

?>