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
 * Class DataTrait
 *
 * @package Rudra
 */
trait DataTrait
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
     * @param string $key
     *
     * @return string|array
     */
    public function getData(string $key = null)
    {
        return (isset($key)) ? $this->data[$key] : $this->data;
    }
}
