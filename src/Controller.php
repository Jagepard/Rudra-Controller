<?php

declare(strict_types=1);

/**
 * @author  : Jagepard <jagepard@yandex.ru">
 * @license https://mit-license.org/ MIT
 */

namespace Rudra\Controller;

use Rudra\Container\Facades\Session;
use Rudra\Container\Interfaces\RudraInterface;

class Controller implements ControllerInterface
{
    public function __construct()
    {
        $this->csrfProtection();
    }

    public function init(): void {}
    public function before(): void {}
    public function after(): void {}

    /**
     * Method to protect against CSRF attack
     *
     * @return void
     */
    public function csrfProtection(): void
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (Session::has("csrf_token")) {
            unset($_SESSION["csrf_token"][count($_SESSION["csrf_token"]) - 1]);
            array_unshift($_SESSION["csrf_token"], md5(uniqid((string)mt_rand(), true)));
            return;
        }

        for ($i = 0; $i < 100; $i++) {
            $csrf[] = bin2hex(random_bytes(32));
        }

        Session::set(["csrf_token", $csrf]);
    }
}
