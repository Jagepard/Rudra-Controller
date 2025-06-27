<?php

declare(strict_types=1);

/**
 * @author  : Jagepard <jagepard@yandex.ru">
 * @license https://mit-license.org/ MIT
 */

namespace Rudra\Controller;

interface ControllerInterface
{
    /**
     * @return void
     */
    public function init(): void;

    /**
     * @return void
     */
    public function before(): void;

    /**
     * @return void
     */
    public function after(): void;
}
