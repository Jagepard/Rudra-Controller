<?php

declare(strict_types=1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Controller;

trait ControllerTrait
{
    public function fileUpload($key, $path)
    {
        if ($this->rudra()->request()->files()->isLoaded($key)) {
            $uploadedFile = "/uploaded/" . substr(md5(microtime()), 0, 5)
                . $this->rudra()->request()->files()->getLoaded($key, "name");
            $uploadPath   = $path . $uploadedFile;
            move_uploaded_file($this->rudra()->request()->files()->getLoaded($key, "tmp_name"), $uploadPath);

            return APP_URL . $uploadedFile;
        }

        return $this->rudra()->request()->post()->get($key);
    }
}
