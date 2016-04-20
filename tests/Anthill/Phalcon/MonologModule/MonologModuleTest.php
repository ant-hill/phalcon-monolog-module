<?php

namespace Tests\Anthill\Phalcon\MonologModule;

use Anthill\Phalcon\KernelModule\DependencyInjection\Exceptions\ConfigParseException;
use Anthill\Phalcon\MonologModule\MonologModule;
use Phalcon\Config;
use Phalcon\Di;
use Psr\Log\LoggerInterface;

class MonologModuleTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        @unlink(__DIR__ . '/Fixtures/test.log');
    }

    private function registerService($di)
    {
        $module = new MonologModule();
        $config = include __DIR__ . '/Fixtures/config.php';
        $module->setConfig(new Config($config));
        $module->registerServices($di);
    }

    public function testService()
    {
        $di = new Di();
        $this->registerService($di);
        $this->assertTrue($di->has('monolog'));
        $this->assertInstanceOf(LoggerInterface::class, $di->get('monolog'));
    }

    public function testWriteLog()
    {
        $di = new Di();
        $this->registerService($di);
        /* @var $monolog LoggerInterface */
        $monolog = $di->get('monolog');
        $monolog->debug('asd');
        $this->assertFileNotExists(__DIR__ . '/Fixtures/test.log');
        $monolog->warning('qwe');
        $this->assertFileNotExists(__DIR__ . '/Fixtures/test.log');
        $monolog->info('qweqwe');
        $this->assertFileNotExists(__DIR__ . '/Fixtures/test.log');

        $monolog->emergency('qweqwe');
        $this->assertFileExists(__DIR__ . '/Fixtures/test.log');
        $this->assertNotFalse(strpos(file_get_contents(__DIR__ . '/Fixtures/test.log'), 'qweqwe'));
    }

    public function testBuildServiceWithoutConfig()
    {
        $this->setExpectedException(ConfigParseException::class);
        $di = new Di();
        $module = new MonologModule();
        $module->setConfig(new Config([]));
        $module->registerServices($di);
        $di->get('monolog');
    }

    public function testBuildServiceWithoutPathInConfig()
    {
        $this->setExpectedException(ConfigParseException::class,
            'You must specify parameter "monolog => log_path" in config');
        $di = new Di();
        $module = new MonologModule();
        $module->setConfig(new Config(include __DIR__ . '/Fixtures/config_without_log_path.php'));
        $module->registerServices($di);
        $di->get('monolog');
    }

    public function testBuildServiceWithoutLogLevelInConfig()
    {
        $this->setExpectedException(ConfigParseException::class,
            'You must specify parameter "monolog => log_level" in config');
        $di = new Di();
        $module = new MonologModule();
        $module->setConfig(new Config(include __DIR__ . '/Fixtures/config_without_log_level.php'));
        $module->registerServices($di);
        $di->get('monolog');
    }
}