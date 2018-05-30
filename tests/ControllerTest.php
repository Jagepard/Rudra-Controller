<?php

declare(strict_types=1);

/**
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2018, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 *
 * phpunit src/tests/ControllerTest --coverage-html src/tests/coverage-html
 */

namespace Rudra\Tests;

use Rudra\Container;
use Rudra\Controller;
use Rudra\Interfaces\ContainerInterface;
use Rudra\Interfaces\ControllerInterface;
use PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

/**
 * Class ControllerTest
 */
class ControllerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ControllerInterface
     */
    protected $controller;

    protected function setUp(): void
    {
        $_FILES = [
            'upload' =>
                ['name'     => ['img' => 'demo.png'],
                 'type'     => ['img' => 'image/png'],
                 'tmp_name' => ['img' => '/tmp/phpiQuDkR'],
                 'error'    => ['img' => 0],
                 'size'     => ['img' => 9584],
                ]
        ];

        $_POST = [
            'img'   => 'http://example.com/images/img.png',
            'image' => 'http://example.com/images/image.png',
        ];

        $this->container = Container::app();
        $this->container->setConfig([
            'bp'         => dirname(__DIR__) . '/',
            'env'        => 'development',
            'engine'     => 'twig',
            'view.path'  => 'app/resources/twig/view',
            'cache.path' => 'app/resources/twig/compilation_cache'
        ]);

        $this->container->setBinding(ContainerInterface::class, Container::$app);
        $this->container->set('debugbar', 'DebugBar\StandardDebugBar');
        $this->controller = new Controller();
        $this->controller->before();
        $this->controller->init($this->container);
        $this->controller->after();
    }

    /**
     * @runInSeparateProcess
     */
    public function testInit()
    {
        $this->assertInstanceOf(ContainerInterface::class, $this->controller()->container());
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
        $this->assertNull($this->controller()->twig('index.html.twig', ['title' => 'title']));
    }

    /**
     * @runInSeparateProcess
     */
    public function testData()
    {
        $this->controller()->setData([
                'first' => 'one',
            ]
        );

        $this->controller()->setData('two', 'second');
        $this->controller()->addData(['first' => 'one'], 'array');
        $this->controller()->addData(['two' => 'second']);
        $this->assertEquals('one', $this->controller()->data('first'));
        $this->assertEquals('two', $this->controller()->data('second'));
        $this->assertArrayHasKey('first', $this->controller()->data());
        $this->assertTrue($this->controller()->hasData('first'));
        $this->assertTrue($this->controller()->hasData('second'));
        $this->assertTrue($this->controller()->hasData('array', 'first'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testFileUpload()
    {
        define('APP_URL', 'http://example.com');
        $this->controller()->fileUpload('img', $this->container->config('bp') . 'app/storage');
        $this->assertTrue($this->container()->isUploaded('img'));
        $this->assertEquals($this->container()->getPost('image'), $this->controller()->fileUpload('image', $this->container->config('bp') . 'app/storage'));
    }

    /**
     * @return ControllerInterface
     */
    public function controller(): ControllerInterface
    {
        return $this->controller;
    }

    /**
     * @return ContainerInterface
     */
    public function container(): ContainerInterface
    {
        return $this->container;
    }
}
