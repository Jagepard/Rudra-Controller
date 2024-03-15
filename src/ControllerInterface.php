<?php

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Controller;

interface ControllerInterface
{
    /**
     * Initializes the necessary data
     * ------------------------------
     * Инициализирует необходимые данные
     * 
     * @return void
     */
    public function init(): void;

    /**
     * The method is executed before calling the controller
     * ----------------------------------------------------
     * Метод выполняется перед вызовом контроллера
     *
     * @return void
     */
    public function before(): void;

    /**
     * The method is executed after calling the controller
     * ---------------------------------------------------
     * Метод выполняется после вызова контроллера
     *
     * @return void
     */
    public function after(): void;
}
