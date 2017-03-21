<?php

/**
 * Date: 27.07.15
 * Time: 13:07
 *
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace Rudra;

use App\Config;

/**
 * Class Controller
 *
 * @package Rudra
 *          Родительский класс для контроллеров
 */
class Controller
{

    /**
     * @var
     */
    protected $container;

    /**
     * @var
     */
    protected $twig;

    /**
     * @var
     */
    protected $data;

    /**
     * @var
     */
    protected $model;

    /**
     * @param \Rudra\IContainer $container
     */
    public function init(IContainer $container)
    {
        $this->container = $container;
        $this->csrfProtection();
        $this->templateEngine(Config::TE);
    }

    /**
     * Метод выполняется перед вызовом контроллера
     */
    public function before()
    {

    }

    /**
     * Метод выполняется после вызова контроллера
     */
    public function after()
    {

    }

    /**
     * @param $config
     */
    public function templateEngine($config)
    {
        if ($config == 'twig') {
            $loader = new \Twig_Loader_Filesystem(BP . 'app/resources/twig/view');
            $this->setTwig(new \Twig_Environment($loader, [
                'cache' => BP . 'app/resources/twig/compilation_cache',
                'debug' => DEV,
            ]));
            $this->csrfField();
        }
    }

    /**
     * CSRF protection
     */
    public function csrfProtection()
    {
        if (!isset($_SESSION)) session_start();

        $this->container()->setSession('csrf_token', md5(uniqid(mt_rand(), true)), 'i++');

        for ($i = 1; count($this->container()->getSession('csrf_token')) < 4; $i++) {
            $this->container()->setSession('csrf_token', md5(uniqid(mt_rand(), true)), $i);
        }

        if (count($this->container()->getSession('csrf_token')) > 4) {
            array_shift($_SESSION['csrf_token']);
        }

    }

    /**
     * @return string
     */
    protected function csrfField()
    {
        $csrf = new \Twig_SimpleFunction('csrf_field', function () {
            return "<input type='hidden' name='csrf_field' value='{$this->container()->getSession('csrf_token', 1)}'>";
        });

        $this->getTwig()->addFunction($csrf);
    }

    /**
     * @param       $path
     * @param array $data
     *
     * @return string
     */
    public function view($path, $data = [])
    {
        $path   = str_replace('.', '/', $path);
        ob_start();
        $this->render($path, $data);

        return ob_get_clean();
    }

    /**
     * @param       $path
     * @param array $data
     */
    public function render($path, $data = [])
    {
        $path   = str_replace('.', '/', $path);
        $file   = BP . 'app/resources/tmpl/' . $path . '.tmpl.php';

        if (count($data)) extract($data, EXTR_REFS);
        if (file_exists($file)) require $file;
    }

    /**
     * @param       $template
     * @param array $params
     */
    public function twig($template, $params = [])
    {
        echo $this->getTwig()->render($template, $params);
    }

    /**
     * @return mixed
     */
    public function container()
    {
        return $this->container;
    }

    /**
     * @param      $data
     * @param null $key
     */
    public function setData($data, $key = null)
    {
        if (isset($key)) {
            $this->data[$key] = $data;
        } else {
            $this->data = $data;
        }
    }

    /**
     * @param null $key
     *
     * @return mixed
     */
    public function getData($key = null)
    {
        return (isset($key)) ? $this->data[$key] : $this->data;
    }

    /**
     * @param mixed $twig
     */
    public function setTwig($twig)
    {
        $this->twig = $twig;
    }

    /**
     * @return mixed
     */
    public function getTwig()
    {
        return $this->twig;
    }

    /**
     * @return mixed
     */
    public function validation()
    {
        return $this->container()->get('validation');
    }

    /**
     * @param        $user
     * @param        $res
     * @param string $message
     *
     * @return mixed
     */
    public function login($user, $res, $message = 'Укажите верные данные')
    {
        $this->container()->get('auth')->login($user, $res, $message);
    }

    public function logout()
    {
        $this->container()->get('auth')->logout();
    }

    public function check()
    {
        $this->container()->get('auth')->check();
    }

    public function auth()
    {
        $this->container()->get('auth')->auth();
    }

    /**
     * @return mixed
     */
    public function redirect()
    {
        return $this->container()->get('redirect');
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function post($key)
    {
        return $this->container()->getPost($key);
    }

    /**
     * @param      $object
     * @param null $params
     *
     * @return mixed
     */
    public function new($object, $params = null)
    {
        return $this->container()->new($object, $params);
    }

    /**
     * @param string      $key
     * @param string|null $subKey
     */
    public function unsetSession(string $key, string $subKey = null)
    {
        $this->container()->unsetSession($key, $subKey);
    }

    /**
     * @return mixed
     */
    public function pagination()
    {
        return $this->container()->get('pagination');
    }

    /**
     * @param $value
     */
    public function setPagination($value)
    {
        $this->container()->set('pagination', new Pagination($value['id']), 'raw');
    }

    /**
     * @return mixed
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @param string      $key
     * @param string      $value
     * @param string|null $subKey
     */
    public function setSession(string $key, string $value, string $subKey = null)
    {
        $this->container()->setSession($key, $value, $subKey);
    }
}
