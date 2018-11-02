## Table of contents

- [\Rudra\Controller](#class-rudracontroller)
- [\Rudra\Interfaces\ControllerInterface (interface)](#interface-rudrainterfacescontrollerinterface)

<hr /><a id="class-rudracontroller"></a>
### Class: \Rudra\Controller

> Class Controller

| Visibility | Function |
|:-----------|:---------|
| public | <strong>addData(</strong><em>mixed</em> <strong>$data</strong>, <em>\string</em> <strong>$key=null</strong>)</strong> : <em>void</em> |
| public | <strong>after()</strong> : <em>void</em><br /><em>Метод выполняется после вызова контроллера</em> |
| public | <strong>auth(</strong><em>\string</em> <strong>$userToken=null</strong>, <em>\string</em> <strong>$redirect=`''`</strong>)</strong> : <em>mixed</em> |
| public | <strong>bcrypt(</strong><em>\string</em> <strong>$password</strong>, <em>\integer</em> <strong>$cost=10</strong>)</strong> : <em>bool/string</em> |
| public | <strong>before()</strong> : <em>void</em><br /><em>Метод выполняется перед вызовом контроллера</em> |
| public | <strong>checkCookie(</strong><em>\string</em> <strong>$redirect=`''`</strong>)</strong> : <em>void</em> |
| public | <strong>container()</strong> : <em>mixed</em> |
| public | <strong>csrfProtection()</strong> : <em>void</em><br /><em>CSRF protection</em> |
| public | <strong>data(</strong><em>\string</em> <strong>$key=null</strong>)</strong> : <em>mixed</em> |
| public | <strong>db()</strong> : <em>mixed</em> |
| public | <strong>fileUpload(</strong><em>mixed</em> <strong>$key</strong>, <em>mixed</em> <strong>$path</strong>)</strong> : <em>array/string</em> |
| public | <strong>getTwig()</strong> : <em>\Rudra\Twig_Environment</em> |
| public | <strong>hasData(</strong><em>\string</em> <strong>$key</strong>, <em>\string</em> <strong>$subKey=null</strong>)</strong> : <em>bool</em> |
| public | <strong>init(</strong><em>\Rudra\Interfaces\ContainerInterface</em> <strong>$container</strong>, <em>array</em> <strong>$config</strong>)</strong> : <em>mixed/void</em> |
| public | <strong>login(</strong><em>\string</em> <strong>$password</strong>, <em>\string</em> <strong>$hash</strong>, <em>\string</em> <strong>$redirect=`'admin'`</strong>, <em>\string</em> <strong>$message=`'Укажите верные данные'`</strong>)</strong> : <em>void</em> |
| public | <strong>logout(</strong><em>\string</em> <strong>$redirect=`''`</strong>)</strong> : <em>void</em> |
| public | <strong>middleware(</strong><em>mixed</em> <strong>$middleware</strong>)</strong> : <em>void</em> |
| public | <strong>new(</strong><em>mixed</em> <strong>$object</strong>, <em>null</em> <strong>$params=null</strong>)</strong> : <em>mixed</em> |
| public | <strong>pagination()</strong> : <em>mixed</em> |
| public | <strong>post(</strong><em>null</em> <strong>$key=null</strong>)</strong> : <em>mixed</em> |
| public | <strong>redirect(</strong><em>null</em> <strong>$target=null</strong>)</strong> : <em>mixed</em> |
| public | <strong>render(</strong><em>\string</em> <strong>$path</strong>, <em>array</em> <strong>$data=array()</strong>)</strong> : <em>void</em> |
| public | <strong>role(</strong><em>\string</em> <strong>$role</strong>, <em>\string</em> <strong>$privilege</strong>, <em>\boolean</em> <strong>$access=false</strong>, <em>\string</em> <strong>$redirect=`''`</strong>)</strong> : <em>mixed</em> |
| public | <strong>setData(</strong><em>mixed</em> <strong>$data</strong>, <em>\string</em> <strong>$key=null</strong>)</strong> : <em>void</em> |
| public | <strong>setPagination(</strong><em>mixed</em> <strong>$value</strong>)</strong> : <em>void</em> |
| public | <strong>setSession(</strong><em>\string</em> <strong>$key</strong>, <em>\string</em> <strong>$value</strong>, <em>\string</em> <strong>$subKey=null</strong>)</strong> : <em>void</em> |
| public | <strong>setTwig(</strong><em>\Twig_Environment</em> <strong>$twig</strong>)</strong> : <em>void</em> |
| public | <strong>template(</strong><em>array</em> <strong>$config</strong>)</strong> : <em>void</em> |
| public | <strong>twig(</strong><em>\string</em> <strong>$template</strong>, <em>array</em> <strong>$params=array()</strong>)</strong> : <em>void</em> |
| public | <strong>unsetSession(</strong><em>\string</em> <strong>$key</strong>, <em>\string</em> <strong>$subKey=null</strong>)</strong> : <em>void</em> |
| public | <strong>userToken()</strong> : <em>string</em> |
| public | <strong>validation()</strong> : <em>mixed</em> |
| public | <strong>view(</strong><em>\string</em> <strong>$path</strong>, <em>array</em> <strong>$data=array()</strong>)</strong> : <em>string</em> |
| protected | <strong>csrfField()</strong> : <em>void</em> |

*This class implements [\Rudra\Interfaces\ControllerInterface](#interface-rudrainterfacescontrollerinterface)*

<hr /><a id="interface-rudrainterfacescontrollerinterface"></a>
### Interface: \Rudra\Interfaces\ControllerInterface

> Interface ControllerInterface

| Visibility | Function |
|:-----------|:---------|
| public | <strong>after()</strong> : <em>void</em><br /><em>Метод выполняется после вызова контроллера</em> |
| public | <strong>before()</strong> : <em>void</em><br /><em>Метод выполняется перед вызовом контроллера</em> |
| public | <strong>csrfProtection()</strong> : <em>void</em><br /><em>CSRF protection</em> |
| public | <strong>init(</strong><em>\Rudra\Interfaces\ContainerInterface</em> <strong>$container</strong>, <em>array</em> <strong>$config</strong>)</strong> : <em>mixed/void</em> |
| public | <strong>render(</strong><em>\string</em> <strong>$path</strong>, <em>array</em> <strong>$data=array()</strong>)</strong> : <em>void</em> |
| public | <strong>template(</strong><em>array</em> <strong>$config</strong>)</strong> : <em>void</em> |
| public | <strong>view(</strong><em>\string</em> <strong>$path</strong>, <em>array</em> <strong>$data=array()</strong>)</strong> : <em>string</em> |

