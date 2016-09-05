<?php

namespace Rudra;

use App\Config\Config;

/**
 * Date: 27.07.15
 * Time: 13:07
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

/**
 * Class Controller
 * @package Core
 * Родительский класс для контроллеров
 */
class Controller
{
    /**
     * @var
     */
    public static $sdi;
    /**
     * @var
     */
    public $di;
    /**
     * @var
     * Для шаблонизатора
     */
    protected $twig;
    /**
     * @var
     * Для данных передаваемых в $data_array
     */
    protected $data;
    /**
     * @var
     * Для экземпляра модели
     */
    protected $model;
    /**
     * @var bool
     * Параметр необходимый для авторизации
     */
    protected $token = false;

    /**
     * @param null $userToken
     * @param bool $false
     * @param array $redirect
     * @return bool
     *
     * Проверяет авторизован ли пользователь
     * Если да, то пропускаем выполнение скрипта дальше,
     * Если нет, то редиректим на страницу регистрации
     */
    public function auth($userToken = null, $false = false, $redirect = ['', 'login'])
    {
        if (!isset($userToken)) {
            if ($this->isToken() === $_SESSION['token']) {
                return true;
            } else {
                (!$false) ? $this->di->get('redirect')->run($redirect[0]) : false;
            }
        } else {
            if ($userToken === $this->token) {
                return true;
            } else {
                (!$false) ? $this->di->get('redirect')->run($redirect[1]) : false;
            }
        }
    }

    /**
     * @param iContainer $di
     *
     * Здесь можно ввести дополнительные параметры при инициализации
     */
    public function init(iContainer $di)
    {
        $this->di = $di;
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

    public function templateEngine($config)
    {
        switch (Config::TE) {
            case 'twig':
                $loader     = new \Twig_Loader_Filesystem(BP . '/app/Twig/view');
                $this->twig = new \Twig_Environment(
                    $loader, array(
                        'cache' => BP . '/vendor/twig/compilation_cache',
                        'debug' => true,
                    )
                );
                if (DEV) {
                    $this->twig->addExtension(new \Twig_Extension_Debug());
                }

                /**
                 * Добавил функцию к Twig
                 */
                $function = new \Twig_SimpleFunction(
                    'count', function ($var) {
                    return count($var);
                }
                );

                $this->twig->addFunction($function);

                $function = new \Twig_SimpleFunction(
                    'md5', function ($var) {
                    return md5($var);
                }
                );

                $this->twig->addFunction($function);

                $function = new \Twig_SimpleFunction(
                    'auth', function ($url = null, $false = null) {
                    return $this->auth($url, $false);
                }
                );

                $this->twig->addFunction($function);
                break;
        }
    }

    public function csrfProtection()
    {
        /**
         * CSRF protection
         */
        if (!isset($_SESSION)) {
            session_start();
        }

        $_SESSION['csrf_token'][] = md5(uniqid(mt_rand(), true));

        for ($i = 1; count($_SESSION['csrf_token']) < 4; $i++) {
            $_SESSION['csrf_token'][$i] = md5(uniqid(mt_rand(), true));
        }

        if (count($_SESSION['csrf_token']) > 4) {
            array_shift($_SESSION['csrf_token']);
        }
    }

    /**
     * @param      $path
     * @param      $module
     * @param null $data_array
     * @return string|void
     * Буферизируем вывод.
     */
    public function setView($path, $module, $data_array = null)
    {
        $path   = str_replace('.', '/', $path);
        $module = str_replace('.', '/', $module);

        ob_start();
        $this->render($path, $module, $data_array);
        $pageContent = ob_get_clean();

        return $pageContent;
    }

    /**
     * @param $path
     * Подключает файл в зависимости от параметров,
     * извлекает из массива $data_array переменные как ссылки
     */
    public function render($path, $module = false, $data_array = null)
    {
        $path   = str_replace('.', '/', $path);
        $module = str_replace('.', '/', $module);

        $file = BP . $module . '/view/' . $path . '.php';

        if (count($data_array)) {
            extract($data_array, EXTR_REFS);
        }

        if (file_exists($file)) {
            require $file;
        }
    }

    /**
     * @param $value
     * @param $param
     * Обрабатывает дополнительные параметры передаваемые в url
     * Используется только при авторутинге
     */
    public function _404($value, $param = [])
    {
        /**
         * Если есть элементы во входящем массиве $value
         */
        if (count($value)) {
            /**
             * Пока $i меньше count($value)
             */
            for ($i = 0; $i < count($value); $i++) {
                /**
                 * Проверяем наличие эмента $param[1][$i]
                 * в котором должен находиться массив со списком
                 * элементов разрешенных в url, если нет сразу
                 * присваиваем $res[$i] = false;
                 */
                if (isset($param[0][$i])) {
                    if (is_array($param[0][$i])) {
                        /**
                         * Если в адресной строке $value[$i] находится
                         * элемент, который есть в массиве с белым списком, то
                         * присваиваем элементу $res[$i] значение true
                         * иначе false
                         */
                        if (in_array($value[$i], $param[0][$i])) {
                            $res[$i] = true;
                        } else {
                            $res[$i] = false;
                        }
                    } elseif ($param[0][$i] == '*') {
                        $res[$i] = true;
                    }
                } else {
                    $res[$i] = false;
                }
            }

            /**
             * Если в массиве нет знвчения false, то завершаем работу метода,
             * если есть, возвращаем "HTTP/1.1 404 Not Found" ниже по коду
             */
            if (!in_array(false, $res)) {
                return;
            }

            header("HTTP/1.1 404 Not Found");
            $this->errorPage();
            exit();
        }
    }

    /**
     * Данные отображаемые, когда воспроизводится ошибка 404
     */
    public function errorPage()
    {
        echo 'Нет такой страницы: <h1>«Ошибка 404»</h1>';
    }

    /**
     * @return boolean
     */
    public function isToken()
    {
        return $this->token;
    }

    /**
     * @param boolean $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }
}
