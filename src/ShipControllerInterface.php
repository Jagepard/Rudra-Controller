<?php

declare(strict_types=1);

/**
 * @author  : Jagepard <jagepard@yandex.ru">
 * @license https://mit-license.org/ MIT
 */

namespace Rudra\Controller;

interface ShipControllerInterface
{
    public function shipInit(): void;
    public function eventRegistration(): void;
}
