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
use Rudra\ContainerInterface;
use Rudra\Container;
use Rudra\ControllerInterface;
use Rudra\Controller;
use Rudra\Model;

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
        $this->container->setBinding(ContainerInterface::class, Container::$app);
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
                'first' => 'one',
            ]
        );

        $this->controller()->setData('two', 'second');

        $this->assertEquals('one', $this->controller()->data('first'));
        $this->assertEquals('two', $this->controller()->data('second'));
        $this->assertArrayHasKey('first', $this->controller()->data());
        $this->assertTrue($this->controller()->hasData('first'));
        $this->assertTrue($this->controller()->hasData('second'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testFileUpload()
    {
        define('APP_URL', 'http://example.com');
        $this->controller()->fileUpload('img', BP . 'app/storage');
        $this->assertTrue($this->container()->isUploaded('img'));
        $this->assertEquals($this->container()->getPost('image'), $this->controller()->fileUpload('image', BP . 'app/storage'));
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
