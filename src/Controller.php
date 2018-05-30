<?php

declare(strict_types=1);

/**
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2018, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace Rudra;

use \Twig_Environment;
use \Twig_SimpleFunction;
use \Twig_Loader_Filesystem;
use Rudra\ExternalTraits\AuthTrait;
use Rudra\ExternalTraits\ContainerTrait;
use Rudra\ExternalTraits\ControllerTrait;
use Rudra\ExternalTraits\RouterMiddlewareTrait;
use Rudra\Interfaces\ContainerInterface;
use Rudra\Interfaces\ControllerInterface;

/**
 * Class Controller
 * @package Rudra
 *
 * Родительский класс для контроллеров
 */
class Controller implements ControllerInterface
{

    use AuthTrait;
    use ContainerTrait;
    use ControllerTrait;
    use RouterMiddlewareTrait;

    /**
     * Twig_Environment
     */
    protected $twig;
    /**
     * @var
     */
    protected $model;
    /**
     * ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     * @param array              $config
     * @return mixed|void
     */
    public function init(ContainerInterface $container, array $config)
    {
        $this->container = $container;
        $this->csrfProtection();
        $this->template($config);
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
     * @param array $config
     */
    public function template(array $config): void
    {
        if ($config['engine'] == 'twig') {
            $loader = new Twig_Loader_Filesystem(
                $this->container()->config('bp') . $config['view.path']
            );
            $this->setTwig(new Twig_Environment($loader, [
                'cache' => $this->container()->config('bp') . $config['cache.path'],
                'debug' => ($this->container()->config('env') == 'development') ? true : false,
            ]));

            $this->csrfField();
        }
    }

    /**
     * CSRF protection
     */
    public function csrfProtection(): void
    {
        isset($_SESSION) ?: session_start();

        if ($this->container()->hasSession('csrf_token')) {
            array_unshift($_SESSION['csrf_token'], md5(uniqid((string)mt_rand(), true)));
            $this->container()->unsetSession('csrf_token', strval(count($this->container()->getSession('csrf_token')) - 1));
            return;
        }

        for ($i = 0; $i < 4; $i++) {
            $this->container()->setSession('csrf_token', md5(uniqid((string)mt_rand(), true)), 'increment');
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
        $file = $this->container()->config('bp') . 'app/resources/tmpl/' . $path . '.tmpl.php';

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
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function twig(string $template, array $params = []): void
    {
        echo $this->getTwig()->render($template, $params);
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
     * @return mixed
     */
    public function container(): ContainerInterface
    {
        return $this->container;
    }
}
