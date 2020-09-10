<?php

declare(strict_types=1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Controller;

//use \Twig_Environment;
//use \Twig_SimpleFunction;
//use \Twig_Loader_Filesystem;
use Rudra\Auth\AuthTrait;
use Rudra\Container\Interfaces\ApplicationInterface;
use Rudra\Container\Traits\SetApplicationContainersTrait;
use Rudra\Router\Traits\RouterMiddlewareTrait;

class Controller implements ControllerInterface
{
    use AuthTrait;
    use ControllerTrait;
    use RouterMiddlewareTrait;
    use SetApplicationContainersTrait {
        SetApplicationContainersTrait::__construct as protected __setContainerTraitConstruct;
    }

//    /**
//     * Twig_Environment
//     */
//    protected $twig;
//    /**
//     * @var
//     */
//    protected $model;

    /**
     * Controller constructor.
     * @param ApplicationInterface $application
     */
    public function __construct(ApplicationInterface $application)
    {
        $this->__setContainerTraitConstruct($application);
        $this->csrfProtection();
    }

    public function init()
    {
    }

    public function before() // The method is executed before calling the controller
    {
    }

    public function after() // The method is executed after calling the controller
    {
    }

//    public function template(array $config): void
//    {
//        if ($config['engine'] === 'twig') {
//            $loader = new Twig_Loader_Filesystem(
//                $this->application()->config('bp') . $config['view.path']
//            );
//            $this->setTwig(new Twig_Environment($loader, [
//                'cache' => $this->application()->config('bp') . $config['cache.path'],
//                'debug' => ($this->application()->config('env') == 'development') ? true : false,
//            ]));
//
//            $this->csrfField();
//        }
//    }

    public function csrfProtection(): void
    {
        isset($_SESSION) ?: session_start();

        if ($this->application()->session()->has("csrf_token")) {
            array_unshift($_SESSION["csrf_token"], md5(uniqid((string)mt_rand(), true)));
            $this->application()->session()
                ->unset("csrf_token", strval(count($this->application()->session()->get("csrf_token")) - 1));
            return;
        }

        for ($i = 0; $i < 4; $i++) {
            $this->application()->session()
                ->set(["csrf_token", [md5(uniqid((string)mt_rand(), true)), "increment"]]);
        }
    }

//    protected function csrfField(): void
//    {
//        $csrf = new Twig_SimpleFunction('csrf_field', function () {
//            return "<input type='hidden' name='csrf_field' value='{$this->application()->getSession("csrf_token", '0')}'>";// @codeCoverageIgnore
//        });
//
//        $this->getTwig()->addFunction($csrf);
//    }

    public function view(string $path, array $data = []): string
    {
        $path = str_replace('.', '/', $path);
        ob_start();
        $this->render($path, $data);

        return ob_get_clean();
    }

    public function render(string $path, array $data = []): void
    {
        $path = str_replace('.', '/', $path);
        $file = $this->application()->config()->get("bp") . "app/resources/tmpl/" . $path . ".tmpl.php";

        if (count($data)) extract($data, EXTR_REFS);
        if (file_exists($file)) require_once $file;
    }

//    public function twig(string $template, array $params = []): void
//    {
//        $template = str_replace('.', '/', $template);
//        print $this->getTwig()->render($template . ".html.twig", $params);
//    }

//    public function setTwig(Twig_Environment $twig): void
//    {
//        $this->twig = $twig;
//    }

//    public function getTwig(): Twig_Environment
//    {
//        return $this->twig;
//    }
}
