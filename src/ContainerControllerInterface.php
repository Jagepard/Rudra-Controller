<?php

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Controller;

interface ContainerControllerInterface
{
    /**
     * Initializes the necessary data
     * ------------------------------
     * Инициализирует необходимые данные
     *
     * @return void
     */
    public function containerInit(): void;
}
