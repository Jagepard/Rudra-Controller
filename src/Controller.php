<?php

declare(strict_types=1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Controller;

use Rudra\Auth\AuthTrait;
use Rudra\Container\Interfaces\ApplicationInterface;
use Rudra\Container\Traits\SetApplicationContainersTrait;
use Rudra\Router\Traits\RouterMiddlewareTrait;

class Controller implements ControllerInterface
{
    use AuthTrait;
    use ControllerTrait;
    use RouterMiddlewareTrait;
    use SetApplicationContainersTrait {
        SetApplicationContainersTrait::__construct as protected __setContainerTraitConstruct;
    }

    /**
     * Controller constructor.
     * @param ApplicationInterface $application
     */
    public function __construct(ApplicationInterface $application)
    {
        $this->__setContainerTraitConstruct($application);
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

        if ($this->application()->session()->has("csrf_token")) {
            array_unshift($_SESSION["csrf_token"], md5(uniqid((string)mt_rand(), true)));
            $this->application()->session()
                ->unset("csrf_token", strval(count($this->application()->session()->get("csrf_token")) - 1));
            return;
        }

        for ($i = 0; $i < 4; $i++) {
            $this->application()->session()
                ->set(["csrf_token", [md5(uniqid((string)mt_rand(), true)), "increment"]]);
        }
    }

//    protected function csrfField(): void
//    {
//        $csrf = new Twig_SimpleFunction('csrf_field', function () {
//            return "<input type='hidden' name='csrf_field' value='{$this->application()->getSession("csrf_token", '0')}'>";// @codeCoverageIgnore
//        });
//
//        $this->getTwig()->addFunction($csrf);
//    }
}
