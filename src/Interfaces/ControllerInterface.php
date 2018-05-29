<?php

declare(strict_types=1);

/**
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2018, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace Rudra\Interfaces;

/**
 * Interface ControllerInterface
 * @package Rudra
 */
interface ControllerInterface
{

    /**
     * @param ContainerInterface $container
     * @param array              $template
     * @return mixed
     */
    public function init(ContainerInterface $container, array $template);

    /**
     * Метод выполняется перед вызовом контроллера
     */
    public function before();

    /**
     * Метод выполняется после вызова контроллера
     */
    public function after();

    /**
     * @param $config
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
