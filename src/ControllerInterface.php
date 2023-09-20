<?php

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Controller;

interface ControllerInterface
{
    /**
     * The method for events register
     * ------------------------------
     * Метод для регистрации событий
     *
     * @return void
     */
    public function eventRegistration();

    /**
     * General pre-call before initialization
     * --------------------------------------
     * Общий предварительный вызов до инициализации
     *
     * @return void
     */
    public function generalPreCall();

    /**
     * Initializes the necessary data
     * ------------------------------
     * Инициализирует необходимые данные
     * 
     * @return void
     */
    public function init();

    /**
     * The method is executed before calling the controller
     * ----------------------------------------------------
     * Метод выполняется перед вызовом контроллера
     *
     * @return void
     */
    public function before();

    /**
     * The method is executed after calling the controller
     * ---------------------------------------------------
     * Метод выполняется после вызова контроллера
     *
     * @return void
     */
    public function after();

    /**
     * Method to protect against CSRF attack
     * -------------------------------------
     * Метод защиты от CSRF-атаки
     *
     * @return void
     */
    public function csrfProtection(): void;
}

