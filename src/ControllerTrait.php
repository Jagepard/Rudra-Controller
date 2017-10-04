<?php

declare(strict_types = 1);

/**
 * Date: 27.03.17
 * Time: 11:50
 *
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace Rudra;

/**
 * Class ControllerTrait
 *
 * @package Rudra
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
    public function setData($data, string $key = null): void
    {
        if (isset($key)) {
            $this->data[$key] = $data;
        } else {
            $this->data = $data;
        }
    }

    /**
     * @param             $data
     * @param string|null $key
     */
    public function addData($data, string $key = null): void
    {
        if (isset($key)) {
            $this->data[$key] = $data;
        } else {
            $this->data = array_merge($this->data, $data);
        }
    }

    /**
     * @param string $key
     *
     * @return string|array
     */
    public function data(string $key = null)
    {
        return (isset($key)) ? $this->data[$key] : $this->data;
    }

    /**
     * @param string $key
     * @param string $arrayKey
     *
     * @return bool
     */
    public function hasData(string $key, string $arrayKey = null): bool
    {
        if (isset($arrayKey)) {
            return isset($this->data[$key][$arrayKey]);
        }

        return isset($this->data[$key]);
    }

    /**
     * @param $key
     * @param $path
     *
     * @return string
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
}
