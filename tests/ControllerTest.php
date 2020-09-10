<?php

declare(strict_types=1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 *
 * phpunit src/tests/ControllerTest --coverage-html src/tests/coverage-html
 */

namespace Rudra\Controller\Tests;

use Rudra\Container\{Application, Interfaces\ApplicationInterface};
use Rudra\Controller\{Controller, ControllerInterface};
use PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

class ControllerTest extends PHPUnit_Framework_TestCase
{

    protected ApplicationInterface $application;
    protected ControllerInterface $controller;

    protected function setUp(): void
    {
        $_FILES = [
            "upload" =>
                ["name"     => ["img" => "demo.png"],
                 "type"     => ["img" => "image/png"],
                 "tmp_name" => ["img" => "/tmp/phpiQuDkR"],
                 "error"    => ["img" => 0],
                 "size"     => ["img" => 9584],
                ]
        ];

        $_POST = [
            "img"   => "http://example.com/images/img.png",
            'image' => "http://example.com/images/image.png",
        ];

        $this->application = Application::run();
        $this->application->config()->set([
            "bp"  => dirname(__DIR__) . '/',
            "env" => "development",
        ]);

        $this->application->binding()->set([ApplicationInterface::class => Application::run()]);
//        $this->application->objset('debugbar', 'DebugBar\StandardDebugBar');
        $this->controller = new Controller($this->application);
        $this->controller->before();
        $this->controller->init();
//        $this->controller->template([
//            'engine'     => 'twig',
//            'view.path'  => 'app/resources/twig/view',
//            'cache.path' => 'app/resources/twig/compilation_cache'
//        ]);
        $this->controller->after();
    }

    /**
     * @runInSeparateProcess
     */
    public function testInit()
    {
        $this->assertInstanceOf(ApplicationInterface::class, $this->controller->application());
        $this->assertTrue($this->application->session()->has("csrf_token"));
        $this->controller->csrfProtection();
    }

    /**
     * @runInSeparateProcess
     */
    public function testView()
    {
        $this->assertEquals('"Hello World!!!"', $this->controller->view("index", ["title" => "title"]));
    }

    /**
     * @runInSeparateProcess
     */
//    public function testTwig()
//    {
//        $this->assertNull($this->controller->twig("index", ["title" => "title"]));
//    }

    /**
     * @runInSeparateProcess
     */
    public function testData()
    {
        $this->controller->setData(null, ["first" => "one"]);

        $this->controller->setData("second", "two");
        $this->controller->addData("array", ["first" => "one"]);
        $this->controller->addData(null, ["two" => "second"]);
        $this->assertEquals("one", $this->controller->data("first"));
        $this->assertEquals("two", $this->controller->data("second"));
        $this->assertArrayHasKey("first", $this->controller->data());
        $this->assertTrue($this->controller->hasData("first"));
        $this->assertTrue($this->controller->hasData("second"));
        $this->assertTrue($this->controller->hasData("array", "first"));
    }

    /**
     * @runInSeparateProcess
     */
    public function testFileUpload()
    {
        define("APP_URL", "http://example.com");
        $this->controller->fileUpload("img", $this->application->config()->get("bp") . "app/storage");
        $this->assertTrue($this->application->request()->files()->isLoaded("img"));
        $this->assertEquals(
            $this->application->request()->post()->get("image"),
            $this->controller->fileUpload("image", $this->application->config()->get("bp") . "app/storage"
        ));
    }
}
