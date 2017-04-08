<?php

declare(strict_types = 1);

/**
 * Date: 28.03.17
 * Time: 16:08
 *
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace Rudra;

/**
 * Interface ControllerInterface
 *
 * @package Rudra
 */
interface ControllerInterface
{

    /**
     * @param ContainerInterface $container
     * @param string     $templateEngine
     */
    public function init(ContainerInterface $container, string $templateEngine);

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
    public function templateEngine(string $config): void;

    /**
     * CSRF protection
     */
    public function csrfProtection(): void;

    /**
     * @param string $path
     * @param array  $data
     *
     * @return string
     */
    public function view(string $path, array $data = []): string;

    /**
     * @param string $path
     * @param array  $data
     */
    public function render(string $path, array $data = []): void;

    /**
     * @return mixed
     */
    public function container(): ContainerInterface;
}