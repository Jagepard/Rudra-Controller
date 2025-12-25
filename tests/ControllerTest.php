<?php

declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 * 
 * phpunit src/tests/ContainerTest --coverage-html src/tests/coverage-html
 */


namespace Rudra\Controller\Tests;

use PHPUnit\Framework\TestCase;
use Rudra\Container\Facades\Session;
use Rudra\Container\Facades\Rudra as Rudra;
use Rudra\Container\Interfaces\RudraInterface;
use Rudra\Controller\{Controller, ControllerInterface};

class ControllerTest extends TestCase
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
