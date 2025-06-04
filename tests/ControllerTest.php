<?php

declare(strict_types=1);

/**
 * @author  : Jagepard <jagepard@yandex.ru">
 * @license https://mit-license.org/ MIT
 *
 * phpunit src/tests/ControllerTest --coverage-html src/tests/coverage-html
 */

namespace Rudra\Controller\Tests;

use Rudra\Container\Facades\Session;
use Rudra\Container\Facades\Rudra as Rudra;
use Rudra\Container\Interfaces\RudraInterface;
use Rudra\Controller\{Controller, ControllerInterface};
use PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

class ControllerTest extends PHPUnit_Framework_TestCase
{
    protected ControllerInterface $controller;

    protected function setUp(): void
    {
        $this->controller = new Controller();

        $this->controller->init();
        $this->controller->before();
        $this->controller->after();
    }

    /**
     * @runInSeparateProcess
     */
    public function testInit()
    {
        $this->assertTrue(Session::has("csrf_token"));
        $this->controller->csrfProtection();
    }
}
