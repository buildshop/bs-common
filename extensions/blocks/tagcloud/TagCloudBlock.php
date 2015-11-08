<?php

class TagCloudBlock extends BlockWidget {

    public $alias = 'ext.blocks.tagcloud';

    public function getTitle() {
        return $this->config['title'];
    }

    public function run() {
        $tags = Tag::model()->findTagWeights($this->config['maxTags']); //
        if (!empty($tags)) {
            foreach ($tags as $tag => $weight) {
                echo Yii::app()->controller->route;
                $link = Html::link(Html::encode($tag), array(Yii::app()->controller->route, 'tag' => $tag));
                echo Html::tag('span', array(
                    'class' => 'tag',
                    'style' => "font-size:{$weight}pt",
                        ), $link) . "\n";
            }
        } else {
            Yii::app()->tpl->alert('warning', 'Нет неодного тега', false);
        }
    }


}