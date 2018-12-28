<?php

declare(strict_types=1);

/**
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2018, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace Rudra\ExternalTraits;

use Rudra\Interfaces\ContainerInterface;

/**
 * Trait ControllerTrait
 * @package Rudra\ExternalTraits
 */
trait ControllerTrait
{

    /**
     * @var
     */
    protected $data;

    /**
     * @param             $data
     * @param string|null $key
     */
    public function setData(string $key = null, $data): void
    {
        (isset($key)) ? $this->data[$key] = $data : $this->data = $data;
    }

    /**
     * @param             $data
     * @param string|null $key
     */
    public function addData(string $key = null, $data): void
    {
        var_dump($data);
        (isset($key)) ? $this->data[$key] = $data : $this->data = array_merge($this->data, $data);
    }

    /**
     * @param string|null $key
     * @return mixed
     */
    public function data(string $key = null)
    {
        return (isset($key)) ? $this->data[$key] : $this->data;
    }

    /**
     * @param string      $key
     * @param string|null $subKey
     * @return bool
     */
    public function hasData(string $key, string $subKey = null): bool
    {
        return (isset($subKey)) ? isset($this->data[$key][$subKey]) : isset($this->data[$key]);
    }

    /**
     * @param $key
     * @param $path
     * @return array|string
     */
    public function fileUpload($key, $path)
    {
        if ($this->container()->isUploaded($key)) {
            $uploadedFile = '/uploaded/' . substr(md5(microtime()), 0, 5) . $this->container()->getUpload($key, 'name');
            $uploadPath   = $path . $uploadedFile;
            move_uploaded_file($this->container()->getUpload($key, 'tmp_name'), $uploadPath);

            return APP_URL . $uploadedFile;
        }

        return $this->container()->getPost($key);
    }

    /**
     * @return ContainerInterface
     */
    abstract public function container(): ContainerInterface;
}
