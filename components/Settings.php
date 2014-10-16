<?php

/**
 * @link http://phe.me
 * @copyright Copyright (c) 2014 Pheme
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace pheme\settings\components;

use yii\base\Component;
use yii\caching\Cache;
use Yii;

/**
 * @author Aris Karageorgos <aris@phe.me>
 */
class Settings extends Component
{
    /**
     * @var string settings model. Make sure your settings model calls clearCache in the afterSave callback
     */
    public $modelClass = 'pheme\settings\models\Setting';

    /**
     * Model to for storing and retrieving settings
     * @var \pheme\settings\models\SettingInterface
     */
    protected $model;

    /**
     * @var Cache|string the cache object or the application component ID of the cache object.
     * Settings will be cached through this cache object, if it is available.
     *
     * After the Settings object is created, if you want to change this property,
     * you should only assign it with a cache object.
     * Set this property to null if you do not want to cache the settings.
     */
    public $cache = 'cache';

    /**
     * @var Cache|string the front cache object or the application component ID of the front cache object.
     * Front cache will be cleared through this cache object, if it is available.
     *
     * After the Settings object is created, if you want to change this property,
     * you should only assign it with a cache object.
     * Set this property to null if you do not want to clear the front cache.
     */
    public $frontCache;

    /**
     * To be used by the cache component.
     *
     * @var string cache key
     */
    public $cacheKey = 'pheme/settings';

    /**
     * Holds a cached copy of the data for the current request
     *
     * @var mixed
     */
    private $_data = null;

    /**
     * Initialize the component
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $this->model = new $this->modelClass;

        if (is_string($this->cache)) {
            $this->cache = Yii::$app->get($this->cache, false);
        }
        if (is_string($this->frontCache)) {
            $this->frontCache = Yii::$app->get($this->frontCache, false);
        }
    }

    /**
     * Get's the value for the given key and section.
     * You can use dot notation to separate the section from the key:
     * $value = $settings->get('section.key');
     * and
     * $value = $settings->get('key', 'section');
     * are equivalent
     *
     * @param $key
     * @param null $section
     * @param null $default
     * @return mixed
     */
    public function get($key, $section = null, $default = null)
    {
        if (is_null($section)) {
            $pieces = explode('.', $key, 2);
            if (count($pieces) > 1) {
                $section = $pieces[0];
                $key = $pieces[1];
            } else {
                $section = '';
            }
        }

        $data = $this->getRawConfig();

        if (isset($data[$section][$key][0])) {
            settype($data[$section][$key][0], $data[$section][$key][1]);
        } else {
            $data[$section][$key][0] = $default;
        }
        return $data[$section][$key][0];
    }

    /**
     * @param $key
     * @param $value
     * @param null $section
     * @param null $type
     * @return boolean
     */
    public function set($key, $value, $section = null, $type = null)
    {
        if (is_null($section)) {
            $pieces = explode('.', $key);
            $section = $pieces[0];
            $key = $pieces[1];
        }

        if ($this->model->setSetting($section, $key, $value, $type)) {
            if ($this->clearCache()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Deletes a setting
     *
     * @param $key
     * @param null|string $section
     * @return bool
     */
    public function delete($key, $section = null)
    {
        if (is_null($section)) {
            $pieces = explode('.', $key);
            $section = $pieces[0];
            $key = $pieces[1];
        }
        return $this->model->deleteSetting($section, $key);
    }

    /**
     * Deletes all setting. Be careful!
     *
     * @return bool
     */
    public function deleteAll()
    {
        return $this->model->deleteAllSettings();
    }

    /**
     * Activates a setting
     *
     * @param $key
     * @param null|string $section
     * @return bool
     */
    public function activate($key, $section = null)
    {
        if (is_null($section)) {
            $pieces = explode('.', $key);
            $section = $pieces[0];
            $key = $pieces[1];
        }
        return $this->model->activateSetting($section, $key);
    }

    /**
     * Deactivates a setting
     *
     * @param $key
     * @param null|string $section
     * @return bool
     */
    public function deactivate($key, $section = null)
    {
        if (is_null($section)) {
            $pieces = explode('.', $key);
            $section = $pieces[0];
            $key = $pieces[1];
        }
        return $this->model->deactivateSetting($section, $key);
    }

    /**
     * Clears the settings cache on demand.
     * If you haven't configured cache this does nothing.
     *
     * @return boolean True if the cache key was deleted and false otherwise
     */
    public function clearCache()
    {
        $this->_data = null;
        if ($this->frontCache instanceof Cache) {
            $this->frontCache->delete($this->cacheKey);
        }
        if ($this->cache instanceof Cache) {
            return $this->cache->delete($this->cacheKey);
        }
        return true;
    }

    /**
     * Returns the raw configuration array
     *
     * @return array
     */
    public function getRawConfig()
    {
        if ($this->_data === null) {
            if ($this->cache instanceof Cache) {

                $data = $this->cache->get($this->cacheKey);

                if ($data === false) {
                    $data = $this->model->getSettings();
                    $this->cache->set($this->cacheKey, $data);
                }
            } else {
                $data = $this->model->getSettings();
            }
            $this->_data = $data;
        }
        return $this->_data;
    }
}
