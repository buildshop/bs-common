<?php

/**
 * DEMO
 */
class ConfiguratorMaterial extends CWidget {

    public function init() {
        parent::init();
    }

    public function run() {

        $this->render($this->skin, array('list' => $this->demoArray()));
    }

    private function demoArray() {
        return array(
            array(
                'name'=>'Орех',
                'id'=>1,
                'image'=>'pi.jpg'
            ),
            array(
                'name'=>'Орех',
                'id'=>1,
                'image'=>'pi.jpg'
            )
            
        );
    }

}
