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


use \Twig_Loader_Filesystem;
use \Twig_Environment;
use \Twig_SimpleFunction;


/**
 * Class Controller
 *
 * @package Rudra
 * Родительский класс для контроллеров
 */
class Controller implements IController
{

    use ContainerTrait, AuthTrait, DataTrait;

    /**
     * IContainer
     */
    protected $container;

    /**
     * Twig_Environment
     */
    protected $twig;

    /**
     * @var
     */
    protected $model;

    /**
     * @param IContainer $container
     * @param string     $templateEngine
     */
    public function init(IContainer $container, string $templateEngine)
    {
        $this->container = $container;
        $this->csrfProtection();
        $this->templateEngine($templateEngine);
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
    public function templateEngine(string $config): void
    {
        if ($config == 'twig') {
            $loader = new Twig_Loader_Filesystem(BP . 'app/resources/twig/view');
            $this->setTwig(new Twig_Environment($loader, [
                'cache' => BP . 'app/resources/twig/compilation_cache',
                'debug' => DEV,
            ]));

            $this->csrfField();
        }
    }

    /**
     * CSRF protection
     */
    public function csrfProtection(): void
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if ($this->container()->hasSession('csrf_token')) {
            array_unshift($_SESSION['csrf_token'], md5(uniqid((string)mt_rand(), true)));
            $this->container()->unsetSession('csrf_token', strval(count($this->container()->getSession('csrf_token')) - 1));
        } else {
            for ($i = 0; $i < 4; $i++) {
                $this->container()->setSession('csrf_token', md5(uniqid((string)mt_rand(), true)), 'increment');
            }
        }
    }

    protected function csrfField(): void
    {
        $csrf = new Twig_SimpleFunction('csrf_field', function () {
            return "<input type='hidden' name='csrf_field' value='{$this->container()->getSession('csrf_token', '0')}'>";// @codeCoverageIgnore
        });

        $this->getTwig()->addFunction($csrf);
    }

    /**
     * @param string $path
     * @param array  $data
     *
     * @return string
     */
    public function view(string $path, array $data = []): string
    {
        $path = str_replace('.', '/', $path);
        ob_start();
        $this->render($path, $data);

        return ob_get_clean();
    }

    /**
     * @param string $path
     * @param array  $data
     */
    public function render(string $path, array $data = []): void
    {
        $path = str_replace('.', '/', $path);
        $file = BP . 'app/resources/tmpl/' . $path . '.tmpl.php';

        if (count($data)) {
            extract($data, EXTR_REFS);
        }
        if (file_exists($file)) {
            require $file;
        }
    }

    /**
     * @param string $template
     * @param array  $params
     */
    public function twig(string $template, array $params = []): void
    {
        echo $this->getTwig()->render($template, $params);
    }

    /**
     * @return mixed
     */
    public function container(): IContainer
    {
        return $this->container;
    }

    /**
     * @param Twig_Environment $twig
     */
    public function setTwig(Twig_Environment $twig): void
    {
        $this->twig = $twig;
    }

    /**
     * @return Twig_Environment
     */
    public function getTwig(): Twig_Environment
    {
        return $this->twig;
    }

    /**
     * @return Model
     */
    public function model(): Model
    {
        return $this->model;
    }

    /**
     * @param string $modelName
     */
    public function setModel(string $modelName): void
    {
        $this->model = $this->container()->new($modelName);
    }
}
