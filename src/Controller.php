<?php

declare(strict_types=1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Controller;

use Rudra\Container\Facades\Session;
use Rudra\Container\Interfaces\RudraInterface;

class Controller implements ControllerInterface
{
    /**
     * runs csrfProtection
     * ---------------------------------
     * запускает csrfProtection
     */
    public function __construct()
    {
        $this->csrfProtection();
    }

    /**
     * General pre-call before initialization
     * --------------------------------------
     * Общий предварительный вызов до инициализации
     *
     * @return void
     */
    public function shipInit(): void{}

    /**
     * Initializes the necessary data
     * ------------------------------
     * Инициализирует необходимые данные
     * 
     * @return void
     */
    public function init(): void
    {
    }

    /**
     * The method is executed before calling the controller
     * ----------------------------------------------------
     * Метод выполняется перед вызовом контроллера
     *
     * @return void
     */
    public function before(): void
    {
    }

    /**
     * The method is executed after calling the controller
     * ---------------------------------------------------
     * Метод выполняется после вызова контроллера
     *
     * @return void
     */
    public function after(): void // The method is executed after calling the controller
    {
    }

    /**
     * Method to protect against CSRF attack
     * -------------------------------------
     * Метод защиты от CSRF-атаки
     *
     * @return void
     */
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
