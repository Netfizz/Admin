<?php namespace Netfizz\Admin\Traits;

use Config;

trait ManageConfig {

    /**
     * @param $config
     * @param null $key
     */
    public function setConfig($config, $key = null)
    {
        Config::set($this->getConfigKey($key), $config);
    }

    /**
     * @param $config
     * @param null $key
     */
    public function addConfig($config, $key = null)
    {
        // merge current config and new config params
        $mergedConfig = array_merge(Config::get($this->getConfigKey($key)), $config);

        Config::set($key, $mergedConfig);
    }

    /**
     * @param null $key
     * @return mixed
     */
    protected function getConfig($key = null) {
        return Config::get($this->getConfigKey($key));
    }

    /**
     * @param null $key
     * @return string
     */
    protected function getConfigKey($key = null)
    {
        $className = 'this.'.class_basename(get_called_class());

        if ($key !== null)
            return $className . '.' .$key;

        return $className;
    }

} 