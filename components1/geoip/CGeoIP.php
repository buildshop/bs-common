<?php

/**
 * +----------------------------------------------------------------------+
 * | PHP version 5                                                        |
 * +----------------------------------------------------------------------+
 * | Copyright (C) 2010 Dinis Lage                                        |
 * +----------------------------------------------------------------------+
 * | This library is free software; you can redistribute it and/or        |
 * | modify it under the terms of the GNU Lesser General Public           |
 * | License as published by the Free Software Foundation; either         |
 * | version 2.1 of the License, or (at your option) any later version.   |
 * |                                                                      |
 * | This library is distributed in the hope that it will be useful,      |
 * | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
 * | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU    |
 * | Lesser General Public License for more details.                      |
 * |                                                                      |
 * | You should have received a copy of the GNU Lesser General Public     |
 * | License along with this library; if not, write to the Free Software  |
 * | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 |
 * | USA, or view it online at http://www.gnu.org/licenses/lgpl.txt.      |
 * +----------------------------------------------------------------------+
 * | Authors: Dinis Lage <dinislage@gmail.com>                            |
 * +----------------------------------------------------------------------+
 *
 * @category Net
 * @author Dinis Lage <dinislage@gmail.com>
 * @license  LGPL http://www.gnu.org/licenses/lgpl.txt
 * $Id: GeoIP.php 296763 2010-03-25 00:53:44Z clockwerx $
 */
/**
 * CGeoip class file.
 *
 * @author Dinis Lage <dinislage@gmail.com>
 * @link http://www.yiiframework.com/
 * @version 0.1
 */
Yii::import('app.geoip.GeoIP');

class CGeoIP extends CApplicationComponent {

    public $filedat = 'GeoIPCity';
    public $filename;
    public $mode;
    protected static $flags = GeoIP::STANDARD;
    protected static $geoip;

    public function init() {
        $this->filename = Yii::getPathOfAlias('app.geoip.dats') . DS . $this->filedat . '.dat';
        $this->mode = YII_DEBUG === true ? 'STANDARD' : 'MEMORY_CACHE';
        switch ($this->mode) {
            case 'MEMORY_CACHE':
                self::$flags = GeoIP::MEMORY_CACHE;
                break;
            default:
                self::$flags = GeoIP::STANDARD;
                break;
        }
        self::$geoip = GeoIP::getInstance($this->filename, self::$flags);
        // Run parent
        parent::init();
    }

    public function lookupLocation($ip = null) {
        if ($this->filedat == 'GeoIPCity') {
            $ip = $this->_getIP($ip);
            return self::$geoip->lookupLocation($ip);
        } else {
            $this->alert(__METHOD__);
        }
    }

    public function lookupCountryCode($ip = null) {
        $ip = $this->_getIP($ip);
        if ($this->filedat == 'GeoIP') {
            return self::$geoip->lookupCountryCode($ip);
        } elseif ($this->filedat == 'GeoIPCity') {
            return self::$geoip->lookupLocation($ip)->countryCode;
        } else {
            $this->alert(__METHOD__);
        }
    }

    public function lookupCountryName($ip = null) {
        if ($this->filedat == 'GeoIP') {
            $ip = $this->_getIP($ip);
            return self::$geoip->lookupCountryName($ip);
        } elseif ($this->filedat == 'GeoIPCity') {
            return self::$geoip->lookupLocation($ip)->countryName;
        } else {
            $this->alert(__METHOD__);
        }
    }

    public function lookupOrg($ip = null) {
        if ($this->filedat != 'GeoIPCity' || $this->filedat != 'GeoIP' || $this->filedat != 'GeoIPRegion') {
            $ip = $this->_getIP($ip);
            return self::$geoip->lookupOrg($ip);
        } else {
            $this->alert(__METHOD__);
        }
    }

    public function lookupRegion($ip = null) {
        if ($this->filedat != 'GeoIPCity' || $this->filedat != 'GeoIP' || $this->filedat != 'GeoIPRegion') {
            $ip = $this->_getIP($ip);
            return self::$geoip->lookupRegion($ip);
        } else {
            $this->alert(__METHOD__);
        }
    }

    protected function _getIP($ip = null) {
        if ($ip === null) {
            $ip = Yii::app()->getRequest()->getUserHostAddress();
        }
        return $ip;
    }

    private function alert($method = __METHOD__) {
        Yii::app()->tpl->alert('warning', Yii::t('CGeoIP.default', 'NO_SUPPORT_METHOD', array(
                    '{method}' => $method,
                    '{filedat}' => $this->filedat
                )), false);
    }

}

?>
