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

use App\Config\Config;

/**
 * Class Controller
 * @package Rudra
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
     * @param IContainer $di
     * Здесь можно ввести дополнительные параметры при инициализации
     */
    public function init(IContainer $di)
    {
        $this->setDi($di);
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
        if ('twig' == $config) {
            $loader = new \Twig_Loader_Filesystem(BP . '/app/Twig/view');

            $this->setTwig(new \Twig_Environment(
                    $loader, array(
                'cache' => BP . '/vendor/twig/compilation_cache',
                'debug' => true,
                    )
            ));

            if (DEV) {
                $this->getTwig()->addExtension(new \Twig_Extension_Debug());
            }

            $this->addFunctionToTwig('count');
            $this->addFunctionToTwig('md5');
            $this->addFunctionToTwig('auth', true);
        }
    }

    /**
     * @param      $name
     * @param bool $instance
     */
    public function addFunctionToTwig($name, $instance = false)
    {
        if ($instance) {
            $$name = new \Twig_SimpleFunction(
                    $name, function ($url = null, $false = null) {
                return $this->$name($url, $false);
            }
            );
        } else {
            $$name = new \Twig_SimpleFunction(
                    $name, function ($var) {
                return $name($var);
            }
            );
        }

        $this->getTwig()->addFunction($$name);
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
        $data_array['di'] = $this->getDi();
        $path             = str_replace('.', '/', $path);
        $module           = str_replace('.', '/', $module);

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
        $data_array['di'] = $this->getDi();
        $path             = str_replace('.', '/', $path);
        $module           = str_replace('.', '/', $module);

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
     * @param mixed $di
     */
    public function setDi($di)
    {
        $this->di = $di;
    }

    /**
     * @return mixed
     */
    public function getDi()
    {
        return $this->di;
    }

    /**
     * @param $key
     * @param $data
     */
    public function setData($key, $data)
    {
        $this->data[$key] = $data;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getDataItem($key)
    {
        return $this->data[$key];
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
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

}
