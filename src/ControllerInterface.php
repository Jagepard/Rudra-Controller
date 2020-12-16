<?php

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Controller;

interface ControllerInterface
{
    public function init();
    public function eventRegistration(); // The method for events register
    public function generalPreCall();
    public function before(); // The method is executed before calling the controller
    public function after(); // The method is executed after calling the controller
    public function csrfProtection(): void;
}

