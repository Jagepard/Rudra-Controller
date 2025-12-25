<?php

declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace Rudra\Controller;

use Rudra\Container\Facades\Session;
use Rudra\Container\Interfaces\RudraInterface;

class Controller implements ControllerInterface
{
    public function __construct()
    {
        $this->csrfProtection();
    }

    /**
     * @return void
     */
    public function init(): void {}

    /**
     * @return void
     */
    public function before(): void {}

    /**
     * @return void
     */
    public function after(): void {}

    /**
     * Method to protect against CSRF attack
     *
     * @return void
     */
    public function csrfProtection(): void
    {
        if (!isset($_SESSION)) {
            $local = (php_sapi_name() == "cli-server");
            session_set_cookie_params([
                'lifetime' => 604800, // 7 days
                'path' => '/',
                'secure' => !$local,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            session_start();
        }

        if (Session::has("csrf_token")) {
            unset($_SESSION["csrf_token"][count($_SESSION["csrf_token"]) - 1]);
            array_unshift($_SESSION["csrf_token"], md5(uniqid((string)mt_rand(), true)));
            return;
        }

        for ($i = 0; $i < 100; $i++) {
            $csrf[] = bin2hex(random_bytes(32));
        }

        Session::set("csrf_token", $csrf);
    }
}
