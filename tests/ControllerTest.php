<?php

declare(strict_types=1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 *
 * phpunit src/tests/ControllerTest --coverage-html src/tests/coverage-html
 */

namespace Rudra\Controller\Tests;

use Rudra\Container\{Interfaces\RudraInterface};
use Rudra\Container\Facades\Rudra as Rudra;
use Rudra\Container\Facades\Session;
use Rudra\Controller\{Controller, ControllerInterface};
use PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

class ControllerTest extends PHPUnit_Framework_TestCase
{
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

        Rudra::config()->set([
            "bp"  => dirname(__DIR__) . '/',
            "env" => "development",
        ]);

        Rudra::binding()->set([RudraInterface::class => Rudra::run()]);

        $this->controller = new Controller(Rudra::run());

        $this->controller->init();
        $this->controller->eventRegistration();
        $this->controller->generalPreCall();
        $this->controller->before();
        $this->controller->after();
    }

    /**
     * @runInSeparateProcess
     */
    public function testInit()
    {
        $this->assertInstanceOf(RudraInterface::class, $this->controller->rudra());
        $this->assertTrue(Session::has("csrf_token"));
        $this->controller->csrfProtection();
    }

    /**
     * @runInSeparateProcess
     */
    public function testFileUpload()
    {
        define("APP_URL", "http://example.com");
        $this->controller->fileUpload("img", Rudra::config()->get("bp") . "app/storage");
        $this->assertTrue(Rudra::request()->files()->isLoaded("img"));
        $this->assertEquals(
            Rudra::request()->post()->get("image"),
            $this->controller->fileUpload("image", Rudra::config()->get("bp") . "app/storage"
        ));
    }
}
