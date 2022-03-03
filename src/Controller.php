<?php

declare(strict_types=1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Controller;

use Rudra\Container\Container;
use Rudra\Container\Facades\Session;
use Rudra\Container\Interfaces\ContainerInterface;
use Rudra\Container\Traits\SetRudraContainersTrait;

class Controller implements ControllerInterface
{
    use ControllerTrait;
    use SetRudraContainersTrait {
        SetRudraContainersTrait::__construct as protected __SetRudraContainersTrait;
    }

    protected ContainerInterface $data;

    public function __construct()
    {
        $this->data = new Container([]);
        $this->csrfProtection();
    }

    public function init()
    {
    }

    public function eventRegistration() // The method for events register
    {
    }

    public function generalPreCall()
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

        if (Session::has("csrf_token")) {
            unset($_SESSION["csrf_token"][count($_SESSION["csrf_token"]) - 1]);
            array_unshift($_SESSION["csrf_token"], md5(uniqid((string)mt_rand(), true)));
            return;
        }

        for ($i = 0; $i < 100; $i++) {
            $csrf[] = md5(uniqid((string)mt_rand(), true));
        }

        Session::set(["csrf_token", $csrf]);
    }
}
