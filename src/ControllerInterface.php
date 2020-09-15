<?php

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Controller;

interface ControllerInterface
{
    public function init();
    public function before(); // The method is executed before calling the controller
    public function after(); // The method is executed after calling the controller
    public function template(array $config): void;
    public function csrfProtection(): void;
    public function view(string $path, array $data = []): string;
    public function render(string $path, array $data = []);
}
