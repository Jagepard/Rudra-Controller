<?php

declare(strict_types=1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Controller;

trait ControllerTrait
{
    protected array $data;

    public function setData(?string $key, $data): void
    {
        (isset($key)) ? $this->data[$key] = $data : $this->data = $data;
    }

    public function addData(?string $key, $data): void
    {
        (isset($key)) ? $this->data[$key] = $data : $this->data = array_merge($this->data, $data);
    }

    public function data(string $key = null)
    {
        return (isset($key)) ? $this->data[$key] : $this->data;
    }

    public function hasData(string $key, string $subKey = null): bool
    {
        return (isset($subKey)) ? isset($this->data[$key][$subKey]) : isset($this->data[$key]);
    }

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
