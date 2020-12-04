<?php

declare(strict_types=1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Controller;

use Rudra\Container\Container;
use Rudra\Container\Interfaces\ContainerInterface;
use Rudra\Container\Interfaces\RudraInterface;
use Rudra\Container\Traits\SetRudraContainersTrait;
use Rudra\Router\Traits\RouterMiddlewareTrait;

class Controller implements ControllerInterface
{
    use ControllerTrait;
    use RouterMiddlewareTrait;
    use SetRudraContainersTrait {
        SetRudraContainersTrait::__construct as protected __SetRudraContainersTrait;
    }

    protected ContainerInterface $data;

    public function __construct(RudraInterface $rudra)
    {
        $this->__SetRudraContainersTrait($rudra);
        $this->data = new Container([]);
        $this->csrfProtection();
    }

    public function init()
    {
    }

    public function before() // The method is executed before calling the controller
    {
    }

    public function after() // The method is executed after calling the controller
    {
    }

    public function csrfProtection(): void
    {
        isset($_SESSION) ?: session_start();

        if ($this->rudra()->session()->has("csrf_token")) {
            unset($_SESSION["csrf_token"][count($_SESSION["csrf_token"]) - 1]);
            array_unshift($_SESSION["csrf_token"], md5(uniqid((string)mt_rand(), true)));
            return;
        }

        for ($i = 0; $i < 4; $i++) {
            $csrf[] = md5(uniqid((string)mt_rand(), true));
        }

        $this->rudra()->session()->set(["csrf_token", $csrf]);
    }
}
