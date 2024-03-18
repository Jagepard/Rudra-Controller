<?php

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Controller;

interface ContainersControllerInterface
{
    /**
     * Initializes the necessary data
     * ------------------------------
     * Инициализирует необходимые данные
     *
     * @return void
     */
    public function containerInit(): void;

    /**
     * The method for events register
     * ------------------------------
     * Метод для регистрации событий
     *
     * @return void
     */
    public function eventRegistration(): void;
}
