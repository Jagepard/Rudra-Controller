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
     * Записывает массив $_SESSION['csrf_token']
     * если в массиве больше 2 элементов, убираем первый,
     * таким образом индексы массива сдвигаются на -1
     */
    public function __construct()
    {
        switch (Config::TE) {
            case 'twig':
                $loader     = new \Twig_Loader_Filesystem(BP.'/app/Twig/view');
                $this->twig = new \Twig_Environment(
                    $loader, array(
                        'cache' => BP.'/vendor/twig/compilation_cache',
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
     * @param null $url
     * @param null $false
     * @return bool
     * Проверяет авторизован ли пользователь
     * Если да, то пропускаем выполнение скрипта дальше,
     * Если нет, то редиректим на страницу регистрации
     */
    public function auth($url = null, $false = null)
    {
        if (!isset($url)) {
            if ($this->token === $_SESSION['token']) {
                return true;
            } else {
                (!isset($false)) ? $this->di->get('redirect')->run('register') : false;
            }
        } else {
            if ($url === $this->token) {
                return true;
            } else {
                (!isset($false)) ? $this->di->get('redirect')->run('login') : false;
            }
        }
    }

    /**
     * @param iContainer $di
     */
    public function init(iContainer $di)
    {
        $this->di                 = $di;
        $this->data['session']    = $_SESSION;
        $this->data['title']      = 'helpio.ru - доска объявлений о компьютерах и комплектующих';
        $this->data['sitekey']    = Config::CAPTHA_SITEKEY;
        $this->data['csrf_token'] = $_SESSION['csrf_token'][2];
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
     * @param      $path
     * @param      $module
     * @param null $data_array
     * @param null $static
     * @return string|void
     * Буферизируем вывод.
     * В зависимости от значения параметра $static можно использовать
     * в качестве альтернативного варианта кеширования
     */
    public function setView($path, $module, $data_array = null, $static = null)
    {
        $path   = str_replace('.', '/', $path);
        $module = str_replace('.', '/', $module);

        if (is_array($static) and !isset($static[2])) {
            $static[2] = $this->cached($static[2]);
        }

        if ($static[2] >= 2 or empty($static)) {
            ob_start();
            $this->render($path, $module, $data_array);
            $pageContent = ob_get_clean();
        }

        if (is_array($static) and $static[2] < 3) {
            // Имя файла
            $file = BP.$module.'/view/'.$static[0].'/'.$static[1].'.php';
            // Имя директории
            $dir = BP.$module.'/view/'.$static[0];
            /*
             * Если директории не существует, то создаем
             * с правами 755
             */
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
            /*
             * Если файла нет, то создаем его и записываем
             */
            if (!file_exists($file)) {
                if ($static[2] == 1) {
                    return;
                }
                file_put_contents($file, $pageContent);
                // Перезаписываем
            } elseif (md5($pageContent) != md5(file_get_contents($file))) {
                if ($static[2] == 2) {
                    file_put_contents($file, $pageContent);
                }
            }

            return file_get_contents($file);
        }

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

        $file = BP.$module.'/view/'.$path.'.php';
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
     * @param $value
     * @param $method
     * Декорация для обращения к модели
     */
    public function model($value, $method)
    {
        switch ($value[0]) {
            case 'get':
                if (isset($_GET[$value[1]])) {
                    $this->model->$method($_GET);
                }
                break;
            case 'post':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (isset($_POST[$value[1]])) {
                        $this->model->$method($_POST);
                    }
                }
                break;
        }
    }

    /**
     * @param        $arr
     * @param string $key
     * @return array
     * Вспомогательный метод, возвращаяет массив ключей
     * необходимых для указания белого списка в методе Controller::_404
     */
    public function fetchData($arr, $key = 'ID')
    {
        foreach ($arr as $val) {
            $ids[] = $val[$key];
        }

        return array_values($ids);
    }
}
