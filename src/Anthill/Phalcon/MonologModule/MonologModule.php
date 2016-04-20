<?php

namespace Anthill\Phalcon\MonologModule;


use Anthill\Phalcon\KernelModule\Mvc\AbstractModule;
use Phalcon\Config;

class MonologModule extends AbstractModule
{
    const MODULE_NAME = 'monolog';

    /**
     * Get config path
     * @return string
     */
    public function getModuleName()
    {
        return self::MODULE_NAME;
    }

    /**
     * Get config path
     * @return string
     */
    public function getConfigPath()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getServicesPath()
    {
        return __DIR__ . '/config/services.php';
    }
}