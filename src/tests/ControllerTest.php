<?php

declare(strict_types = 1);

/**
 * Date: 28.03.17
 * Time: 17:32
 *
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 *
 * phpunit src/tests/ControllerTest --coverage-html src/tests/coverage-html
 */

use PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;
use Rudra\IContainer;
use Rudra\Container;
use Rudra\IController;
use Rudra\Controller;
use Rudra\Model;

/**
 * Class ControllerTest
 */
class ControllerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var IContainer
     */
    protected $container;

    /**
     * @var IController
     */
    protected $controller;

    protected function setUp(): void
    {
        $this->container = Container::app();
        $this->container->setBinding(IContainer::class, Container::$app);
        $this->controller = new Controller();
        $this->controller->before();
        $this->controller->init($this->container, 'twig');
        $this->controller->after();
    }

    /**
     * @runInSeparateProcess
     */
    public function testInit()
    {
        $this->assertInstanceOf(IContainer::class, $this->controller()->container());
        $this->assertTrue($this->container()->hasSession('csrf_token'));
        $this->controller()->csrfProtection();
    }

    /**
     * @runInSeparateProcess
     */
    public function testView()
    {
        $this->assertEquals('"Hello World!!!"', $this->controller()->view('index', ['title' => 'title']));
    }

    /**
     * @runInSeparateProcess
     */
    public function testTwig()
    {
        $this->assertEquals('"Hello World!!!"', $this->controller()->twig('index.html.twig', ['title' => 'title']));
    }

    /**
     * @runInSeparateProcess
     */
    public function testModel()
    {
        $this->controller()->setModel(Model::class);
        $this->assertInstanceOf(Model::class, $this->controller()->model());
    }

    /**
     * @runInSeparateProcess
     */
    public function testData()
    {
        $this->controller()->setData([
            'first'  => 'one',
        ]);

        $this->controller()->setData('two', 'second');

        $this->assertEquals('one', $this->controller()->getData('first'));
        $this->assertEquals('two', $this->controller()->getData('second'));
        $this->assertArrayHasKey('first', $this->controller()->getData());
    }

    /**
     * @return IController
     */
    public function controller(): IController
    {
        return $this->controller;
    }

    /**
     * @return IContainer
     */
    public function container(): IContainer
    {
        return $this->container;
    }
}