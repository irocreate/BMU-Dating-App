<?php

class Wpdating_Option_Config

{
    var $configs;
    static $_this;

    function loadConfig()
    {
        $this->configs = get_option('wpdating_option_config_v2');
        if (empty($this->configs)) {
            $wpdating_option_raw_configs = get_option('wpdating_option_config');
            if (is_string($wpdating_option_raw_configs)) {
                $this->configs = unserialize($wpdating_option_raw_configs);
            } else {
                $this->configs = unserialize((string)$wpdating_option_raw_configs);
            }
        }

        if (empty($this->configs)) {
            $this->configs = array();
        }//This a brand new install site with no config data so initilize with a new array
    }

    function getValue($key)
    {
        return isset($this->configs[$key]) ? $this->configs[$key] : '';
    }

    function setValue($key, $value)
    {
        $this->configs[$key] = $value;
    }

    function saveConfig()
    {
        update_option('wpdating_option_config', serialize($this->configs));
        update_option('wpdating_option_config_v2', $this->configs);
    }

    function addValue($key, $value)
    {
        if (array_key_exists($key, $this->configs)) {
            //Don't update the value for this key
        } else {
            //It is save to update the value for this key
            $this->configs[$key] = $value;
        }
    }

    static function getInstance()
    {
        if (empty(self::$_this)) {
            self::$_this = new Wpdating_Option_Config();
            self::$_this->loadConfig();

            return self::$_this;
        }

        return self::$_this;
    }
}


class Wpdating_Option_Config_Helper
{
    static function add_options_config_values()
    {
        $Wpdating_Option_Config = Wpdating_Option_Config::getInstance();
        $Wpdating_Option_Config->addValue('powered_by', 1);
        $Wpdating_Option_Config->saveConfig();
    }
}

