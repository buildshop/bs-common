<?php

class CManagerSettings extends CComponent {

    /**
     * @var array
     */
    protected $data = array();
    public $cache_key = 'cached_settings';

    /**
     * Initialize component
     */
    public function init() {
        $this->data = Yii::app()->cache->get($this->cache_key);

        if (!$this->data) {
            // Load settings
            $settings = Yii::app()->db->createCommand()
                    ->from('{{settings}}')
                    ->order('category')
                    ->queryAll();

            if (!empty($settings)) {
                foreach ($settings as $row) {
                    if (!isset($this->data[$row['category']]))
                        $this->data[$row['category']] = array();
                    $this->data[$row['category']][$row['key']] = $row['value'];
                }
            }
            Yii::app()->cache->set($this->cache_key, $this->data);
        }
    }

    /**
     * @param $category string component unique id. e.g: contacts, shop, news
     * @param array $data key-value array. e.g array('param'=>10)
     */
    public function set($category, array $data) {
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if ($this->get($category, $key) !== null) {
                    Yii::app()->db->createCommand()->update('{{settings}}', array(
                        'value' => $value), '{{settings}}.category=:category AND {{settings}}.key=:key', array(':category' => $category, ':key' => $key));
                } else {
                    Yii::app()->db->createCommand()->insert('{{settings}}', array(
                        'category' => $category,
                        'key' => $key,
                        'value' => $value
                    ));
                }
            }

            if (!isset($this->data[$category]))
                $this->data[$category] = array();
            $this->data[$category] = CMap::mergeArray($this->data[$category], $data);

            // Update cache
            Yii::app()->cache->set($this->cache_key, $this->data);
        }
    }

    /**
     * @param $category string component unique id.
     * @param null $key option key. If not provided all category settings will be returned as array.
     * @param null|string $default default value if original does not exists
     * @return mixed
     */
    public function get($category, $key = null, $default = null) {
        if (!isset($this->data[$category]))
            return $default;

        if ($key === null)
            return $this->data[$category];
        if (isset($this->data[$category][$key]))
            return $this->data[$category][$key];
        else
            return $default;
    }

    /**
     * Remove category from DB
     * @param $category
     */
    public function clear($category) {
        Yii::app()->db->createCommand()->delete('{{settings}}', 'category=:category', array(':category' => $category));
        if (isset($this->data[$category]))
            unset($this->data[$category]);

        Yii::app()->cache->delete($this->cache_key);
    }

}
