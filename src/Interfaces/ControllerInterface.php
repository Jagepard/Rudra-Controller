<?php

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @copyright Copyright (c) 2019, Jagepard
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Interfaces;

interface ControllerInterface
{
    /**
     * @return mixed
     */
    public function init();

    /**
     * Метод выполняется перед вызовом контроллера
     */
    public function before();

    /**
     * Метод выполняется после вызова контроллера
     */
    public function after();

    /**
     * @param array $config
     */
    public function template(array $config): void;

    /**
     * CSRF protection
     */
    public function csrfProtection(): void;

    /**
     * @param string $path
     * @param array  $data
     * @return string
     */
    public function view(string $path, array $data = []): string;

    /**
     * @param string $path
     * @param array  $data
     */
    public function render(string $path, array $data = []): void;
}
