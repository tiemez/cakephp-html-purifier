<?php
/**
 * Purifier
 *
 * @author    Florian Krämer
 * @copyright 2012 - 2018 Florian Krämer
 * @license   MIT
 */
namespace Tiemez\HtmlPurifier\Lib;

use HTMLPurifier;

/**
 * Purifier
 */
class Purifier
{

    /**
     * Purifier configurations
     *
     * @var array
     */
    protected $_configs = [];

    /**
     * HTMLPurifier instances
     *
     * @var array
     */
    protected $_instances = [];

    /**
     * Return a singleton instance of the StorageManager.
     *
     * @return Purifier Purifier instance
     */
    public static function &getInstance()
    {
        static $instance = [];
        if (!$instance) {
            $instance[0] = new Purifier();
        }

        return $instance[0];
    }

    /**
     * Gets and sets purifier configuration sets.
     *
     * @param  string $configName
     * @param  string $config
     * @throws \InvalidArgumentException
     * @return \HTMLPurifier_Config
     */
    public static function config($configName, $config = null)
    {
        $_this = Purifier::getInstance();

        if (empty($config)) {
            if (!isset($_this->_configs[$configName])) {
                throw new \InvalidArgumentException(sprintf('Purifier configuration `%s` does not exist!', $configName));
            }
            return $_this->_configs[$configName];
        }

        if (is_array($config)) {
            $purifierConfig = \HTMLPurifier_Config::createDefault();
            foreach ($config as $key => $value) {
                $purifierConfig->set($key, $value);
            }

            return $_this->_configs[$configName] = $purifierConfig;
        } elseif (is_object($config) && is_a($config, 'HTMLPurifier_Config')) {
            return $_this->_configs[$configName] = $config;
        } else {
            throw new \InvalidArgumentException('Invalid purifier config passed!');
        }
    }

    /**
     * Gets an instance of the purifier lib only when needed, lazy loading it
     *
     * @param  string $configName
     * @return HTMLPurifier
     */
    public static function getPurifierInstance($configName = 'default')
    {
        $_this = Purifier::getInstance();

        if (!isset($_this->_instances[$configName])) {
            if (!isset($_this->_configs[$configName])) {
                throw new \InvalidArgumentException(sprintf('Configuration and instance `%s` does not exist!', $configName));
            }
            $_this->_instances[$configName] = new \HTMLPurifier($_this->_configs[$configName]);
        }

        return $_this->_instances[$configName];
    }

    /**
     * Cleans Markup using a given config
     *
     * @param string $markup
     * @param string $configName
     */
    public static function clean($markup, $configName = 'default')
    {
        $_this = Purifier::getInstance();

        if (!isset($_this->_configs[$configName])) {
            throw new \InvalidArgumentException(sprintf('Invalid HtmlPurifier configuration "%s"!', $configName));
        }

        return $_this->getPurifierInstance($configName)->purify($markup);
    }
}
